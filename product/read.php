<?php
//Required headers - file can be read by anyone (*) and will return data in JSON format

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Including database and object files

require_once('../config/database.php');

require_once('../objects/product.php');

//We create new database object and product object

$database=new DatabaseConnection();

$db=$database->getConnection();

$product=new Product($db);

//Query products

$stmt=$product->read();
$num=$stmt->rowCount();

//If we have more than 0 records found 

if($num>0){

    //Define array where we gonna place our data
    $products_arr=array();
    $products_arr["records"]=array();

    //We iterate through and fetch data

    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

        //create variable that will hold one row of data and push it to $products_arr.

        $product_item=array(
            "id"=>$row['id'],
            "name"=>$row['name'],
            "description"=>html_entity_decode($row['description']),
            "price"=>$row['price'],
            "category_id"=>$row['category_id'],
            "category_name"=>$row['category_name']

        );
        array_push($products_arr["records"],$product_item);


    }
    //Setting response code to 200 - OK

    http_response_code(200);

    //Show data in JSON format

    echo json_encode($products_arr);
}
else{
    //Setting response code to 404 - Not Found

    http_response_code(404);

    //Tell the user no products were found

    echo json_encode(array("messsage"=>"No products found."));

}


?>