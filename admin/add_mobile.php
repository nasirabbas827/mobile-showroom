<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

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

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert into database
    $sql = "INSERT INTO Mobiles (category_id, image, brand, model, price, screen_size, ram_size, camera_resolution, operating_system, arrival_date, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdsissss", $category_id, $target_file, $brand, $model, $price, $screen_size, $ram_size, $camera_resolution, $operating_system, $arrival_date, $description);

    if ($stmt->execute()) {
        echo "New mobile added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include('admin_navbar.php');
?>

<div class="container mt-5 mb-5">
    <h2>Add New Mobile</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM Categories");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="brand">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" required>
            </div>
            <div class="form-group col-md-6">
                <label for="model">Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group col-md-6">
                <label for="screen_size">Screen Size</label>
                <input type="text" class="form-control" id="screen_size" name="screen_size" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="ram_size">RAM Size</label>
                <input type="text" class="form-control" id="ram_size" name="ram_size" required>
            </div>
            <div class="form-group col-md6">
                <label for="camera_resolution">Camera Resolution</label>
                <input type="text" class="form-control" id="camera_resolution" name="camera_resolution" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="operating_system">Operating System</label>
                <input type="text" class="form-control" id="operating_system" name="operating_system" required>
            </div>
            <div class="form-group col-md-6">
                <label for="arrival_date">Arrival Date</label>
                <input type="date" class="form-control" id="arrival_date" name="arrival_date" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Mobile</button>
        <a class="btn btn-outline-dark" href="view_mobiles.php">View Mobiles</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
