<?php

//Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers,Authorization, X-Requested-With ");


//Including database and object files

require_once('../config/database.php');

require_once('../objects/product.php');

//We create new database object and product object
$database=new DatabaseConnection();
$db=$database->getConnection();

$product=new Product($db);

//get id of product to be updated
$data=json_decode(file_get_contents("php://input"));

//set Id property of product to be updated
$product->id=$data->id;

//Set product property values
$product->name=$data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;
$product->modified=date('Y-m-d H:i:s');

if($product->update()){

    // Set response code - 200 OK
    http_response_code(200);
 
    // Notify user
    echo json_encode(array("message" => "Product was updated."));

}
else{
    // Set response code  503 - Service unavailable.
    http_response_code(503);
 
    //Notify user
    echo json_encode(array("message" => "Unable to update product."));
}

?>