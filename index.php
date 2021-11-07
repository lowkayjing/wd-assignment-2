<?php

include_once './lib/session.php';
include_once './lib/config.php';

if (empty($_POST['search']) && !empty($_SESSION['search'])) {
    $_POST['search'] = $_SESSION['search'];
}

if (!empty($_POST['search'])) {
    $sql = "SELECT id, fullName, gender, address, dob, email, age, pn FROM patients
            WHERE id LIKE ? OR fullName LIKE ? OR gender LIKE ? OR address LIKE ? 
            OR dob LIKE ? OR email LIKE ? OR age LIKE ? OR pn LIKE ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', ...array_fill(0, 8, '%' . $_POST['search'] . '%'));
    $stmt->execute();

    $stmt->bind_result($id, $fullName, $gender, $address, $dob, $email, $age, $pn);

    while ($stmt->fetch()) {
        $patients[] = "<tr><td>$id</td>
        <td>$fullName</td>
        <td>$gender</td>
        <td>$address</td>
        <td>$dob</td>
        <td>$email</td>
        <td>$age</td>
        <td>$pn</td>
        <td>
            <div class='buttons'>
                <a href='edit.php' class='button'>Edit</a>
                <form action='delete.php' method='post'>
                    <input type='hidden' name='id' value='" . $id . "'/>
                    <a class='button delete'>Delete</a>
                </form>
            </div>
            <div class='confirm d-none'>
                Confirm?
                <span class='yes'>Yes</span>
                <span class='no'>No</span>
            </div>
        </td>
        </tr>";
    }

    $conn->close();

    $_SESSION['search'] = $_POST['search'];
}

$content = '<h1>Hello ' . $_SESSION["username"] . ', welcome to Taylor’s Clinic</h1>' .
    (!empty($_POST['search']) ? '<section>
<h2>Patients (' . count($patients ?? []) . ')</h2>
<table class="patients">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Age</th>
            <th>Phone Number</th>
            <th></th>
        </tr>
    </thead>
    <tbody>' . join(null, $patients ?? []) . '</tbody>
</table>
</section>' : '<div class="message">Search for patient or <a href="register.php">add new patient</a></div>');
$styles = '<link href="css/index.css" rel="stylesheet">';
$scripts = '<script src="js/index.js"></script>';

include './lib/template.php';