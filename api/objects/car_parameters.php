<?php
class CarParameters{

  // database connection and table name
  private $conn;
  private $table_name = "car_parameters";

  // object properties
  public $absoluteEngineLoad;
  public $engineLoad;
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
  public $path_id;

  // constructor with $db as database connection
  public function __construct($db){
    $this->conn = $db;
  }

  function get_parameters_list() {
    return array("absoluteEngineLoad",
    "engineLoad",
    "massAirFlow",
    "oilTemperature",
    "RPM",
    "throttlePosition",
    "airFuelRatio",
    "consumptionRate",
    "fuelLevel",
    "fuelTrim",
    "widebandAirFuelRatio",
    "barometricPressure",
    "fuelPressure",
    "fuelRailPressure",
    "intakeManifoldPressure",
    "airIntakeTemperature",
    "ambientAirTemperature",
    "engineCoolantTemperature");
  }

  // create user
  function create(){
    // query to insert record
    $query = "INSERT INTO " . $this->table_name . "
    SET absoluteEngineLoad = :absoluteEngineLoad,
    engineLoad = :engineLoad,
    massAirFlow = :massAirFlow,
    oilTemperature = :oilTemperature,
    RPM = :RPM,
    throttlePosition = :throttlePosition,
    airFuelRatio = :airFuelRatio,
    consumptionRate = :consumptionRate,
    fuelLevel = :fuelLevel,
    fuelTrim = :fuelTrim,
    widebandAirFuelRatio = :widebandAirFuelRatio,
    barometricPressure = :barometricPressure,
    fuelPressure = :fuelPressure,
    fuelRailPressure = :fuelRailPressure,
    intakeManifoldPressure = :intakeManifoldPressure,
    airIntakeTemperature = :airIntakeTemperature,
    ambientAirTemperature = :ambientAirTemperature,
    engineCoolantTemperature = :engineCoolantTemperature,
    path_id = :path_id";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->absoluteEngineLoad=htmlspecialchars(strip_tags($this->absoluteEngineLoad));
    $this->engineLoad=htmlspecialchars(strip_tags($this->engineLoad));
    $this->massAirFlow=htmlspecialchars(strip_tags($this->massAirFlow));
    $this->oilTemperature=htmlspecialchars(strip_tags($this->oilTemperature));
    $this->RPM=htmlspecialchars(strip_tags($this->RPM));
    $this->throttlePosition=htmlspecialchars(strip_tags($this->throttlePosition));
    $this->airFuelRatio=htmlspecialchars(strip_tags($this->airFuelRatio));
    $this->consumptionRate=htmlspecialchars(strip_tags($this->consumptionRate));
    $this->fuelLevel=htmlspecialchars(strip_tags($this->fuelLevel));
    $this->fuelTrim=htmlspecialchars(strip_tags($this->fuelTrim));
    $this->widebandAirFuelRatio=htmlspecialchars(strip_tags($this->widebandAirFuelRatio));
    $this->barometricPressure=htmlspecialchars(strip_tags($this->barometricPressure));
    $this->fuelPressure=htmlspecialchars(strip_tags($this->fuelPressure));
    $this->fuelRailPressure=htmlspecialchars(strip_tags($this->fuelRailPressure));
    $this->intakeManifoldPressure=htmlspecialchars(strip_tags($this->intakeManifoldPressure));
    $this->airIntakeTemperature=htmlspecialchars(strip_tags($this->airIntakeTemperature));
    $this->ambientAirTemperature=htmlspecialchars(strip_tags($this->ambientAirTemperature));
    $this->engineCoolantTemperature=htmlspecialchars(strip_tags($this->engineCoolantTemperature));
    $this->path_id=htmlspecialchars(strip_tags($this->path_id));

    // bind values
    $stmt->bindParam(":absoluteEngineLoad", $this->absoluteEngineLoad);
    $stmt->bindParam(":engineLoad", $this->engineLoad);
    $stmt->bindParam(":massAirFlow", $this->massAirFlow);
    $stmt->bindParam(":oilTemperature", $this->oilTemperature);
    $stmt->bindParam(":RPM", $this->RPM);
    $stmt->bindParam(":throttlePosition", $this->throttlePosition);
    $stmt->bindParam(":airFuelRatio", $this->airFuelRatio);
    $stmt->bindParam(":consumptionRate", $this->consumptionRate);
    $stmt->bindParam(":fuelLevel", $this->fuelLevel);
    $stmt->bindParam(":fuelTrim", $this->fuelTrim);
    $stmt->bindParam(":widebandAirFuelRatio", $this->widebandAirFuelRatio);
    $stmt->bindParam(":barometricPressure", $this->barometricPressure);
    $stmt->bindParam(":fuelPressure", $this->fuelPressure);
    $stmt->bindParam(":fuelRailPressure", $this->fuelRailPressure);
    $stmt->bindParam(":intakeManifoldPressure", $this->intakeManifoldPressure);
    $stmt->bindParam(":airIntakeTemperature", $this->airIntakeTemperature);
    $stmt->bindParam(":ambientAirTemperature", $this->ambientAirTemperature);
    $stmt->bindParam(":engineCoolantTemperature", $this->engineCoolantTemperature);
    $stmt->bindParam(":path_id", $this->path_id);

    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  function read() {
    // query to insert record
    $query = "SELECT $this->table_name.* FROM $this->table_name
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
