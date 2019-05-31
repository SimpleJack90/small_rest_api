<?php

class DatabaseConnection{

    //Specifying database credentials
    private $host="localhost";
    private $db_name="api_db";
    private $username="root";
    private $password="";
    public $conn;

    //Defining method for database connection
    public function getConnection(){

        $this->conn=null;

        //Creating new PDO object and returning connection
        try{

            $this->conn=new PDO("mysql:host=".$this->host.";dbname="
                            .$this->db_name,$this->username,$this->password);
            $this->conn->exec("set names utf8");                

        }
        catch(PDOException $exception){
            echo "Connection error: ".$exception->getMessage();
        }

        return $this->conn;
    }
}


?>