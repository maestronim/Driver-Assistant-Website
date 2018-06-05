<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/car_parameters.php';
require_once '../token/validate.php';

/*
 * Get all headers from the HTTP request
 */
$headers    = apache_request_headers();
$authHeader = $headers['Authorization'];

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (validate_token($authHeader, $data->user_id)) {
    // get database connection
    $database = new Database();
    $db       = $database->getConnection();

    // instantiate object
    $car_parameters = new CarParameters($db);

    // set user property values
    $car_parameters->absoluteEngineLoad       = $data->absoluteEngineLoad;
    $car_parameters->engineLoad               = $data->engineLoad;
    $car_parameters->massAirFlow              = $data->massAirFlow;
    $car_parameters->oilTemperature           = $data->oilTemperature;
    $car_parameters->RPM                      = $data->RPM;
    $car_parameters->throttlePosition         = $data->throttlePosition;
    $car_parameters->airFuelRatio             = $data->airFuelRatio;
    $car_parameters->consumptionRate          = $data->consumptionRate;
    $car_parameters->fuelLevel                = $data->fuelLevel;
    $car_parameters->fuelTrim                 = $data->fuelTrim;
    $car_parameters->widebandAirFuelRatio     = $data->widebandAirFuelRatio;
    $car_parameters->barometricPressure       = $data->barometricPressure;
    $car_parameters->fuelPressure             = $data->fuelPressure;
    $car_parameters->fuelRailPressure         = $data->fuelRailPressure;
    $car_parameters->intakeManifoldPressure   = $data->intakeManifoldPressure;
    $car_parameters->airIntakeTemperature     = $data->airIntakeTemperature;
    $car_parameters->ambientAirTemperature    = $data->ambientAirTemperature;
    $car_parameters->engineCoolantTemperature = $data->engineCoolantTemperature;
    $car_parameters->path_id                  = $data->path_id;

    if ($car_parameters->create()) {
        echo '{';
        echo '"success": "yes",';
        echo '"message": "Parameters were created."';
        echo '}';
    }

    // if unable to create parameters
    else {
        echo '{';
        echo '"success": "no",';
        echo '"message": "Unable to create parameters."';
        echo '}';
    }
} else {
    echo '{';
    echo '"success": "no",';
    echo '"message": "Access denied"';
    echo '}';
}
?>
