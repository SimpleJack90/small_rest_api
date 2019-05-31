<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

//Home page url
$home_url="http://localhost/small_rest_api/";

//Page given in URL parameter, default page is one
$page=isset($_GET['page'])? $_GET['page'] : 1;

//Set number of records per page
$records_per_page=5;

//Calculate for the query limit clause
$from_record_num=($records_per_page*$page) - $records_per_page;

?>