<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle deletion of mobile
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Mobiles WHERE mobile_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "Mobile deleted successfully!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch all mobiles
$sql = "SELECT Mobiles.*, Categories.name FROM Mobiles INNER JOIN Categories ON Mobiles.category_id = Categories.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Mobiles</title>
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
    <h2>All Mobiles</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Price</th>
                <th>Screen Size</th>
                <th>RAM Size</th>
                <th>Camera Resolution</th>
                <th>Operating System</th>
                <th>Arrival Date</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="<?php echo $row['image']; ?>" alt="Mobile Image" style="width:100px;"></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['brand']; ?></td>
                <td><?php echo $row['model']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['screen_size']; ?></td>
                <td><?php echo $row['ram_size']; ?></td>
                <td><?php echo $row['camera_resolution']; ?></td>
                <td><?php echo $row['operating_system']; ?></td>
                <td><?php echo $row['arrival_date']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <a href="edit_mobile.php?mobile_id=<?php echo $row['mobile_id']; ?>" class="btn btn-warning btn-sm m-2">Edit</a>
                    <a href="view_mobiles.php?delete_id=<?php echo $row['mobile_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this mobile?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
