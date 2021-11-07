<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management System</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <?= $styles ?? '' ?>
</head>

<body>
<div id="wrapper">

    <nav id="navigation">
        <ul id="nav">
            <li><a href="index.php">Welcome</a></li>
            <?= isset($_SESSION["username"]) ? '<li><a href="create.php">New Patient</a></li>' : '' ?>
        </ul>
        <?= isset($_SESSION["username"]) ?
            '<form action="index.php" method="post">
                <input type="text" name="search" placeholder="Search Patient" value="' . ($_POST['search'] ?? '') . '" />
                <button type="submit">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                    </svg>
                </input>
            </form>' : '' ?>
    </nav>

    <div id="content-area">
        <?= $content ?? ''; ?>
    </div>

    <footer>
        <p>All rights reserved &copy;
            <?= date('Y') ?>
        </p>
    </footer>
</div>

<?= $scripts ?? '' ?>
</body>

</html>