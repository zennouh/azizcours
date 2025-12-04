<?php

include_once "connect.php";


if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $conn = makeConnection();
    $q = 'DELETE FROM sections WHERE section_id =' . $id;
    $result = mysqli_query($conn, $q);
    if ($result) {
        mysqli_close($conn);
        sleep(1);
        header("Location: courses_list.php");
    }
} else {
    echo "error";
    header("Location: courses_list.php");
}
