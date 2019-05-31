<?php

// Required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Include database and object files
require_once('../config/database.php');
require_once('../objects/category.php');
 
// Instantiate database and category object
$database = new DatabaseConnection();
$db = $database->getConnection();
 
// Initialize object
$category = new Category($db);
 
// Query categories
$stmt = $category->readAll();
$num = $stmt->rowCount();
 
// Check if more than 0 record found
if($num>0){
 
    // products array
    $categories_arr=array();
    $categories_arr["records"]=array();
 
    // retrieve our table contents
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $category_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );
 
        array_push($categories_arr["records"], $category_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show categories data in json format
    echo json_encode($categories_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no categories found
    echo json_encode(
        array("message" => "No categories found.")
    );
}

?>