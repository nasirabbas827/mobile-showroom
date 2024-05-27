<?php
include('config.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION["id"]) && isset($_POST['mobile_id'])) {
    $user_id = $_SESSION["id"];
    $mobile_id = $_POST['mobile_id'];

    // Check if the mobile is already in the wishlist
    $checkSql = "SELECT * FROM Wishlist WHERE user_id = ? AND mobile_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $user_id, $mobile_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Remove from wishlist
        $deleteSql = "DELETE FROM Wishlist WHERE user_id = ? AND mobile_id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("ii", $user_id, $mobile_id);
        if ($stmt->execute()) {
            echo "Removed from wishlist.";
        } else {
            echo "Error removing from wishlist.";
        }
    } else {
        // Add to wishlist
        $insertSql = "INSERT INTO Wishlist (user_id, mobile_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ii", $user_id, $mobile_id);
        if ($stmt->execute()) {
            echo "Added to wishlist.";
        } else {
            echo "Error adding to wishlist.";
        }
    }
} else {
    echo "Invalid request.";
}
?>
