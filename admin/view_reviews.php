<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle review deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_review'])) {
    $review_id = $_POST['review_id'];
    $deleteSql = "DELETE FROM Reviews WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $review_id);
    if ($stmt->execute()) {
        $message = "Review deleted successfully.";
    } else {
        $message = "Error deleting review.";
    }
}

// Fetch all reviews
$sql = "SELECT Reviews.*, Users.username, Mobiles.model FROM Reviews 
        INNER JOIN Users ON Reviews.user_id = Users.id
        INNER JOIN Mobiles ON Reviews.mobile_id = Mobiles.mobile_id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Reviews</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Reviews</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
                <th>Mobile Model</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($review = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['username']); ?></td>
                    <td><?php echo htmlspecialchars($review['model']); ?></td>
                    <td><?php echo htmlspecialchars($review['rating']); ?></td>
                    <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <button type="submit" name="delete_review" class="btn btn-danger">Delete</button>
                        </form>
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
