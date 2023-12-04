<?php

include "../include.php";
include "Product.php";

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Get the JSON data from the request body
$inputData = json_decode(file_get_contents("php://input"), true);

// Create an instance of the Product class
$product = new Product($mysqli);

// Check for missing or invalid data
if (empty($inputData)) {
    echo json_encode(["success" => false, "message" => "Missing or invalid data."]);
    exit;
}

// Check the request method and perform the corresponding action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the search criteria and perform the search
    if (isset($inputData['name'])) {
        $result = $product->searchProductsByName($inputData['name']);
    } elseif (isset($inputData['categoryID'])) {
        $result = $product->searchProductsByCategory($inputData['categoryID']);
    } elseif (isset($inputData['minPrice']) && isset($inputData['maxPrice'])) {
        $result = $product->searchProductsByPriceRange($inputData['minPrice'], $inputData['maxPrice']);
    } else {
        $result = ["success" => false, "message" => "Invalid search criteria."];
    }

    // Echo the result in JSON format
    echo json_encode($result);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request (if needed)
    echo json_encode(["success" => false, "message" => "Invalid request method for this endpoint."]);
} else {
    // Handle other request methods (if needed)
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

// Close the database connection
$mysqli->close();

?>
