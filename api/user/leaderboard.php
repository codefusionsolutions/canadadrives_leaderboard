<?php

/* 
 * This end point is designed to retrieve the list of users for the leaderboard.
 * Output returned is based on data retrieved from the user table.
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
  
// instantiate database and user object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$user = new user($db);
  
// query users and build leaderboard
$leaderboard = $user->getLeaderboard();

$response = new stdClass();
$response->success = false;
  
// check if any users are found
if (count($leaderboard)) {
    // set response code - 200 OK
    http_response_code(200);
  
    $response->success = true;
    $response->leaderboard = $leaderboard;
} else {
    // no users found will be here, set response code - 404 Not found
    http_response_code(404);
  
    // tell the end user no users found for the leaderboard
    $response->error_message = "No users found for the leaderboard";
}

// show leaderboard response data in json format
echo json_encode($response);