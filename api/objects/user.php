<?php

class User {
    // database connection and table name
    private $conn;
    private $table_name = "user";

    // object properties
    public $id;
    public $name;
    public $age;
    public $points;
    public $street;
    public $city;
    public $state;
    public $country;
    public $zip;
    public $date_created;
    
    public $missing_fields = [];
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function isDataComplete($data) {
        if (empty($data->name))
            $this->missing_fields[] = "name";
        if (empty($data->age))
            $this->missing_fields[] = "age";
        if (empty($data->street))
            $this->missing_fields[] = "street";
        if (empty($data->city))
            $this->missing_fields[] = "city";
        if (empty($data->state))
            $this->missing_fields[] = "state";
        if (empty($data->country))
            $this->missing_fields[] = "country";
        if (empty($data->zip))
            $this->missing_fields[] = "zip";
        
        return count($this->missing_fields) == 0;
    }

    public function readUser() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind values
        $stmt->bindParam(1, $this->id);
        
        $stmt->execute();
        
        $row = $stmt->fetchObject();
        
        return $row;
    }
    
    // read users for leaderboard
    public function getLeaderboard() {
        // select all query
        $query = "SELECT
                      id, name, points
                  FROM " . $this->table_name . "
                  ORDER BY points DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        
        // retrieve leaderboard array
        // fetch() is faster than fetchAll()
        $leaderboard = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row as this will make $row['name'] to $name only, etc.
            extract($row);

            $user_item = [
                "id" => $id,
                "name" => $name,
                "points" => $points
            ];

            $leaderboard[] = $user_item;
        }

        return $leaderboard;
    }
    
    public function create() {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, age=:age, street=:street, city=:city, state=:state, country=:country, zip=:zip, date_created=:date_created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->street = htmlspecialchars(strip_tags($this->street));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->zip = htmlspecialchars(strip_tags($this->zip));
        $this->date_created = htmlspecialchars(strip_tags($this->date_created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":street", $this->street);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":state", $this->state);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":zip", $this->zip);
        $stmt->bindParam(":date_created", $this->date_created);

        // execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
    
    public function delete() {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    public function updatePoints($action) {
        // update points query
        $query = "UPDATE " . $this->table_name . " SET points = points";
        $query.= $action == "increment" ? " + 1 " : " - 1 "; // increment or decrement
        $query.= "WHERE id = ?";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // bind values
        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}