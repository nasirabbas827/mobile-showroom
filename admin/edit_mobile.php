<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Get mobile ID from query string
$mobile_id = $_GET['mobile_id'];

// Fetch current mobile details
$sql = "SELECT * FROM Mobiles WHERE mobile_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mobile_id);
$stmt->execute();
$result = $stmt->get_result();
$mobile = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST["category_id"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $price = $_POST["price"];
    $screen_size = $_POST["screen_size"];
    $ram_size = $_POST["ram_size"];
    $camera_resolution = $_POST["camera_resolution"];
    $operating_system = $_POST["operating_system"];
    $arrival_date = $_POST["arrival_date"];
    $description = $_POST["description"];
    $image = $mobile["image"]; // Default to current image

    // Handle file upload if a new image is provided
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $target_file; // Update image path
    }

    // Update database
    $sql = "UPDATE Mobiles SET category_id = ?, image = ?, brand = ?, model = ?, price = ?, screen_size = ?, ram_size = ?, camera_resolution = ?, operating_system = ?, arrival_date = ?, description = ? WHERE mobile_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdsissssi", $category_id, $image, $brand, $model, $price, $screen_size, $ram_size, $camera_resolution, $operating_system, $arrival_date, $description, $mobile_id);

    if ($stmt->execute()) {
        echo "Mobile updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Mobile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include('admin_navbar.php');
?>

<div class="container mt-5">
    <h2>Edit Mobile</h2>
    <form action="edit_mobile.php?mobile_id=<?php echo $mobile_id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM Categories");
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row["category_id"] == $mobile["category_id"]) ? "selected" : "";
                        echo "<option value='" . $row["id"] . "' $selected>" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="<?php echo $mobile['image']; ?>" alt="Mobile Image" style="width:100px; margin-top:10px;">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="brand">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $mobile['brand']; ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="model">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo $mobile['model']; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo $mobile['price']; ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="screen_size">Screen Size</label>
                <input type="text" class="form-control" id="screen_size" name="screen_size" value="<?php echo $mobile['screen_size']; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="ram_size">RAM Size</label>
                <input type="text" class="form-control" id="ram_size" name="ram_size" value="<?php echo $mobile['ram_size']; ?>" required>
            </div>
            <div class="form-group col-md6">
                <label for="camera_resolution">Camera Resolution</label>
                <input type="text" class="form-control" id="camera_resolution" name="camera_resolution" value="<?php echo $mobile['camera_resolution']; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="operating_system">Operating System</label>
                <input type="text" class="form-control" id="operating_system" name="operating_system" value="<?php echo $mobile['operating_system']; ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="arrival_date">Arrival Date</label>
                <input type="date" class="form-control" id="arrival_date" name="arrival_date" value="<?php echo $mobile['arrival_date']; ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $mobile['description']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Mobile</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
