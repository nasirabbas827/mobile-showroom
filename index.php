<?php
include('config.php');
session_start();


// Fetch all mobiles
$sql = "SELECT Mobiles.*, Categories.name FROM Mobiles INNER JOIN Categories ON Mobiles.category_id = Categories.id";
$result = $conn->query($sql);

// Fetch distinct filter values
$brands = $conn->query("SELECT DISTINCT brand FROM Mobiles");
$os = $conn->query("SELECT DISTINCT operating_system FROM Mobiles");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Mobile Showroom</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
 <style>
.jumbotron {
            height: 500px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="jumbotron text-center">
    <h1>Welcome to Online Mobile Showroom</h1>
    <p>Discover the Latest Mobile Devices with our Showroom</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login to Explore</a>
</div>


<div class="container mt-5">
<h4>Search Mobiles By</h4>

<!-- Search and Filter Form -->
<form id="search-form" method="get">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="brand">Brand</label>
                <select class="form-control" id="brand" name="brand">
                    <option value="">All</option>
                    <?php while ($row = $brands->fetch_assoc()): ?>
                        <option value="<?php echo $row['brand']; ?>"><?php echo $row['brand']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="os">Operating System</label>
                <select class="form-control" id="os" name="os">
                    <option value="">All</option>
                    <?php while ($row = $os->fetch_assoc()): ?>
                        <option value="<?php echo $row['operating_system']; ?>"><?php echo $row['operating_system']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="price_range">Price Range</label>
                <input type="text" class="form-control" id="price_range" name="price_range" placeholder="e.g., 100-500">
            </div>
            <div class="form-group col-md-3">
                <label for="screen_size">Screen Size</label>
                <input type="text" class="form-control" id="screen_size" name="screen_size" placeholder="e.g., 5.5">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="ram_size">RAM Size</label>
                <input type="text" class="form-control" id="ram_size" name="ram_size" placeholder="e.g., 4">
            </div>
            <div class="form-group col-md-3">
                <label for="camera_resolution">Camera Resolution</label>
                <input type="text" class="form-control" id="camera_resolution" name="camera_resolution" placeholder="e.g., 12MP">
            </div>
            <div class="form-group col-md-3">
                <label for="sort_by">Sort By</label>
                <select class="form-control" id="sort_by" name="sort_by">
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="popularity">Popularity</option>
                    <option value="arrival_new">Arrival: New to Old</option>
                    <option value="arrival_old">Arrival: Old to New</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="search">&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block" id="search">Search</button>
            </div>
        </div>
    </form>
    <h2>All Mobiles</h2>
    <!-- Mobile Listing -->
    <div class="row" id="mobile-list">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="admin/<?php echo $row['image']; ?>" class="card-img-top" alt="Mobile Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['brand'] . " " . $row['model']; ?></h5>
                    <p class="card-text"><?php echo $row['description']; ?></p>
                    <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                    <button class="btn btn-primary btn-sm compare-btn m-2 " data-id="<?php echo $row['mobile_id']; ?>">Compare</button>
                    <button class="btn btn-warning btn-sm wishlist-btn m-2 " data-id="<?php echo $row['mobile_id']; ?>">Add to Wishlist</button>
                    <button class="btn btn-info btn-sm review-btn m-2 " data-id="<?php echo $row['mobile_id']; ?>">Add Review</button>
                    <a href="view_mobile.php?id=<?php echo $row['mobile_id']; ?>" class=" m-2 btn btn-secondary btn-sm">View Mobile</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Comparison Section -->
    <div class="mt-5 mb-5">
        <h3>Comparison</h3>
        <div class="row" id="comparison-section"></div>
    </div>
</div>





<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 Mobile Showroom. All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    var compareList = [];

    // Handle compare button click
    $('.compare-btn').click(function() {
        var mobileId = $(this).data('id');
        if (compareList.length < 3 && !compareList.includes(mobileId)) {
            compareList.push(mobileId);
            updateComparisonSection();
        } else {
            alert('You can compare a maximum of 3 mobiles.');
        }
    });

    // Handle wishlist button click
    $('.wishlist-btn').click(function() {
        var mobileId = $(this).data('id');
        $.ajax({
            url: 'wishlist.php',
            method: 'POST',
            data: { mobile_id: mobileId },
            success: function(response) {
                alert(response);
            }
        });
    });

    // Handle review button click
    $('.review-btn').click(function() {
        var mobileId = $(this).data('id');
        window.location.href = 'add_review.php?id=' + mobileId;
    });

    // Update comparison section
    function updateComparisonSection() {
        if (compareList.length === 0) {
            $('#comparison-section').html('');
            return;
        }

        $.ajax({
            url: 'compare_mobiles.php',
            method: 'POST',
            data: { ids: compareList },
            success: function(response) {
                $('#comparison-section').html(response);
                highlightComparison();
            }
        });
    }

    // Highlight the best features in comparison
    function highlightComparison() {
        // Example logic: Highlight the highest RAM size
        var maxRam = Math.max.apply(null, $('#comparison-section .ram-size').map(function() {
            return parseInt($(this).text());
        }).get());

        $('#comparison-section .ram-size').each(function() {
            if (parseInt($(this).text()) === maxRam) {
                $(this).addClass('comparison-highlight');
            }
        });
    }

    // Handle search form submission
    $('#search-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'search_mobiles.php',
            method: 'GET',
            data: formData,
            success: function(response) {
                $('#mobile-list').html(response);
            }
        });
    });
});
</script>


</body>
</html>
