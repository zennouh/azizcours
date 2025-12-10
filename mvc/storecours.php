<?php

require_once "controller/cours.php";

$file = $_FILES["image"];
$title = $_POST["title"];
$desc  = $_POST["description"];
$level = $_POST["level"];


$data = [
    'title' => $title,
    'description' => $desc,
    'level' => $level,
    'image' => $file,
];

add($data);
