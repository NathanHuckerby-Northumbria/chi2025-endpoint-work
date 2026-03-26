<?php
/**
 * About endpoint
 * 
 * This endpoint will return information about the api.
 * It will check to see if there is a bearer token.
 * 
 * @author Nathan Huckerby W24016075
 * @version 2026Assessment1
 */

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once 'api.php';


//Function returns the information about the module & developer
function about() {
    $moduleinfo = [
        "module" => "Software Architecture",
        "developer" => "Nathan Huckerby",
    ];

    echo json_encode($moduleinfo);
    return $moduleinfo;

}

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        about();
        // Returns OK if successful
        http_response_code(200);
        break;
    default:
        http_response_code(404);
        echo json_encode("Method Not Allowed");
        break;
}

