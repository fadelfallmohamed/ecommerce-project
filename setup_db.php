<?php
$conn = new mysqli('localhost', 'root', '');

// Create database
if ($conn->query('CREATE DATABASE IF NOT EXISTS `ecommerce-project`')) {
    echo "Database created successfully\n";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db('ecommerce-project');

// Run migrations
echo "Running migrations...\n";
$output = [];
exec('php artisan migrate --force', $output, $return_var);
echo implode("\n", $output) . "\n";

if ($return_var === 0) {
    echo "Migrations completed successfully\n";
} else {
    echo "Error running migrations\n";
}

$conn->close();
?>
