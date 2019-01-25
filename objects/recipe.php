<?php
class Recipes{

    // database connection and table name
    private $conn;
    private $table_name = "recipes";

    // object properties
    public $id;
    public $name;
    public $image_path;
    public $description;
    public $modified;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){

        // select all query
        $query = "  SELECT *
                    FROM
                    " . $this->table_name . " p
                    ORDER BY
                    p.modified DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }


    // create product
    function create(){

        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, image_path=:image_path, description=:description, modified=:modified";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->image_path));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->created=htmlspecialchars(strip_tags($this->modified));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image_path", $this->image_path);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":modified", $this->modified);


        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }


    // used when filling up the update product form
    function readOne(){

        // query to read single record
        $query = "  SELECT *
                    FROM
                    " . $this->table_name . " p
                    WHERE p.id = ?
                    LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['name'];
        $this->image_path = $row['image_path'];
        $this->description = $row['description'];
        $this->modified = $row['modified'];

    }


    // update the product
    function update(){

        // update query
        $query = "UPDATE
                   " . $this->table_name . " p
                SET
                    p.name = :name,
                    p.image_path = :image_path,
                    p.description = :description,
                    p.modified = :modified
                WHERE p.id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->image_path=htmlspecialchars(strip_tags($this->image_path));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->modified=htmlspecialchars(strip_tags($this->modified));


        // bind new values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':image_path', $this->image_path);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':modified', $this->modified);


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;


    }



    // update the product
    function delete(){

        // delete query
        $query = "  DELETE
                    FROM " . $this->table_name .
            " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;


    }


    // search products
    function search($keywords){

        // select all query
        $query = "SELECT
                     p.id, p.name, p.description, p.image_path,p.modified
                FROM
                    " . $this->table_name . " p
                WHERE
                    p.name LIKE ? OR p.description LIKE ? OR p.name LIKE ?
                ORDER BY
                    p.modified DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // execute query
        $stmt->execute();

        return $stmt;
    }


    // read products with pagination
    public function readPaging($from_record_num, $records_per_page){

        // select query
        $query = "SELECT *
                FROM
                    " . $this->table_name . " p
                ORDER BY p.modified DESC
                LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }


    // used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

}

?>