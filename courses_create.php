<?php

include "connect.php";

try {
    $conn = makeConnection();

    $file = $_FILES["image"];
    $title = $_POST["title"];
    $desc  = $_POST["description"];
    $level = $_POST["level"];
    $imageName = $fileName = time() . "_" . basename($file["name"]);

    $query = "INSERT INTO `cours` (`title`, `levele`, `image`, `description`) VALUES ('$title', '$level', '$imageName', '$desc')";

    $result = mysqli_query($conn, $query);

    if ($result) {

        $targetDir = "src/upload/";
        $targetPath = $targetDir . $imageName;

        $isMove = move_uploaded_file($file["tmp_name"], $targetPath);
        mysqli_close($conn);
        header('Location: add_cours.php');
    }
} catch (\Throwable $th) {
    echo $th->getMessage();
}
