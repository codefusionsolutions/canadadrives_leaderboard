<?php

/* 
 * This API endpoint will allow the ability to delete an existing user.
 * Output will be determined if paramters are set properly or if user record exists.
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../config/database.php';
include_once '../objects/user.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new User($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
$response = new stdClass();
$response->success = false;

// check if user id parameter is set properly
if (!empty($data->id)) {
    // set user id to be deleted
    $user->id = $data->id;
    $user_record = $user->readUser();

    // check if user record based on id exists
    if (!$user_record) {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the end user that the user does not exist
        $response->message = "User does not exist.";
    } elseif ($user->delete()) {
        // delete the user and set response code - 200 ok
        http_response_code(200);

        // tell the end user
        $response->success = true;
        $response->message = "User was deleted.";
        $response->delete_user_id = $data->id;
    } else {
        // if unable to delete the user, set response code - 503 service unavailable
        http_response_code(503);

        // tell the end user
        $response->message = "Unable to delete user.";
    }
} else {
    // tell the end user data is incomplete and set response code - 400 bad request
    http_response_code(400);
    
    $response->message = "The id parameter must be set properly in order to delete a user.";
}

echo json_encode($response);