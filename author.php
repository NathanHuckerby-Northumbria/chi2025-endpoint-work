<?php

/**
 * Author endpoint
 * 
 * This endpoint will return information about the author such as the id and name.
 * The endpoint also supports optional parameters: author id and presentation id. 
 * @author Nathan Huckerby W24016075
 * @version 2026Assessment1
 */

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once 'api.php';
require_once 'database/execute_sql.php';

$sql = "SELECT author.id as author_id, author.name FROM author WHERE 1=1";

// Reads the optional parameters
$author_id = $_GET['author-id'] ?? "";
$presentation_id = $_GET['presentation-id'] ?? "";
$page = $_GET['page'] ?? "";
$size = $_GET['size'] ?? "";

$param = [];

// Filters by author ID if one is provided
if (!empty($author_id)) {

    if (!is_numeric($author_id)) {
        http_response_code(400);
        echo json_encode("Author ID parameter must be an integer");
        exit();
    }

    $sql .= " AND author.id = :author_id";
    $param[':author_id'] = $author_id;
}

// Filters by presentation ID if one is provided
if (!empty($presentation_id)) {

    if (!is_numeric($presentation_id)) {

    http_response_code(400);
    echo json_encode("Presentation ID parameter must be an integer");
    exit();
    }

    $sql .= " AND author.id IN (SELECT author_id FROM presentation_has_author
    WHERE presentation_id = :presentation_id)";
    $param[':presentation_id'] = $presentation_id;
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

    $sql .= " ORDER BY author_id LIMIT $page_size OFFSET $offset";
} elseif (!empty($size) && (int)$size > 0) {
    // If the size is given by no page parameter is used, it will assume the page is 1
    $sql .= " ORDER BY author_id LIMIT $page_size OFFSET 0";
}

$data = execute_sql($sql, $param);
echo json_encode($data);