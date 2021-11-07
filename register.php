<?php
include_once 'config.php';
include_once 'session.php';
$fullName = $gender = $address = $dob = $email = $age = $pn = '';
$errors = ['fullName' => '', 'gender' => '', 'address' => '', 'dob' => '', 'email' => '', 'age' => '', 'pn' => ''];

if ($_SERVER["REQUEST_METHOD"]  == "POST" && isset($_POST['submit'])) {
    // FullName
    if (empty($_POST['fullName'])) {
        $errors['fullName'] = 'Full Name is required. Please ensure the full name is filled out. </br>';
    } else {
        $fullName = $_POST['fullName'];
    }

    // Gender
    if (empty($_POST['gender'])) {
        $errors['gender'] = 'Gender is required. Please ensure the gender is filled out. </br>';
    } else {
        $gender = $_POST['gender'];
    }

    // Address
    if (empty($_POST['address'])) {
        $errors['address'] = 'Address is required. Please ensure the address is filled out. </br>';
    } else {
        $address = $_POST['address'];
    }

    // DOB
    if (empty($_POST['dob'])) {
        $errors['dob'] = 'Date of Birth is required. Please ensure the date of birth is filled out. </br>';
    } else {
        $dob = $_POST['dob'];
    }

    // Email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required. Please ensure the email is filled out. </br>';
    } else {
        $email = $_POST['email'];

        if (!filter_var($email, FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => '/[-0-9a-zA-Z.+_]+@(sd)?\.taylors\.edu\.my/'
            ]
        ])) {
            $errors['email'] = '<ul>
                <li>must contain <strong>@</strong>symbol</li>
                <li>must have <strong>taylors.edu.my or sd.taylors.edu.my</strong> as domain only.</li>
            </ul>';
        }
    }

    // Age
    if (empty($_POST['age'])) {
        $errors['age'] = 'Age is required. Please ensure the age is filled out. </br>';
    } else {
        $age = $_POST['age'];

        if ($age < 16 || $age > 80) {
            $errors['age'] = '<ul>
                <li>must be <strong>between 16 and 80</strong></li>
            </ul>';
        }
    }

    // Phone Number
    if (empty($_POST['pn'])) {
        $errors['pn'] = 'Phone number is required. Please ensure the phone number is filled out. </br>';
    } else {
        $pn = $_POST['pn'];

        if (!filter_var($pn, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/\+601[0-46-9]-*[0-9]{7,8}$/']])) {
            $errors['pn'] = '<ul>
                <li>must <strong>start with +60</strong> and <strong>followed by 9 or 10 digits</strong></li>
            </ul>';
        }
    }

    if (!array_filter($errors)) {
        // create sql
        $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $pn = mysqli_real_escape_string($conn, $_POST['pn']);

        // create sql
        $sql = "INSERT INTO patients(fullName, gender, address, dob, email, age, pn) VALUES ('$fullName','$gender','$address','$dob','$email','$age','$pn')";

        // execute sql
        $results = mysqli_query($conn, $sql);

        // check whether the saving is successful
        if ($results) {
            // query to new patient
            $sql = 'SELECT id, fullName, gender, address, dob, email, age, pn FROM patients WHERE id = (SELECT LAST_INSERT_ID())';

            // execute the query to retrieve data
            $results = mysqli_query($conn, $sql);

            if (mysqli_num_rows($results) > 0) {
                // fetch data of each row
                while ($row = mysqli_fetch_array($results)) {
                    // display the data of each row
                    $registeredPatient = 'Patient ID: ' . $row['id'] . '<br>' .
                        $row['fullName'] . '<br>' .
                        $row['gender'] . '<br>' .
                        $row['address'] . '<br>' .
                        $row['dob'] . '<br>' .
                        $row['email'] . '<br>' .
                        $row['age'] . '<br>' .
                        $row['pn'];
                }
            }
        } else {
            echo 'Data is NOT stored into the database';
        }
    }
}

$content = !empty($registeredPatient) ? $registeredPatient : '<form action="register.php" method="POST">
    <h2>Register</h2>
    <p>Please fill this form to create an account.</p>
    <table>
        <tr>
            <td> Full Name:</td>
            <td>
                <input type="text" name="fullName" value="' . ($_POST['fullName'] ?? '') . '">
                <div>' . $errors['fullName'] . '</div>
            </td>
        </tr>
        <tr>
            <td>Gender:</td>
            <td>
                <input type="radio" name="gender" value="Male" ' . (($_POST['gender'] ?? '') === 'Male' ? 'checked=checked' : '') . '">Male
                <input type="radio" name="gender" value="Female" ' . (($_POST['gender'] ?? '') === 'Female' ? 'checked=checked' : '') . '">Female
                <div>' . $errors['gender'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Address:</td>
            <td>
                <input type="text" name="address" value="' . ($_POST['address'] ?? '') . '">
                <div>' . $errors['address'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Date of Birth:</td>
            <td>
                <input type="date" name="dob" value="' . ($_POST['dob'] ?? '') . '">
                <div>' . $errors['dob'] . '</div>
            </td>
        </tr>
        
        <tr>
            <td> Email:</td>
            <td>
                <input type="text" name="email" value="' . ($_POST['email'] ?? '') . '">
                <div>' . $errors['email'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Age:</td>
            <td>
                <input type="text" name="age" value="' . ($_POST['age'] ?? '') . '">
                <div>' . $errors['age'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Phone Number:</td>
            <td>
                <input type="text" name="pn" value="' . ($_POST['pn'] ?? '') . '">
                <div>' . $errors['pn'] . '</div>
            </td>
        </tr>
    </table>
    <input type="submit" name="submit" value="Register">
</form>';

include 'template.php';
