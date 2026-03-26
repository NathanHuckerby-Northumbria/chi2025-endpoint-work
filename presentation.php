<?php

/**
 * Presentation endpoint
 * 
 * This endpoint will return information about each presentation, such as title, abstract, video etc.
 * The endpoint also supports optional parameters: presentation id and author id.
 * 
 * @author Nathan Huckerby W24016075
 * @version 2026Assessment1
 */

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PATCH');

require_once 'api.php';
require_once 'database/execute_sql.php';

function GET_presentation(){

    // The base sql query, which joins the type table to get the type name
    $sql = "SELECT presentation.id as presentation_id, presentation.title, presentation.abstract,
    presentation.doi, presentation.video, type.name as type
    FROM presentation
    LEFT JOIN type ON presentation.type_id = type.id
    WHERE 1=1";

    // Reads the optional parameters
    $presentation_id = $_GET['presentation-id'] ?? "";
    $author_id = $_GET['author-id'] ?? "";
    $page = $_GET['page'] ?? "";
    $size = $_GET['size'] ?? "";

    $param = [];

    // Filters by presentation ID if one is provided
    if (!empty($presentation_id)) {

        if (!is_numeric($presentation_id)) {
        http_response_code(400);
        echo json_encode("Presentation ID parameter must be an integer");
        exit();
        }

        $sql .= " AND presentation.id = :presentation_id";
        $param[':presentation_id'] = $presentation_id;
    }

    // Filters by author ID if one is provided
    if (!empty($author_id)) {

        if (!is_numeric($author_id)) {
            http_response_code(400);
            echo json_encode("Author ID parameter must be an integer");
            exit();
        }

        $sql .= " AND presentation.id IN (SELECT presentation_id FROM presentation_has_author
        WHERE author_id = :author_id)";
        $param[':author_id'] = $author_id;
    }

    $page_size = 10;

    // Sets the page size if one is provided
    if (!empty($size) || $size === '0') {

        if (!is_numeric($size)) {
            echo json_encode([]);
            exit();
        }

        if ($size <= 0) {
            echo json_encode([]);
            exit();
        }

        $page_size = (int)$size;

    }

    // Sets the number of pages if one is provided
    if (!empty($page) || $page === '0') {

        if (!is_numeric($page)) {
            echo json_encode([]);
            exit();
        }

        if ($page <= 0) {
            echo json_encode([]);
            exit();
        }

        $offset = ($page -1) * $page_size;

        $sql .= " ORDER BY presentation_id LIMIT $page_size OFFSET $offset";
    } elseif (!empty($size) && (int)$size > 0) {
        // If the size is given by no page parameter is used, it will assume the page is 1
        $sql .= " ORDER BY presentation_id LIMIT $page_size OFFSET 0";
    }

    $data = execute_sql($sql, $param);
    echo json_encode($data);

}

/**
 * Updates the presentation type for a given presentation from the JSON request body
 */

function PATCH_presentation() {
    // Reads and decods the request body
    $request_body = file_get_contents('php://input');
    $request_body = json_decode($request_body, true);

    $sql = "UPDATE presentation SET type_id = :type_id WHERE id = :presentation_id";

    // Validates the request body 
    if ($request_body === null) {
        http_response_code(400);
        echo json_encode("No JSON or JSON is invalid");
        exit();
    }

    // Checks to see if presentation ID is present
    if (array_key_exists('presentation_id', $request_body)) {
        $presentation_id = $request_body['presentation_id'] ?? "";
    } else {
        http_response_code(400);
        echo json_encode("Presentation ID is required");
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

    $param = [':presentation_id' => $presentation_id, ':type_id' => $type_id];
    execute_sql($sql, $param);
    http_response_code(200);
}

// Determines the HTTP request method and calls the correct function
$request_method = $_SERVER['REQUEST_METHOD'];

switch($request_method) {
    case 'GET':
        GET_presentation();
        break;
    case 'PATCH':
        PATCH_presentation();
        break;
    default:
    http_response_code(405);
    echo json_encode("Method not allowed");
    break;
}
