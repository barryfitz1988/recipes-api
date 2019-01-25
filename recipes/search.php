<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/recipe.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$recipes = new Recipes($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// get keywords
$keywords=isset($_GET["query"]) ? $_GET["query"] : "";

// query products
$stmt = $recipes->search($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $recipes_arr=array();
    $recipes_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $recipes_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "image_path" => $image_path
        );

        array_push($recipes_arr["records"], $recipes_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data
    echo json_encode($recipes_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No Recipes found.")
    );
}
?>