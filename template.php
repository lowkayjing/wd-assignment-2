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
            <!--                <li><a href="login.php">Login</a></li>-->
            <li><a href="searchUpdateDelete.php">Search</a></li>
        </ul>
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
</body>

</html>