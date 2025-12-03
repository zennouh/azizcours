<?php

include "connect.php";

try {
    $conn = makeConnection();


    $title = $_POST["sections_title"];
    $desc  = $_POST["sections_content"];
    $position = intval($_POST["sections_position"]) + 1;
    $coursID = $_POST["cours_id"];
    $query = "INSERT INTO `sections` (`cours_id`, `section_title`, `section_content`, `position`) VALUES
     ('$coursID', '$title', '$desc', '$position')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        mysqli_close($conn);
        header('Location: courses_list.php');
    }
} catch (\Throwable $th) {
    echo $th->getMessage();
}
