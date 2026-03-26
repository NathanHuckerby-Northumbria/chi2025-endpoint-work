<?php
function execute_SQL($sql = "", $param = []) {
    // Import the database connection function. Adding _DIR_ at the start
    // means PHP will look in the same folder as the current script
    require_once __DIR__."/database_connection.php";
    
    try {
        // connect to the database
        $conn = database_connection();
        // prepare and execute the SQL statement, passing in the parameters
        $result = $conn->prepare($sql);
        $result->execute($param); 
        // fetch the results as an associative array and return it
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        return $data;
 
    } catch( PDOException $e ) {
        // If there is an exception, output the error message
        $error = "SQL Error: " . $e->getMessage();
        echo json_encode($error);
        exit();
    }
}