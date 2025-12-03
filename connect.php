
<?php

function makeConnection()
{
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $database   = "aziz_cours";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

?>