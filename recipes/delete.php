<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE");
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

// delete the product
if($recipes->delete()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Recipe was deleted."));
}

// if unable to delete the product
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete Recipe."));
}
?>