<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//Including database and object files

require_once('../config/database.php');

require_once('../objects/product.php');

//We create new database object and product object

$database=new DatabaseConnection();
$db=$database->getConnection();

$product=new Product($db);

//set ID property of record to read
$product->id=isset($_GET['id'])? $_GET['id']: die();

//read details of product to be edited

$product->read_one();

if($product->name!=null){
    //create array to place existing data

    $product_item=array(
        "id" =>  $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name
    );

     // set response code - 200 OK
     http_response_code(200);
 
     // make it json format
     echo json_encode($product_item);
}else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Product does not exist."));
}
?>