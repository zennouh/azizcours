<?php

require_once "controller/section.php";

$id = $_POST["cours_id"];
$maxPos = $_POST["sections_position"];

add($id, $maxPos);
