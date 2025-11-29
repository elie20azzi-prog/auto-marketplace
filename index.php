<?php
session_start();
require_once 'config.php';

// Get cars from database
$cars = getAllCars();
// Reverse to show newest first
$cars = array_reverse($cars);

// Check for database errors
$dbError = null;
if (empty($cars) && isset($_GET['db_error'])) {
    $dbError = 'Database connection issue. Please check your configuration.';
}

$successMessage = isset($_GET['success']) ? 'Car listing added successfully!' : '';

// Get form data from session if form was submitted with errors
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse and search car listings on AutoFeed - Your trusted marketplace for buying and selling vehicles.">
    <title>Car Listings - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <!-- Modular CSS -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/feed.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/toast.css">
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Success Message Toast -->
    <?php if ($successMessage): ?>
        <div class="toast" id="successToast">
            <span><?php echo htmlspecialchars($successMessage); ?></span>
        </div>
    <?php endif; ?>

    <!-- Main Feed Container -->
    <div class="feed-container">
        <!-- Feed Header -->
        <header class="feed-header">
            <h1>AutoFeed</h1>
    </header>

        <!-- Feed Posts -->
        <main class="feed-main" id="main-content" role="main">
            <?php if (empty($cars)): ?>
                <div class="empty-feed">
                    <div class="empty-icon">🚗</div>
                    <h2>No listings yet</h2>
                    <p>Be the first to post a car!</p>
                    <a href="add_post.php" class="btn-primary" style="text-decoration: none; display: inline-block;">Post Your First Car</a>
                </div>
            <?php else: ?>
                <?php foreach ($cars as $car): 
                    $description = $car['year'] . ' • ' . number_format($car['mileage']) . ' miles • ' . $car['color'];
                    $sellerName = 'Seller_' . substr($car['id'], -4); // Generate seller name from ID
                    
                    // Format posted date
                    $postedDate = '';
                    if (!empty($car['created_at'])) {
                        $date = new DateTime($car['created_at']);
                        $now = new DateTime();
                        $diff = $now->diff($date);
                        
                        if ($diff->days == 0) {
                            $postedDate = 'Today';
                        } elseif ($diff->days == 1) {
                            $postedDate = 'Yesterday';
                        } elseif ($diff->days < 7) {
                            $postedDate = $date->format('l'); // Day name (Monday, Tuesday, etc.)
                        } else {
                            $postedDate = $date->format('M j, Y'); // Dec 15, 2024
                        }
                    }
                ?>
                    <article class="feed-post" data-car-id="<?php echo htmlspecialchars($car['id']); ?>" data-car-image="<?php echo !empty($car['images'][0]) ? htmlspecialchars($car['images'][0]) : ''; ?>">
                        <!-- Post Header -->
                        <div class="post-header">
                            <div class="post-user">
                                <div class="user-avatar"><?php echo strtoupper(substr($sellerName, 0, 1)); ?></div>
                                <span class="username"><?php echo htmlspecialchars($sellerName); ?></span>
                            </div>
                        </div>

                        <!-- Post Image -->
                        <a href="car_detail.php?id=<?php echo urlencode($car['id']); ?>" class="post-image-link" aria-label="View details for <?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?>">
                            <div class="post-image-container">
                                <?php if (!empty($car['images'][0])): ?>
                                    <img src="<?php echo htmlspecialchars($car['images'][0]); ?>" 
                                         alt="<?php echo htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year']); ?>"
                                         class="post-image"
                                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'%3E%3Crect fill=\'%23f0f0f0\' width=\'400\' height=\'400\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'20\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                                <?php else: ?>
                                    <div class="post-image-placeholder" role="img" aria-label="No image available">
                                        <span>No Image</span>
                                    </div>
                                <?php endif; ?>
                                <?php if (count($car['images']) > 1): ?>
                                    <div class="image-indicator">
                                        <span>+<?php echo count($car['images']) - 1; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <!-- Post Actions -->
                        <div class="post-actions">
                            <button class="action-btn" aria-label="Like">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                            <button class="action-btn" aria-label="Comment">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </button>
                            <button class="action-btn" aria-label="Share">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                    <polyline points="16 6 12 2 8 6"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="15"></line>
                                </svg>
                            </button>
                        </div>

                        <!-- Post Content -->
                        <div class="post-content">
                            <div class="post-title">
                                <strong><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></strong>
                                <span class="post-price">$<?php echo number_format($car['price']); ?></span>
                            </div>
                            <p class="post-description">
                                <strong><?php echo htmlspecialchars($sellerName); ?></strong> 
                                <?php echo htmlspecialchars($description); ?>
                            </p>
                            <?php if ($postedDate): ?>
                                <div class="post-date" style="font-size: 12px; color: var(--text-tertiary); margin-top: 8px;">
                                    Posted <?php echo htmlspecialchars($postedDate); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <!-- Fixed Bottom Navigation -->
        <nav class="bottom-nav">
            <button class="nav-item active" onclick="window.location.href='index.php'">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Home</span>
            </button>
            <a href="search.php" class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <span>Search</span>
            </a>
            <a href="add_post.php" class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Post</span>
            </a>
        </nav>
    </div>

    <!-- Search Modal -->
    <div id="searchModal" class="modal">
        <div class="modal-content search-modal">
            <div class="modal-header">
                <h2>Search Cars</h2>
                <button class="modal-close" onclick="closeSearchModal()" aria-label="Close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="search-container">
                <div class="search-input-wrapper">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by make, model, year..." autocomplete="off" aria-label="Search cars by make, model, or year">
                </div>
            <div class="search-filters">
                    <div class="multi-select-wrapper">
                        <div class="multi-select-trigger" id="makeTrigger">
                            <span class="multi-select-text">All Makes</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="multi-select-dropdown" id="makeDropdown">
                            <div class="multi-select-option select-all-option" data-value="all">
                                <input type="checkbox" id="makeAll" class="multi-select-checkbox">
                                <label for="makeAll">All Makes</label>
                            </div>
                            <?php 
                            if (!empty($cars)) {
                            $makes = array_unique(array_column($cars, 'make'));
                            sort($makes);
                            foreach ($makes as $make): 
                            ?>
                                <div class="multi-select-option" data-value="<?php echo htmlspecialchars($make); ?>">
                                    <input type="checkbox" id="make_<?php echo htmlspecialchars($make); ?>" class="multi-select-checkbox" value="<?php echo htmlspecialchars($make); ?>">
                                    <label for="make_<?php echo htmlspecialchars($make); ?>"><?php echo htmlspecialchars($make); ?></label>
                                </div>
                            <?php 
                                endforeach;
                            } else {
                                // Show message if no cars in database
                                echo '<div class="multi-select-option" style="color: var(--text-tertiary); padding: 12px 16px; font-style: italic;">No makes available. Add listings first.</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="multi-select-wrapper">
                        <div class="multi-select-trigger" id="yearTrigger">
                            <span class="multi-select-text">All Years</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="multi-select-dropdown" id="yearDropdown">
                            <div class="multi-select-option select-all-option" data-value="all">
                                <input type="checkbox" id="yearAll" class="multi-select-checkbox">
                                <label for="yearAll">All Years</label>
                            </div>
                            <?php 
                            if (!empty($cars)) {
                            $years = array_unique(array_column($cars, 'year'));
                            rsort($years);
                            foreach ($years as $year): 
                            ?>
                                <div class="multi-select-option" data-value="<?php echo htmlspecialchars($year); ?>">
                                    <input type="checkbox" id="year_<?php echo htmlspecialchars($year); ?>" class="multi-select-checkbox" value="<?php echo htmlspecialchars($year); ?>">
                                    <label for="year_<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></label>
                                </div>
                            <?php 
                                endforeach;
                            } else {
                                // Show message if no cars in database
                                echo '<div class="multi-select-option" style="color: var(--text-tertiary); padding: 12px 16px; font-style: italic;">No years available. Add listings first.</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <input type="number" id="maxPrice" placeholder="Max Price" min="0">
                </div>
                <div id="searchResults" class="search-results"></div>
            </div>
        </div>
                </div>


    <!-- Modular JavaScript -->
    <script src="js/utils.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/multiselect.js"></script>
    <script src="js/search.js"></script>
    <script src="js/toast.js"></script>
    <script src="js/main.js"></script>
    
    <?php if (!empty($errors) || isset($_GET['form_error'])): ?>
    <script>
        // Redirect to add_post.php if there are form errors
        document.addEventListener('DOMContentLoaded', function() {
            window.location.href = 'add_post.php';
        });
    </script>
    <?php endif; ?>
    
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
