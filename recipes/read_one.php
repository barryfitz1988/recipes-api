<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/recipe.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$recipes = new Recipes($db);



// set ID property of record to read
$recipes->id = isset($_GET['id']) ? $_GET['id'] : die();


// read the details of product to be edited
$recipes->readOne();


if($recipes->name!=null){
    // create array
    $recipes_arr = array(
        "id" => $recipes->id,
        "name" => $recipes->name,
        "image_path" =>  $recipes->image_path,
        "description" => $recipes->description,
        "modified" => $recipes->modified,

    );

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($recipes_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user product does not exist
    echo json_encode(array("message" => "Recipe does not exist."));
}
