<?php

/*
 * This API endpoint allow the ability to increment/decrement a user's points by 1 point.
 * Assumption is made that a user's total points can go into negative territory.
 * Output will be determined on proper parameter format and whether or not the user record exists.
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate user object
include_once '../objects/user.php';
  
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$response = new stdClass();
$response->success = false;

if (empty($data->id) || !isset($data->action) || !in_array($data->action, ["increment", "decrement"])) {
    // tell the end user that action parameter is missing or invalid - 400 bad request
    http_response_code(400);

    // tell the end user about missing data input
    $response->message = "Unable to update points as id and action parameters must be set and action is either 'increment' or 'decrement'";
} else if (!empty($data->id) && in_array($data->action, ["increment", "decrement"])) {
    $user->id = $data->id;
    
    // check if user exists first
    $user_details = $user->readUser();
    if (!$user_details) {
        // tell the end user that resource cannot be found - 404 bad request
        http_response_code(404);

        // tell the end user that user cannot be found
        $response->message = "Unable to find user.";
    } elseif ($user->updatePoints($data->action)) {
        // set response code - 200 OK
        http_response_code(200);

        $response->success = true;
        $response->message = "User points were successfully updated.";
        $response->updated_leaderboard = $user->getLeaderboard();
    } else {
        // if unable to update the user, set response code - 503 service unavailable
        http_response_code(503);

        // tell the end user
        $response->message = "Unable to update user points.";
    }
}

echo json_encode($response);