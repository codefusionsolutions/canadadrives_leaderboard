<?php

/* 
 * This API endpoint is responsible for retrieving user details for viewing.
 * Output is determined whether parameter is set properly and if user record exists.
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new User($db);
$user_details = null;

$response = new stdClass();
$response->success = false;

if (!isset($_GET["id"]) || (isset($_GET["id"]) && intval($_GET["id"]) < 1)) {
    // tell the end user data is incomplete and set response code - 400 bad request
    http_response_code(400);
    
    $response->message = "The id parameter must be set and be an integer greater than 0";
} else {
    // set id of user record to view
    $user->id = $_GET["id"];

    $user_details = $user->readUser(); // read the details of user
    if ($user_details) {
        // set response code - 200 OK
        http_response_code(200);

        $response->success = true;
        $response->message = "User was successfully retrieved.";
        $response->user_details = $user_details;
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the end user that the user does not exist
        $response->message = "User does not exist.";
    }
}

echo json_encode($response);