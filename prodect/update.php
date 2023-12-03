<?php

// File: updateProduct.php

// Purpose:
// This file updates an existing product in the "product" table in the database.

// Usage:
// Use PUT or PATCH method to update an existing product.
// Send updated product details in JSON format in the request body.
// Request body: {"ProductID":1,"CategoryID":1,"Name":"Updated Laptop",...}
// Example response: {"message":"Product updated successfully."}



// Include the database connection file
include "../include.php";

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, PATCH");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Check if the request method is PUT or PATCH
if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH') {
    // Get the JSON data from the request body
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Check if productId is provided
    if (isset($inputData['ProductID'])) {
        $productId = $inputData['ProductID'];

        // Function to update an existing product
        function updateProduct($productId, $data)
        {
            global $mysqli;

            // Prepare the update query
            $updateFields = array();
            $updateData = array();
            foreach ($data as $key => $value) {
                if ($key !== 'ProductID') {
                    $updateFields[] = "$key = ?";
                    $updateData[] = $value;
                }
            }

            $updateFieldsStr = implode(', ', $updateFields);
            $query = "UPDATE product SET $updateFieldsStr WHERE ProductID = ?";
            $updateData[] = $productId;

            // Prepare and execute the update statement
            $updateStatement = $mysqli->prepare($query);
            $types = str_repeat('s', count($updateData));
            $updateStatement->bind_param($types, ...$updateData);
            
            if ($updateStatement->execute()) {
                echo json_encode(array("message" => "Product updated successfully."));
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Error updating product."));
            }

            $updateStatement->close();
        }

        // Call the function to update the product
        updateProduct($productId, $inputData);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Please provide a valid product ID."));
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Invalid request method. Use PUT or PATCH."));
}

?>
