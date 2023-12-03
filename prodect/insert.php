<?php

// File: insertProduct.php

// Purpose:
// This file inserts a new product into the "product" table in the database.

// Usage:
// Use POST method to add a new product. Send product details in JSON format in the request body.
// Example request: POST http://example.com/insertProduct
// Request body: {"CategoryID":1,"Name":"New Product","Description":"Product Description",...}
// Example response: {"message":"Product created successfully."}



// Include the database connection file
include "../include.php";

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the JSON data from the request body
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Function to insert a new product
    function insertProduct($data)
    {
        global $mysqli;

        // Extract data from the input
        $categoryID = $data['CategoryID'];
        $name = $data['Name'];
        $description = isset($data['Description']) ? $data['Description'] : null;
        $price = $data['Price'];
        $stockQuantity = $data['StockQuantity'];
        $image = isset($data['Image']) ? $data['Image'] : null;

        // Insert data into the database
        $query = "INSERT INTO product (CategoryID, Name, Description, Price, StockQuantity, Image) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $mysqli->prepare($query);
        $statement->bind_param('issdis', $categoryID, $name, $description, $price, $stockQuantity, $image);
        $statement->execute();

        http_response_code(201); // Created
        echo json_encode(array("message" => "Product created successfully."));
    }

    // Call the function to insert a new product
    insertProduct($inputData);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Invalid request method. Use POST."));
}

?>
