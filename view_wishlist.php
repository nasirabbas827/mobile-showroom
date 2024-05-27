<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Function to remove item from wishlist
if (isset($_POST['remove_id'])) {
    $remove_id = $_POST['remove_id'];

    // Delete the item from wishlist
    $delete_sql = "DELETE FROM Wishlist WHERE user_id = ? AND mobile_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $user_id, $remove_id);
    if ($stmt->execute()) {
        echo "Item removed from wishlist successfully.";
        exit; // Stop further execution
    } else {
        echo "Error removing item from wishlist.";
        exit; // Stop further execution
    }
}

// Fetch user's wishlist items
$sql = "SELECT Mobiles.* FROM Wishlist INNER JOIN Mobiles ON Wishlist.mobile_id = Mobiles.mobile_id WHERE Wishlist.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>My Wishlist</h2>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="admin/<?php echo $row['image']; ?>" class="card-img-top" alt="Mobile Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['brand'] . " " . $row['model']; ?></h5>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                        <button class="btn btn-danger remove-from-wishlist" data-id="<?php echo $row['mobile_id']; ?>">Remove from Wishlist</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Handle click event on remove button
    $('.remove-from-wishlist').click(function() {
        var remove_id = $(this).data('id');

        // AJAX request to remove item from wishlist
        $.ajax({
            url: 'view_wishlist.php',
            type: 'POST',
            data: { remove_id: remove_id },
            success: function(response) {
                // Reload the page after successful removal
                location.reload();
            },
            error: function(xhr, status, error) {
                alert("An error occurred while removing item from wishlist.");
                console.error(xhr.responseText);
            }
        });
    });
});
</script>

</body>
</html>
