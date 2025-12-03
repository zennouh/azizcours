<?php
include_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["cours_id"];
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $level = $_POST["level"];
    $conn = makeConnection();


    if (!empty($_FILES["image"]["name"])) {
        $imgName = time() . "_" . $_FILES["image"]["name"];
        $path = "src/upload/" . $imgName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $path);

        $sql = "UPDATE cours SET title='$title', description='$desc', levele='$level', image='$imgName' WHERE cours_id=$id";
    } else {
        $sql = "UPDATE cours SET title='$title', description='$desc', levele='$level' WHERE cours_id=$id";
    }

    mysqli_query($conn, $sql);
    mysqli_close($conn);
}

header("Location: courses_list.php");
exit;
