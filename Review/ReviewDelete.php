<?php

include "../include.php";
include "Review.php";

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["success" => false, "message" => $_SERVER['REQUEST_METHOD'] ." is Invalid request method."]);
    exit;
}
$inputData = json_decode(file_get_contents("php://input"), true);

// Assuming you have the following data from the API request
$reviewID = $inputData['reviewID'];

// Create a new instance of the Review class
$reviewObj = new Review($mysqli);

// Delete the review
$result = $reviewObj->deleteReview($reviewID);

// Return the result as JSON
echo json_encode($result);

?>
