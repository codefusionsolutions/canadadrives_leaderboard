<?php

/* 
 * This API end point is designed to allow the creation of new users.
 * Output will be determined if data input is complete or if any parameters are missing.
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

$response->success = false;
  
// make sure data is not empty
if ($user->isDataComplete($data)) {
    // set user property values
    $user->name = $data->name;
    $user->age = $data->age;
    $user->street = $data->street;
    $user->city = $data->city;
    $user->state = $data->state;
    $user->country = $data->country;
    $user->zip = $data->zip;
    $user->date_created = date('Y-m-d H:i:s');

    // create the user
    if ($user->create()) {
        // set response code - 201 created
        http_response_code(201);
  
        // tell the end user
        $response->success = true;
        $response->message = "User was created.";
        $response->user_details = $user->readUser();
    } else {
        // if unable to create the user, tell the end user
        // set response code - 503 service unavailable
        http_response_code(503);
  
        $response->message = "Unable to create user.";
    }
} else {
    // tell the end user data is incomplete and set response code - 400 bad request
    http_response_code(400);

    // tell the end user about missing data input
    $response->message = "Unable to create user as there are missing fields.";
    $response->missing_fields = $user->missing_fields;
}

echo json_encode($response);