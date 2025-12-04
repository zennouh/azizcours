<?php
include_once "connect.php";

$id = intval($_GET["id"]);

$conn = makeConnection();

$sql = "SELECT section_title, section_content, position FROM sections WHERE cours_id = $id";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        "sec_title" => $row["section_title"],
        "sec_dsc"   => $row["section_content"],
        "sec_position" => $row["position"],
    ];
}

echo json_encode($data);
