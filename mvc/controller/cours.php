<?php

require_once "modal/cours.php";
require_once "modal/section.php";

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

function addAction()
{
    require_once "view/add.php";
}

function add()
{
    $res = add_cours();
    if ($res) {
        header("location: index.php");
    }
}


function editAction($id)
{
    $c = get_cours_detail($id)[0];
    require_once "view/edit.php";
}

function update()
{
    $result =  update_cours();
    if ($result) {
        header("location: index.php");
    }
}
