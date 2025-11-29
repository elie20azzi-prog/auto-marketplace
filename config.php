<?php
/**
 * Configuration file
 * 
 * Database credentials should be set in config.php (copy from config.example.php)
 * DO NOT commit config.php with real credentials to version control
 */

// Database Configuration
// MAMP default MySQL port is 8889 (not 3306)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_PORT', 8889); // MAMP default MySQL port
    define('DB_NAME', 'auto_marketplace');
    define('DB_USER', 'root');
    define('DB_PASS', 'root'); // MAMP default password is 'root' (try empty string '' if this doesn't work)
}

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Application Configuration
define('SITE_NAME', 'Auto Marketplace');
define('BASE_URL', 'http://localhost');
define('DEBUG_MODE', true); // Set to false in production - temporarily true for debugging

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Include required classes
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Validation.php';

// Function to get all cars as array (MySQL version)
function getAllCars() {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        $stmt = $pdo->query("
            SELECT c.*, 
                   GROUP_CONCAT(ci.image_path ORDER BY ci.display_order SEPARATOR '|||') as image_paths
            FROM cars c
            LEFT JOIN car_images ci ON c.id = ci.car_id
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
        
        $cars = [];
        while ($row = $stmt->fetch()) {
        $car = [
                'id' => $row['id'],
                'make' => $row['make'],
                'model' => $row['model'],
                'year' => (int)$row['year'],
                'mileage' => (int)$row['mileage'],
                'color' => $row['color'],
                'price' => (float)$row['price'],
                'images' => !empty($row['image_paths']) ? explode('|||', $row['image_paths']) : [],
                'created_at' => $row['created_at'] ?? null,
                'vehicle_type' => $row['vehicle_type'] ?? 'car',
                'transmission' => $row['transmission'] ?? null,
                'fuel_type' => $row['fuel_type'] ?? null
            ];
        $cars[] = $car;
    }
    
    return $cars;
    } catch (Exception $e) {
        error_log("Error getting all cars: " . $e->getMessage());
        if (DEBUG_MODE) {
            throw $e;
        }
        return [];
    }
}

// Function to get a single car by ID (MySQL version)
function getCarById($carId) {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        $stmt = $pdo->prepare("
            SELECT c.*, 
                   GROUP_CONCAT(ci.image_path ORDER BY ci.display_order SEPARATOR '|||') as image_paths
            FROM cars c
            LEFT JOIN car_images ci ON c.id = ci.car_id
            WHERE c.id = ?
            GROUP BY c.id
        ");
        
        $stmt->execute([$carId]);
        $row = $stmt->fetch();
        
        if (!$row) {
            return null;
        }
        
        return [
            'id' => $row['id'],
            'make' => $row['make'],
            'model' => $row['model'],
            'year' => (int)$row['year'],
            'mileage' => (int)$row['mileage'],
            'color' => $row['color'],
            'price' => (float)$row['price'],
            'images' => !empty($row['image_paths']) ? explode('|||', $row['image_paths']) : [],
            'created_at' => $row['created_at'] ?? null,
            'vehicle_type' => $row['vehicle_type'] ?? 'car',
            'transmission' => $row['transmission'] ?? null,
            'fuel_type' => $row['fuel_type'] ?? null
        ];
    } catch (Exception $e) {
        error_log("Error getting car by ID: " . $e->getMessage());
        if (DEBUG_MODE) {
            throw $e;
        }
    return null;
    }
}

// Function to add a new car listing (MySQL version)
function addCarListing($carData, $imagePaths) {
    $pdo = null;
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
    
    // Generate unique ID
    $carId = 'car' . time() . rand(1000, 9999);
    
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert car (vehicle_type defaults to 'car' if not provided)
        $vehicleType = isset($carData['vehicle_type']) ? $carData['vehicle_type'] : 'car';
        $transmission = isset($carData['transmission']) ? $carData['transmission'] : null;
        $fuelType = isset($carData['fuel_type']) ? $carData['fuel_type'] : null;
        
        $stmt = $pdo->prepare("
            INSERT INTO cars (id, make, model, vehicle_type, year, mileage, color, transmission, fuel_type, price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $carId,
            $carData['make'],
            $carData['model'],
            $vehicleType,
            $carData['year'],
            $carData['mileage'],
            $carData['color'],
            $transmission,
            $fuelType,
            $carData['price']
        ]);
        
        if (!$result) {
            throw new Exception("Failed to insert car record");
        }
        
        // Insert images
        if (!empty($imagePaths)) {
            $stmt = $pdo->prepare("
                INSERT INTO car_images (car_id, image_path, display_order)
                VALUES (?, ?, ?)
            ");
            
            foreach ($imagePaths as $order => $imagePath) {
                $imgResult = $stmt->execute([$carId, $imagePath, $order]);
                if (!$imgResult) {
                    throw new Exception("Failed to insert image record for: " . $imagePath);
                }
            }
        }
        
        $pdo->commit();
    
    return $carId;
    } catch (PDOException $e) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("PDO Error adding car listing: " . $e->getMessage());
        error_log("SQL State: " . $e->getCode());
        if (DEBUG_MODE) {
            throw $e;
        }
        return false;
    } catch (Exception $e) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Error adding car listing: " . $e->getMessage());
        if (DEBUG_MODE) {
            throw $e;
        }
        return false;
    }
}

// Function to validate uploaded images (using Validation class)
function validateImage($file) {
    $validation = Validation::validateImage($file);
    
    if (!$validation['valid']) {
        return [
            'success' => false,
            'message' => implode(', ', $validation['errors'])
        ];
    }
    
    return ['success' => true];
}

// Function to upload image
function uploadImage($file) {
    $validation = validateImage($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('img_', true) . '.' . $ext;
    $filepath = UPLOAD_DIR . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'path' => 'uploads/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file'];
}
