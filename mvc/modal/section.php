<?php


function make__connection()
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


function get_section($id)
{
    $conn = make__connection();
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


function update_section($id)
{ 
    $title = $_POST["sections_title"];
    $desc = $_POST["sections_content"];

    $conn = make__connection();
    $sql = "UPDATE `sections` SET `section_title`='$title',`section_content`='$desc' WHERE `section_id`='$id'";
    $result = mysqli_query($conn, $sql);
    return $result;

    mysqli_close($conn);
}


function add_section($id, $pos)
{
    try {
        $conn = make__connection();
        $title = $_POST["sections_title"];
        $desc  = $_POST["sections_content"];
        // $position = intval($_POST["sections_position"]) + 1;
        $position = intval($pos) + 1;

        $coursID = $id;
        $query = "INSERT INTO `sections` (`cours_id`, `section_title`, `section_content`, `position`) VALUES
     ('$coursID', '$title', '$desc', '$position')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            mysqli_close($conn);
            return true;
        }
        return false;
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;
    }
}


function delete_section($id)
{

    $conn = make__connection();
    $q = 'DELETE FROM sections WHERE section_id =' . $id;
    $result = mysqli_query($conn, $q);
    if ($result) {
        mysqli_close($conn);
        return true;
    }
    return false;
}
