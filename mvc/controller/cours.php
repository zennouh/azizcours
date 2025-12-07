<?php

require_once "modal/cours.php";

function cours_list_action()
{
    // bring modal
    $courses = get_courses_list();
    // bring view
    require_once "view/courses.php";
}
