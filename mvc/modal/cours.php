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

