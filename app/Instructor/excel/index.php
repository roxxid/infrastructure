<?php

$belowRoot = true;
$isLoggedIn = true;
$isTeacher = true;
$isStudent = false;
$displayClass=true; //display the class name after the prof selects section from dropdown
$showNav = true; //don't display navigation if teacher hasn't selected class from drowpdown

$thisPage = "Excel";

include '../../header.php';
?>


<?php 
include 'excel.php';

include '../../footer.php';

?>


