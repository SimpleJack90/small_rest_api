<?php

//Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With ");


//Including database and object files

require_once('../config/database.php');

require_once('../objects/product.php');

//We create new database object and product object
$database=new DatabaseConnection();
$db=$database->getConnection();

$product=new Product($db);

//get id of product to be updated
$data=json_decode(file_get_contents("php://input"));

//Set product id to be deleted

$product->id=$data->id;

if($product->delete()){

    // Set response code - 200 OK.

    http_response_code(200);
 
    //Notify user.
    echo json_encode(array("message" => "Product was deleted."));
}
else{

    // Set response code 503 - Service Unavailable.
    http_response_code(503);
 
    //Notify user.
    echo json_encode(array("message" => "Unable to delete product."));

}

?>