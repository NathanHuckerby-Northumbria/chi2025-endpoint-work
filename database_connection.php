<?php
// This function returns a connection to the database using PDO
// The function returns the connection object (or exits on error)
function database_connection() {
 
    // Import the credentials. Adding _DIR_ at the start
    // means PHP will look in the same folder as the current script
    require_once __DIR__."/credentials.php";
 
    // A try ... catch block will try something and catch any
    // exception messages if something goes wrong.
    try {
        // Use PDO to create a connection to the MariaDB database
        $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        // return a database connection
        return $connection;
 
    } catch( PDOException $e ) {
        // If there is an exception, return the error message 
        $error = "Database Connection Error: " . $e->getMessage();
        echo json_encode($error);
        
        // Stop execution of the program
        exit();
    }
}