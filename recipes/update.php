<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/recipe.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$recipes = new Recipes($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));



// set ID property of record to read
$recipes->id = isset($_GET['id']) ? $_GET['id'] : die();

// set product property values
$recipes->name = $data->name;
$recipes->image_path = $data->image_path;
$recipes->description = $data->description;
$recipes->modified = date('Y-m-d H:i:s');



// update the product
if($recipes->update()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Recipe was updated."));
}

// if unable to update the product, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update Recipe."));
}
?>