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

//Get posted data

$data=json_decode(file_get_contents("php://input"));

//Make sure data is not empty

if( !empty($data->name) && 
    !empty($data->price) && 
    !empty($data->description) && 
    !empty($data->category_id)){

        //Set product property values
        $product->name=$data->name;
        $product->price=$data->price;
        $product->description=$data->description;
        $product->category_id=$data->category_id;
        $product->created=date('Y-m-d H:i:s');

        //Call create method and create new product.
        if($product->create()){

            //Set response code to  201 - Created.
            http_response_code(201);

            //Notify user
            echo json_encode(array("message" => "Product was created."));
        }
        //Not able to create product
        else {

            //Set response code to  503 - Service unavailable.
            http_response_code(503);

            //Notify user
            echo json_encode(array("message"=>"Unable to create product."));

        }

    }
    else {

        //Set response code to 400 - Bad request.
        http_response_code(400);

        //Notify user
        echo json_encode(array("message"=>"Unable to create product. Data is incomplete."));
    }






?>