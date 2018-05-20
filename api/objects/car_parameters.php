<?php
class CarParameters{
 
    // database connection and table name
    private $conn;
    private $table_name = "car_parameters";
 
    // object properties
    public $absoluteLoad;
    public $load;
    public $massAirFlow;
    public $oilTemperature;
    public $RPM;
    public $throttlePosition;
    public $airFuelRatio;
    public $consumptionRate;
    public $fuelLevel;
    public $fuelTrim;
    public $wideBandAirFuelRatio;
    public $barometricPressure;
    public $fuelPressure;
    public $fuelRailPressure;
    public $intakeManifoldPressure;
    public $airIntakeTemperature;
	public $ambientAirTemperature;
    public $engineCoolantTemperature;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create user
	function create(){
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " 
        			SET oilTemperature = :oilTemperature,
                    	RPM = :RPM,
                        throttlePosition = :throttlePosition,
                        airFuelRatio = :airFuelRatio,
                        path_id = :path_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->oilTemperature=htmlspecialchars(strip_tags($this->oilTemperature));
        $this->RPM=htmlspecialchars(strip_tags($this->RPM));
        $this->throttlePosition=htmlspecialchars(strip_tags($this->throttlePosition));
        $this->airFuelRatio=htmlspecialchars(strip_tags($this->airFuelRatio));
        $this->path_id=htmlspecialchars(strip_tags($this->path_id));

        // bind values
        $stmt->bindParam(":oilTemperature", $this->oilTemperature);
        $stmt->bindParam(":RPM", $this->RPM);
        $stmt->bindParam(":throttlePosition", $this->throttlePosition);
        $stmt->bindParam(":airFuelRatio", $this->airFuelRatio);
        $stmt->bindParam(":path_id", $this->path_id);

        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
	}
    
    function read() {
    	// query to insert record
        $query = "SELECT oilTemperature, RPM, throttlePosition, airFuelRatio FROM " . $this->table_name . " 
        			WHERE path_id = :path_id";

        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->path_id=htmlspecialchars(strip_tags($this->path_id));
        
        // bind values
        $stmt->bindParam(":path_id", $this->path_id);
        
        $stmt->execute();
        
        return $stmt;
    }
}