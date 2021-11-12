<?php
include_once './lib/config.php';

if (isset($_POST['id'])) {
    $conn->query('DELETE FROM patients WHERE id = ' . $_POST['id']);

    header('Location: index.php');
}