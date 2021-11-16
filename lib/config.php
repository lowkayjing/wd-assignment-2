<?php
$servername = '192.168.64.3';
$username = 'kate';
$password = 'Jing@0220';
$database = 'pms';
$port = 3306;

// create
$conn = mysqli_connect($servername, $username, $password, $database, $port);

// check connection
if (!$conn) die("Connection failed: " . mysqli_connect_error());