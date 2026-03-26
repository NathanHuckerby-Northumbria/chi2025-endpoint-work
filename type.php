<?php

/**
 * Type endpoint
 * 
 * This endpoint will return information about the presentation types.
 * The endpoint also supports GET, POST, PATCH, PUT and DELETE requests.
 * 
 * @author Nathan Huckerby W24016075
 * @version 2026Assessment1
 */

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');

require_once 'api.php';
require_once 'database/execute_sql.php';

function GET_type() {
    $sql = "SELECT type.id as type_id, type.name 
    FROM type
    WHERE 1=1";

    $data = execute_sql($sql, []);
    echo json_encode($data);
}
/**
 * Creates a new presentation type from the JSON request body
 */

function POST_type() {
    // Reads and decods the request body
    $request_body = file_get_contents('php://input');
    $request_body = json_decode($request_body, true);

    $sql = "INSERT INTO type (name) VALUES (:name)";

    // Validates the request body 
    if ($request_body === null) {
        http_response_code(400);
        echo json_encode("No JSON or JSON is invalid");
        exit();
    }

    // Checks to see if name is present
    if (array_key_exists('name', $request_body)) {
        $name = $request_body['name'] ?? "";
    } else {
        http_response_code(400);
        echo json_encode("Name is required");
        exit();
    }

    // Inserts the new type
    $param = [':name' => $name];
    execute_sql($sql, $param);
    http_response_code(201);
}
/**
 * Updates an existing presentation type from the JSON request body
 */

function PATCH_type() {
    // Reads and decods the request body
    $request_body = file_get_contents('php://input');
    $request_body = json_decode($request_body, true);

    $sql = "UPDATE type SET name = :name WHERE id = :type_id";

    // Validates the request body 
    if ($request_body === null) {
        http_response_code(400);
        echo json_encode("No JSON or JSON is invalid");
        exit();
    }

    // Checks to see if type ID is present
    if (array_key_exists('type_id', $request_body)) {
        $type_id = $request_body['type_id'] ?? "";
    } else {
        http_response_code(400);
        echo json_encode("Type ID is required");
        exit();
    }

    // Checks to see if name is present
    if (array_key_exists('name', $request_body)) {
        $name = $request_body['name'] ?? "";
    } else {
        http_response_code(400);
        echo json_encode("Name is required");
        exit();
    }

    $param = [':name' => $name, ':type_id' => $type_id];
    execute_sql($sql, $param);
    http_response_code(200);
}

/**
 * Deletes an existing presentation type from the JSON request body
 */

function DELETE_type() {
    // Reads and decods the request body
    $request_body = file_get_contents('php://input');
    $request_body = json_decode($request_body, true);

    $sql = "DELETE FROM type WHERE id = :type_id";

    // Validates the request body 
    if ($request_body === null) {
        http_response_code(400);
        echo json_encode("No JSON or JSON is invalid");
        exit();
    }

    // Checks to see if type ID is present
    if (array_key_exists('type_id', $request_body)) {
        $type_id = $request_body['type_id'] ?? "";
    } else {
        http_response_code(400);
        echo json_encode("Type ID is required");
        exit();
    }

    $param = [':type_id' => $type_id];
    execute_sql($sql, $param);
    http_response_code(200);
}

// Determines the HTTP request method and calls the correct function
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        GET_type();
        break;
    case 'POST':
        POST_type();
        break;
    case 'PATCH':
    case 'PUT':
        // PUT & PATC have similar functionality, so they will use the same function
        PATCH_type();
        break;
    case 'DELETE':
        DELETE_type();
        break;
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        // Returns http response code 406 for any unsupported methods
        http_response_code(405);
        echo json_encode("Method not allowed");
        break;
}
