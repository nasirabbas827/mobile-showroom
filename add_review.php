<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the mobile ID from the URL
$mobile_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION["id"];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    // Insert review into database
    $sql = "INSERT INTO Reviews (user_id, mobile_id, review_text, rating, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $user_id, $mobile_id, $review_text, $rating);
    if ($stmt->execute()) {
        // Increase the popularity of the mobile by one
        $update_sql = "UPDATE Mobiles SET popularity_score = popularity_score + 1 WHERE mobile_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $mobile_id);
        $update_stmt->execute();

        echo "Review added successfully.";
    } else {
        echo "Error adding review.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Review</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Add Review</h2>
    <form method="post">
        <div class="form-group">
            <label for="rating">Rating:</label>
            <select class="form-control" id="rating" name="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="review_text">Review:</label>
            <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
