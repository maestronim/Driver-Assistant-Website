<?php
class UserPath{
 
    // database connection and table name
    private $conn;
    private $table_name = "paths";
 
    // object properties
    public $path_date;
    public $user_id;
    public $hard_braking;
    public $speed_limit_exceeded;
    public $dangerous_time;
    public $duration;
    public $url;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create score
    function create() {
        // query to insert record
    	$query = "INSERT INTO " . $this->table_name . " 
                  SET path_date = :path_date,
                      user_id = :user_id,
                      url = :url";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->path_date=htmlspecialchars(strip_tags($this->path_date));
        $this->url=htmlspecialchars(strip_tags($this->url));

        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":path_date" , $this->path_date);

        // execute query
        if($stmt->execute()){
          	return true;
        }

        return false;
    }
    
    function update() {
    	// query to insert record
    	$query = "UPDATE " . $this->table_name . " 
        		SET hard_braking = :hard_braking,
                    speed_limit_exceeded = :speed_limit_exceeded,
                    dangerous_time = :dangerous_time,
                    duration = :duration
               	WHERE user_id = :user_id AND path_date = :path_date";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->path_date=htmlspecialchars(strip_tags($this->path_date));
        $this->speed_limit_exceeded=htmlspecialchars(strip_tags($this->speed_limit_exceeded));
        $this->hard_braking=htmlspecialchars(strip_tags($this->hard_braking));
        $this->dangerous_time=htmlspecialchars(strip_tags($this->dangerous_time));
        $this->duration=htmlspecialchars(strip_tags($this->duration));
        
        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":path_date", $this->path_date);
		$stmt->bindParam(":hard_braking", $this->hard_braking);
        $stmt->bindParam(":speed_limit_exceeded", $this->speed_limit_exceeded);
        $stmt->bindParam(":dangerous_time", $this->dangerous_time);
        $stmt->bindParam(":duration", $this->duration);
        
        // execute query
        if($stmt->execute()){
        	if($stmt->rowCount() > 0) {
          		return true;
            }
        }
		print_r($stmt->errorInfo());
        return false;
    }
    
    function getUrl() {
    	// query to read single record
        $query = "SELECT url 
                  FROM   " . $this->table_name . " 
                  WHERE user_id = ? AND path_date = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->path_date=htmlspecialchars(strip_tags($this->path_date));
		
        // bind values
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->path_date);

        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    function read() {
    	$query = "SELECT hard_braking, speed_limit_exceeded, dangerous_time, duration, url FROM " . $this->table_name . "
        		WHERE user_id = ? AND DATE(path_date) = ? LOCK IN SHARE MODE";
                
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->path_date=htmlspecialchars(strip_tags($this->path_date));
		
        // bind values
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->path_date);
        
       	// execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    function getID() {
    	$query = "SELECT id FROM " . $this->table_name . "
        		WHERE user_id = ? AND path_date = ?";
                
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->path_date=htmlspecialchars(strip_tags($this->path_date));
		
        // bind values
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->path_date);
        
       	// execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    function getIDs() {
    	$query = "SELECT id FROM " . $this->table_name . "
        		WHERE user_id = ? AND DATE(path_date) = ?";
                
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->path_date=htmlspecialchars(strip_tags($this->path_date));
		
        // bind values
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->path_date);
        
       	// execute query
        $stmt->execute();
        
        return $stmt;
    }
}
?>