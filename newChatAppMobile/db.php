<?php

function dbConnect(){
    // Connecting to databases with PHP we will be using the PDO class.
    $db_uid = "root";       //The username of the mysql database
    $db_pwd = "root";   //The password for the mysql user
    $db_conn_string = "mysql:host=localhost;dbname=assignmentfive;charset=utf8";
    // NOTE: By default, the WAMP mysql root user has no p  assword,
    // you need to set one in the user privileges area of MysqlWorkbench;
    // PDO - PHP Data Object
    $dbConnection = new PDO($db_conn_string, $db_uid, $db_pwd);
    // Shows you when you have an error in your SQL
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //returns the connection to the database
    return $dbConnection;
}

?>