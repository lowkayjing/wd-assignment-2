<?php

include_once './lib/session.php';
include_once './lib/config.php';

$shouldDisplayResults = isset($_GET['search']) || !empty($_GET['id']);

// Pagination
$size = $_GET["size"] ?? ($_COOKIE["index:size"] ?? 10);
$page = $_GET["page"] ?? 1;
$offset = ($page - 1) * $size;
$pageCount = 1;

// Retain page size in cookie
setcookie('index:size', $size, 2147483647);

if ($shouldDisplayResults) {
    $sql = "SELECT id, ic, fullName, gender, address, dob, email, age, phoneNumber FROM patients " .
        (!empty($_GET['id']) ? "WHERE id = ?" :
            (empty($_GET['search']) ? "" : "WHERE id LIKE ? OR fullName LIKE ? OR gender LIKE ? OR address LIKE ? 
            OR dob LIKE ? OR email LIKE ? OR age LIKE ? OR phoneNumber LIKE ?"));

    $countSql = "SELECT COUNT(*) AS count FROM ($sql) sub";
    $sql .= " LIMIT $size OFFSET $offset";

    $countStmt = $conn->prepare($countSql);
    $stmt = $conn->prepare($sql);

    if (!empty($_GET['id'])) {
        $stmt->bind_param('i', $_GET['id']);
    } else if (!empty($_GET['search'])) {
        $stmt->bind_param('ssssssss', ...array_fill(0, 8, '%' . $_GET['search'] . '%'));
        $countStmt->bind_param('ssssssss', ...array_fill(0, 8, '%' . $_GET['search'] . '%'));
    }

    $countStmt->execute();
    $countStmt->bind_result($rowCount);
    $countStmt->fetch();
    $countStmt->close();

    $pageCount = ($rowCount % $size == 0) ? $rowCount / $size : ceil($rowCount / $size);

    $stmt->execute();
    $stmt->bind_result($id, $ic, $fullName, $gender, $address, $dob, $email, $age, $phoneNumber);

    while ($stmt->fetch()) {
        $patients[] = "<tr><td>$id</td>
        <td>$ic</td>
        <td>$fullName</td>
        <td>$gender</td>
        <td>$address</td>
        <td>$dob</td>
        <td>$email</td>
        <td>$age</td>
        <td>$phoneNumber</td>
        <td>
            <div class='buttons'>
                <a href='edit.php?id=$id' class='button'>Edit</a>
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
}

$content = '<h1>Hello ' . $_SESSION["username"] . ', welcome to Taylor’s Clinic</h1>' .
    ($shouldDisplayResults ? '<section>
<div class="index-heading">
    <h2>Patients (' . count($patients ?? []) . ')</h2>
    <div class="pagination">
        <div>
            <label>Size</label>
            <select name="size">
                ' . (join('', array_map(function ($o) use ($size) {
            return "<option value='$o' " . ($o == $size ? 'selected="selected"' : '') . ">$o</option>";
        }, [5, 10, 25, 50, 100]))) . '
            </select>
        </div>
        <div>
            <label>Page</label>
            ' . ($pageCount > 1 ? "<span data-page='1'>&laquo;</span>" : '') . '
            <select name="page">
                ' . (join('', array_map(function ($o) use ($pageCount, $page) {
            return "<option value='$o' " . ($o == $page ? 'selected="selected"' : '') . ">$o</option>";
        }, range(1, $pageCount ?: 1)))) . '
            </select>
            ' . ($pageCount > 1 ? "<span data-page='$pageCount'>&raquo;</span>" : '') . '
        </div>
    </div>
</div>
<table class="patients">
    <thead>
        <tr>
            <th>ID</th>
            <th>IC</th>
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
</section>' : '<div class="message">Search for patient or <a href="create.php">add new patient</a></div>');
$styles = '<link href="css/index.css" rel="stylesheet">';
$scripts = '<script src="js/index.js"></script>';

include './lib/template.php';