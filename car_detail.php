<?php
session_start();
require_once 'config.php';

$carId = isset($_GET['id']) ? $_GET['id'] : '';
$car = $carId ? getCarById($carId) : null;

if (!$car) {
    header('Location: index.php');
    exit;
}

$fromSearch = isset($_GET['from']) && $_GET['from'] === 'search';
$sellerName = 'Seller_' . substr($car['id'], -4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year'] . ' - $' . number_format($car['price'])); ?>">
    <title><?php echo htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year']); ?> - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <!-- Modular CSS -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/detail.css">
    <link rel="stylesheet" href="css/buttons.css">
</head>
<body>
    <!-- Detail Container -->
    <div class="detail-container">
        <!-- Header with Back Button -->
        <header class="detail-header">
            <button class="back-btn" id="backButton" onclick="goBack()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <h1>Car Details</h1>
            <div style="width: 40px;"></div> <!-- Spacer for centering -->
        </header>

        <!-- Car Images Gallery -->
        <div class="detail-images">
            <?php if (!empty($car['images'])): ?>
                <div class="image-gallery">
                    <?php foreach ($car['images'] as $index => $image): ?>
                            <div class="gallery-item <?php echo $index === 0 ? 'active' : ''; ?>" role="img" aria-label="Image <?php echo $index + 1; ?> of <?php echo count($car['images']); ?>">
                            <img src="<?php echo htmlspecialchars($image); ?>" 
                                 alt="<?php echo htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year']); ?> - Image <?php echo $index + 1; ?> of <?php echo count($car['images']); ?>"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'%3E%3Crect fill=\'%23f0f0f0\' width=\'400\' height=\'400\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'20\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($car['images']) > 1): ?>
                    <div class="gallery-thumbnails">
                        <?php foreach ($car['images'] as $index => $image): ?>
                            <button type="button" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="showImage(<?php echo $index; ?>)" aria-label="View image <?php echo $index + 1; ?>" aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                <img src="<?php echo htmlspecialchars($image); ?>" 
                                     alt="Thumbnail <?php echo $index + 1; ?>"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect fill=\'%23f0f0f0\' width=\'100\' height=\'100\'/%3E%3C/svg%3E';">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-image-large">
                    <span>No Images Available</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Car Information -->
        <div class="detail-content">
            <!-- Seller Info -->
            <div class="detail-section">
                <div class="seller-info">
                    <div class="user-avatar large"><?php echo strtoupper(substr($sellerName, 0, 1)); ?></div>
                    <div class="seller-details">
                        <div class="seller-name"><?php echo htmlspecialchars($sellerName); ?></div>
                        <div class="seller-label">Seller</div>
                    </div>
                </div>
            </div>

            <!-- Title and Price -->
            <div class="detail-section">
                <h2 class="car-title"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></h2>
                <div class="car-price-large">$<?php echo number_format($car['price']); ?></div>
            </div>

            <!-- Car Specifications -->
            <div class="detail-section">
                <h3 class="section-title">Specifications</h3>
                <div class="specs-grid">
                    <?php if (!empty($car['vehicle_type'])): ?>
                    <div class="spec-item">
                        <div class="spec-label">Vehicle Type</div>
                        <div class="spec-value"><?php echo htmlspecialchars(ucfirst($car['vehicle_type'] === 'motorcycle' ? 'Motorcycle' : $car['vehicle_type'])); ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="spec-item">
                        <div class="spec-label">Make</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['make']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Model</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['model']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Year</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['year']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Mileage</div>
                        <div class="spec-value"><?php echo number_format($car['mileage']); ?> miles</div>
                    </div>
                    <?php if (!empty($car['transmission'])): ?>
                    <div class="spec-item">
                        <div class="spec-label">Transmission</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['transmission']); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($car['fuel_type'])): ?>
                    <div class="spec-item">
                        <div class="spec-label">Fuel Type</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['fuel_type']); ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="spec-item">
                        <div class="spec-label">Color</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['color']); ?></div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Price</div>
                        <div class="spec-value price-value">$<?php echo number_format($car['price']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="detail-section">
                <div class="action-buttons">
                    <button class="btn-primary btn-full" onclick="alert('Contact feature coming soon!')">
                        Contact Seller
                    </button>
                    <button class="btn-secondary btn-full" onclick="goBack()">
                        <?php echo $fromSearch ? 'Back to Search' : 'Back to Feed'; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modular JavaScript -->
    <script src="js/gallery.js"></script>
    <script>
        // Initialize gallery navigation
        const totalImages = <?php echo count($car['images']); ?>;
        if (typeof initGalleryNavigation === 'function') {
            initGalleryNavigation(totalImages);
        }
        
        // Handle back button navigation
        function goBack() {
            const fromSearch = <?php echo $fromSearch ? 'true' : 'false'; ?>;
            
            if (fromSearch) {
                // Return to search page with from=detail parameter
                window.location.href = 'search.php?from=detail';
            } else {
                // Default: return to feed
                window.location.href = 'index.php';
            }
        }
        
        // Make goBack available globally
        window.goBack = goBack;
    </script>
    
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

