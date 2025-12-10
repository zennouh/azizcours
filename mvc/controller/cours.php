<?php

require_once "modal/cours.php";
require_once "modal/section.php";

function index()
{
    // bring modal
    $courses = Cours::all();
    // bring view
    require_once "view/courses.php";
}

function detail($id)
{
    $cours = Cours::detail($id);
    $sect = get_section($id);
    require_once "view/details.php";
}

function delete($id)
{
    $result =  Cours::delete($id, "");
    if ($result) {
        header("location: index.php");
    }
}

function addAction()
{
    require_once "view/add.php";
}

function add($data)
{
    $res = Cours::create($data);
    if ($res) {
        header("location: index.php");
    }
}


function editAction($id)
{
    $c = Cours::detail($id);
    require_once "view/edit.php";
}

function update($id)
{
    $result =  Cours::update($id);
    if ($result) {
        header("location: index.php");
    }
}
