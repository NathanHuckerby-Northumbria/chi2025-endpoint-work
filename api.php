<?php

/**
 * API Authentication
 * 
 * Validates the bearer token upon each request.
 * All endpoints will use this.
 * 
 * @author Nathan Huckerby W24016075
 * @version 2026Assessment1
 */

// set response headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
header('Content-Type: application/json');
 
// get the request headers
$allHeaders = getallheaders();
 
// convert header keys to lowercase for case-insensitive access
$allHeaders = array_change_key_case($allHeaders, CASE_LOWER);
 
// check for the presence of the Authorization header
if (array_key_exists('authorization', $allHeaders)) {
    $authorizationHeader = $allHeaders['authorization'];
} else {
    http_response_code(401);
    exit("Authorization Header Not Found");
}
 
// extract the API key from the Authorization header
$api_key = str_replace('Bearer ', '', $authorizationHeader);
 
if ($api_key !== 'w24016075') {
    http_response_code(401);
    exit("Invalid API Key");
}