<?php

require_once "modal/section.php";

function update($id)
{
    $result = update_section($id);
    if ($result) {
        header("location: index.php");
    }
}

function add($id, $position)
{
    $result = add_section($id, $position);
    if ($result) {
        header("location: index.php");
    }
}

function delete($id)
{
    $result = delete_section($id);
    if ($result) {
        header("location: index.php");
    }
}
