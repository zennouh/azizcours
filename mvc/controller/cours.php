<?php

require_once "modal/cours.php";

function index()
{
    // bring modal
    $courses = get_courses_list();
    // bring view
    require_once "view/courses.php";
}

function detail($id)
{
    $cours = get_cours_detail($id);
    $sect = get_section($id);
    require_once "view/details.php";
}

function delete($id)
{
    $result =  delete_cours($id);
    if ($result) {
        header("location: index.php");
    }
}
