<?php

class Product{

    //Declaring database connection and table name.
    private $conn;
    private $table_name="products";

    //Properties of a object aka variables that will represent
    //attributes of our table.

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
    public $modified;

    //Overriding default constructor to take database connection as argument.

    public function __construct($db){
        $this->conn=$db;
    }

    //Read all products method
    public function read(){

        //Select all query.
        $query="SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id=c.id 
                ORDER BY p.created DESC";

        //We prepare and execute statement.        
        $stmt=$this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
    //Read one product
    public function read_one(){
        //Query to read single record
        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";
        //Prepare query statement
        $stmt=$this->conn->prepare($query);

        //Bind id of product 
        $stmt->bindParam(1,$this->id);

        //Execute query
        $stmt->execute();

        //Get retrieved row

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];



    }
    //Create single product method
    public function create(){
        
        //Query to insert record
        $query="INSERT INTO ".$this->table_name." (name,price,description,category_id,created) 
                VALUES(:name,:price,:description,:category_id,:created)";
        
        //Prepare query
        $stmt=$this->conn->prepare($query);
        
        //Sanitize data
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->created=htmlspecialchars(strip_tags($this->created));

        //Bind values
        $stmt->bindParam(":name",$this->name);
        $stmt->bindParam(":price",$this->price);
        $stmt->bindParam(":description",$this->description);
        $stmt->bindParam(":category_id",$this->category_id);
        $stmt->bindParam(":created",$this->created);

        //Execute query

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    //Update one product
    public function update(){

        //Query to update single record
        $query="UPDATE ".$this->table_name."
                SET name=:name,price=:price,description=:description,category_id=:category_id,modified=:modified
                WHERE id=:id";

        //Prepare query statement        
        $stmt=$this->conn->prepare($query);

        //Sanitize data
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->modified=htmlspecialchars(strip_tags($this->modified));

        //Binding data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':modified', $this->modified);

        //execute query
        if($stmt->execute()){
            return true;
        }
        return false;

    }

    public function delete(){
        //Query to delete product.
        $query="DELETE FROM ".$this->table_name." WHERE id= ?";

        //Prepare statement.
        $stmt=$this->conn->prepare($query);

        //Sanitize data.
        $this->id=htmlspecialchars(strip_tags($this->id));

        //Bind data of record to delete.
        $stmt->bindParam(1,$this->id);

        //Execute query.
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    //Search products
    public function search($keywords){

        //Query for search
        $query="SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM ".$this->table_name." p LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
                ORDER BY p.created DESC";
        
        //Prepare query statement
        $stmt=$this->conn->prepare($query);
        
        //Sanitize data
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords="%{$keywords}%";

        //Binding data
        $stmt->bindParam(1,$keywords);
        $stmt->bindParam(2,$keywords);
        $stmt->bindParam(3,$keywords);
        
        //Execute query
        $stmt->execute();

        return $stmt;
   

    }
    //Read products with pagination
    public function readPaging($from_record_num,$records_per_page){

        //Select all, but with limit
        $query="SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM ".$this->table_name." p LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created DESC
                LIMIT ?,?";
        //Prepare query
        $stmt=$this->conn->prepare($query);
        
        //Bind data
        $stmt->bindParam(1,$from_record_num,PDO::PARAM_INT);
        $stmt->bindParam(2,$records_per_page,PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }
    //Used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
 
         $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        return $row['total_rows'];
    }    

}


?>