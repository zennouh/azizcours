<?php

require_once "modal/section.php";

function update($id)
{
    $result = update_section($id);
    if ($result) {
        header("location: index.php");
    }
}
