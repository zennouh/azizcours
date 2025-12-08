<?php

function make_connection()
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

function get_courses_list()
{
    $conn = make_connection();

    $sql = <<<EOD
    SELECT COALESCE(MAX(s.position), 0)  max_position, 
       c.cours_id,
       c.title, 
       c.levele, 
       c.image, 
       c.description
FROM cours c
LEFT JOIN sections s
ON c.cours_id = s.cours_id
GROUP BY c.cours_id, c.title, c.levele, c.image, c.description
EOD;
    $result = mysqli_query($conn, $sql);

    $courses = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = [
                'cours_id' => $row['cours_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'level' => $row['levele'],
                'position' => $row['max_position'],
                'image' => "http://localhost/azizcours/src/upload/" . $row['image']
            ];
        }
    }
    mysqli_close($conn);
    return $courses;
}

function delete_cours($id): bool
{

    $conn = make_connection();
    $q = 'DELETE FROM cours WHERE cours_id =' . $id;
    $result = mysqli_query($conn, $q);
    if ($result) {
        mysqli_close($conn);
        sleep(1);
        return true;
    } else {
        return false;
    }
}

function get_cours_detail($id)
{
    $conn = make_connection();
    $id = intval($id);

    // We get the max position of sections directly here
    $sql = "SELECT c.cours_id, c.title, c.description, c.levele, c.image,
                   COALESCE(MAX(s.position),0) AS max_position
            FROM cours c
            LEFT JOIN sections s ON c.cours_id = s.cours_id
            WHERE c.cours_id = $id
            GROUP BY c.cours_id, c.title, c.description, c.levele, c.image";

    $result = mysqli_query($conn, $sql);

    $courses = [];

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $courses[] = [
            'cours_id'   => $row['cours_id'],
            'title'      => $row['title'],
            'description' => $row['description'],
            'level'      => $row['levele'],
            'max_position' => $row['max_position'],
            'image'      => "http://localhost/azizcours/src/upload/" . $row['image']
        ];
    }

    mysqli_close($conn);
    return $courses;
}
function get_section($id)
{
    $conn = make_connection();
    $id = intval($id);

    $sql = "SELECT section_title, section_content, section_id,position 
            FROM sections 
            WHERE cours_id = $id
            ORDER BY position ASC";

    $result = mysqli_query($conn, $sql);
    $sections = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sections[] = [
                "sec_title"    => $row["section_title"],
                "sec_dsc"      => $row["section_content"],
                "sec_position" => $row["position"],
                "sec_id" => $row["section_id"],
            ];
        }
    }

    mysqli_close($conn);
    return $sections;
}


function add_cours()
{
    try {
        $conn = make_connection();

        $file = $_FILES["image"];
        $title = $_POST["title"];
        $desc  = $_POST["description"];
        $level = $_POST["level"];
        $imageName = time() . "_" . basename($file["name"]);

        $query = "INSERT INTO `cours` (`title`, `levele`, `image`, `description`) VALUES ('$title', '$level', '$imageName', '$desc')";

        $result = mysqli_query($conn, $query);

        if ($result) {

            $targetDir = "../src/upload/";
            $targetPath = $targetDir . $imageName;

            $isMove = move_uploaded_file($file["tmp_name"], $targetPath);
            mysqli_close($conn);
            return true;
        }
        return false;
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;
    }
}
