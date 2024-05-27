<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    $ids = implode(',', array_map('intval', $ids));

    $sql = "SELECT * FROM Mobiles WHERE mobile_id IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()): ?>
    <div class="col-md-4">
        <div class="card">
            <img src="admin/<?php echo $row['image']; ?>" class="card-img-top" alt="Mobile Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo $row['brand'] . " " . $row['model']; ?></h5>
                <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                <p class="card-text ram-size">RAM: <?php echo $row['ram_size']; ?> GB</p>
                <p class="card-text">Screen Size: <?php echo $row['screen_size']; ?> inches</p>
                <p class="card-text">Camera: <?php echo $row['camera_resolution']; ?></p>
                <p class="card-text">OS: <?php echo $row['operating_system']; ?></p>
                <p class="card-text">Arrival Date: <?php echo $row['arrival_date']; ?></p>
                <p class="card-text">Description: <?php echo $row['description']; ?></p>
            </div>
        </div>
    </div>
    <?php endwhile;
}
?>
