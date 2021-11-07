<?php

include_once 'session.php';

$content = '<h1>Hello ' . $_SESSION["username"] . ', welcome to Taylor’s Clinic</h1>';
$styles = '<link href="./css/index.css" rel="stylesheet">';

include 'template.php';

?>