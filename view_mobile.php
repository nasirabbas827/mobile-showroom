<?php
include('config.php');

session_start();


// Get the mobile ID from the URL
$mobile_id = $_GET['id'];

// Fetch mobile details
$sql = "SELECT Mobiles.*, Categories.name FROM Mobiles INNER JOIN Categories ON Mobiles.category_id = Categories.id WHERE mobile_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mobile_id);
$stmt->execute();
$result = $stmt->get_result();
$mobile = $result->fetch_assoc();

// Fetch mobile reviews
$reviewSql = "SELECT Reviews.*, Users.username FROM Reviews INNER JOIN Users ON Reviews.user_id = Users.id WHERE mobile_id = ?";
$stmt = $conn->prepare($reviewSql);
$stmt->bind_param("i", $mobile_id);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Mobile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .star-rating {
            color: gold;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
<div class="card mb-4">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="admin/<?php echo htmlspecialchars($mobile['image']); ?>" class="card-img" alt="Mobile Image">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($mobile['brand'] . " " . $mobile['model']); ?></h2>
                    <p class="card-text"><?php echo htmlspecialchars($mobile['description']); ?></p>
                    <p class="card-text"><strong>Price:</strong> $<?php echo htmlspecialchars($mobile['price']); ?></p>
                    <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($mobile['name']); ?></p>
                    <p class="card-text"><strong>Screen Size:</strong> <?php echo htmlspecialchars($mobile['screen_size']); ?></p>
                    <p class="card-text"><strong>RAM:</strong> <?php echo htmlspecialchars($mobile['ram_size']); ?></p>
                    <p class="card-text"><strong>Camera:</strong> <?php echo htmlspecialchars($mobile['camera_resolution']); ?></p>
                    <p class="card-text"><strong>Operating System:</strong> <?php echo htmlspecialchars($mobile['operating_system']); ?></p>
                    <p class="card-text"><strong>Popularity Score:</strong> <?php echo htmlspecialchars($mobile['popularity_score']); ?></p>
                    <p class="card-text"><strong>Arrival Date:</strong> <?php echo htmlspecialchars($mobile['arrival_date']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <h3>Reviews</h3>
    <div class="row">
        <?php while ($review = $reviews->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $review['username']; ?></h5>
                    <p class="card-text">
                        <span class="star-rating">
                            <?php for ($i = 1; $i <= $review['rating']; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </span>
                    </p>
                    <p class="card-text"><?php echo $review['review_text']; ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
