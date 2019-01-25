<?php
class Ingredients{

    // database connection and table name
    private $conn;
    private $table_name = "ingredients";

    // object properties
    public $id;
    public $name;
    public $amount;
    public $recipe_id;

    public function __construct($db){
        $this->conn = $db;
    }

    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

    // used by select drop-down list
    public function read(){

        //select all data
        $query = "SELECT *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

}
?>