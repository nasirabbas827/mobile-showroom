<?php
include('config.php');

$brand = $_GET['brand'] ?? '';
$os = $_GET['os'] ?? '';
$price_range = $_GET['price_range'] ?? '';
$screen_size = $_GET['screen_size'] ?? '';
$ram_size = $_GET['ram_size'] ?? '';
$camera_resolution = $_GET['camera_resolution'] ?? '';
$sort_by = $_GET['sort_by'] ?? '';

$sql = "SELECT Mobiles.*, Categories.name FROM Mobiles INNER JOIN Categories ON Mobiles.category_id = Categories.id WHERE 1=1";

if ($brand) {
    $sql .= " AND brand = '$brand'";
}
if ($os) {
    $sql .= " AND operating_system = '$os'";
}
if ($price_range) {
    // Check if the price_range is not empty before splitting
    if (strpos($price_range, '-') !== false) {
        list($min_price, $max_price) = explode('-', $price_range);
        $sql .= " AND price BETWEEN $min_price AND $max_price";
    }
}
if ($screen_size) {
    $sql .= " AND screen_size = $screen_size";
}
if ($ram_size) {
    $sql .= " AND ram_size = $ram_size";
}
if ($camera_resolution) {
    $sql .= " AND camera_resolution = '$camera_resolution'";
}

// Add default sorting option if $sort_by is empty
if (!$sort_by) {
    $sort_by = 'price_asc';
}

switch ($sort_by) {
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'popularity':
        $sql .= " ORDER BY popularity_score DESC";
        break;
    case 'arrival_new':
        $sql .= " ORDER BY arrival_date DESC";
        break;
    case 'arrival_old':
        $sql .= " ORDER BY arrival_date ASC";
        break;
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="admin/<?php echo $row['image']; ?>" class="card-img-top" alt="Mobile Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo $row['brand'] . " " . $row['model']; ?></h5>
                <p class="card-text"><?php echo $row['description']; ?></p>
                <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                <button class="btn btn-primary btn-sm compare-btn" data-id="<?php echo $row['mobile_id']; ?>">Compare</button>
            </div>
        </div>
    </div>
<?php endwhile; ?>
