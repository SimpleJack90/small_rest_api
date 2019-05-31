<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and object files
require_once('../config/core.php');
require_once('../shared/utilities.php');
require_once('../config/database.php');
require_once('../objects/product.php');

//Utilities

$utilities=new Utilities();

// Instantiate database connection and product object
$database = new DatabaseConnection();
$db = $database->getConnection();
 
// Initialize object of product
$product = new Product($db);

//Query products
$stmt=$product->readPaging($from_record_num,$records_per_page);
$num=$stmt->rowCount();

//Check if more than 0 records found
if($num>0){

    //Products array
    $products_arr=array();
    $products_arr['records']=array();
    $products_arr['paging']=array();

    //Iterate  row by row
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
       
        
        //Creating array for each row product
        $product_item=array(
            "id" => $row['id'],
            "name" => $row['name'],
            "description" => html_entity_decode($row['description']),
            "price" => $row['price'],
            "category_id" => $row['category_id'],
            "category_name" => $row['category_name']
        );
 
        array_push($products_arr["records"], $product_item);
    }
        //Include paging
        $total_rows=$product->count();

        
        $page_url="{$home_url}product/read_paging.php?";

        $paging=$utilities->getPaging($page,$total_rows,$records_per_page,$page_url);

        $products_arr['paging']=$paging;
        // set response code - 200 OK
        http_response_code(200);
 
        // make it json format
        echo json_encode($products_arr);

    
}
else{

    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user products does not exist
    echo json_encode(
        array("message" => "No pages found.")
    );
}

?>