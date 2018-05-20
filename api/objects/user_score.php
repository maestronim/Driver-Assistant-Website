<?php
class UserScore{
 
    // database connection and table name
    private $conn;
    private $table_name = "scores";
 
    // object properties
    public $hard_braking;
    public $speed_limit_exceeded;
    public $dangerous_time;
    public $user_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create score
    function create() {
        // query to insert record
    	$query = "INSERT INTO " . $this->table_name . " 
                  SET hard_braking = :hard_braking, 
                      speed_limit_exceeded = :speed_limit_exceeded, 
                      dangerous_time = :dangerous_time, 
                      user_id = :user_id, 
                      score_date = Now() ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->hard_braking=htmlspecialchars(strip_tags($this->hard_braking));
        $this->speed_limit_exceeded=htmlspecialchars(strip_tags($this->speed_limit_exceeded));
        $this->dangerous_time=htmlspecialchars(strip_tags($this->dangerous_time));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":hard_braking", $this->hard_braking);
        $stmt->bindParam(":speed_limit_exceeded", $this->speed_limit_exceeded);
        $stmt->bindParam(":dangerous_time", $this->dangerous_time);
        $stmt->bindParam(":user_id", $this->user_id);

        // execute query
        if($stmt->execute()){
          return true;
        }

        return false;
    }
    
    function read() {
    	// query to read single record
        $query = "SELECT Sum(hard_braking)         AS hard_braking, 
                         Sum(speed_limit_exceeded) AS speed_limit_exceeded, 
                         Sum(dangerous_time)       AS dangerous_time 
                  FROM   " . $this->table_name . " 
                         INNER JOIN users 
                                 ON users.username = scores.user_id 
                  WHERE  scores.user_id = ? ";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->user_id);

        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}
?>