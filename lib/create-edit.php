<?php
include_once './lib/config.php';
include_once './lib/session.php';
$ic = $fullName = $gender = $address = $dob = $email = $age = $phoneNumber = '';
$errors = ['ic' => '', 'fullName' => '', 'gender' => '', 'address' => '', 'dob' => '', 'email' => '', 'age' => '', 'phoneNumber' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // IC
    if (empty($_POST['ic'])) {
        $errors['ic'] = 'IC Number is required. Please ensure the IC number is filled out. </br>';
    } else {
        $ic = $_POST['ic'];
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM patients WHERE ic = ? AND id != ?)");

        $stmt->bind_param('si', $ic, $id);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        // @see https://www.php.net/manual/en/mysqli.query.php#102904
        $stmt->close();
        $conn->next_result();

        if (!filter_var($ic, FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => '/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/'
            ]
        ])) {
            $errors['ic'] = 'Please enter a valid IC number.</br>';
        } else if ($exists) {
            $errors['ic'] = 'IC already exists.</br>';
        }
    }

    // FullName
    if (empty($_POST['fullName'])) {
        $errors['fullName'] = 'Full Name is required. Please ensure the full name is filled out.</br>';
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
        $age = (new DateTime())->diff(new DateTime($dob))->y;

        if ($age < 16 || $age > 80) {
            $errors['dob'] = '<ul>
                <li>Age must be <strong>between 16 and 80</strong></li>
            </ul>';
        }
    }

    // Email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required. Please ensure the email is filled out. </br>';
    } else {
        $email = $_POST['email'];

        if (!filter_var($email, FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => '/[-0-9a-zA-Z.+_]+@(sd\.)*taylors\.edu\.my/'
            ]
        ])) {
            $errors['email'] = '<ul>
                <li>must contain <strong>@</strong>symbol</li>
                <li>must have <strong>taylors.edu.my or sd.taylors.edu.my</strong> as domain only.</li>
            </ul>';
        }
    }

    // Phone Number
    if (empty($_POST['phoneNumber'])) {
        $errors['phoneNumber'] = 'Phone number is required. Please ensure the phone number is filled out. </br>';
    } else {
        $phoneNumber = $_POST['phoneNumber'];

        if (!filter_var($phoneNumber, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/\+601[0-46-9]-*[0-9]{7,8}$/']])) {
            $errors['phoneNumber'] = '<ul>
                <li>must <strong>start with +60</strong> and <strong>followed by 9 or 10 digits</strong></li>
            </ul>';
        }
    }

    if (!array_filter($errors)) {
        // create sql
        $ic = mysqli_real_escape_string($conn, $_POST['ic']);
        $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $age = mysqli_real_escape_string($conn, (new DateTime())->diff(new DateTime($_POST['dob']))->y);
        $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);

        $sql = !empty($_POST['id']) ? "UPDATE patients SET ic = '$ic', fullName = '$fullName', gender = '$gender',
                    address = '$address', dob = '$dob', email = '$email',  age = '$age', phoneNumber = '$phoneNumber' 
                    WHERE id = " . $_POST['id'] : "INSERT INTO patients(ic, fullName, gender, address, dob, email, age, phoneNumber) 
                VALUES ('$ic', '$fullName', '$gender', '$address', '$dob', '$email', '$age', '$phoneNumber')";

        $results = mysqli_query($conn, $sql);

        if ($results) {
            $sql = 'SELECT * FROM patients WHERE id = ' . (!empty($_POST['id']) ? $_POST['id'] : '(SELECT LAST_INSERT_ID())');

            $results = mysqli_query($conn, $sql);

            if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_array($results)) {
                    header('Location: index.php?id=' . $row['id']);
                }
            }
        } else {
            $errors['save'] = mysqli_error($conn);
        }
    }
} else if (isEdit()) {
    if (!empty($_GET['id'])) {
        $stmt = $conn->prepare("SELECT id, ic, fullName, gender, address, dob, email, phoneNumber FROM patients WHERE id = ?");

        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $stmt->bind_result($_POST['id'], $_POST['ic'], $_POST['fullName'], $_POST['gender'], $_POST['address'], $_POST['dob'], $_POST['email'], $_POST['phoneNumber']);
        $stmt->fetch();
        // @see https://www.php.net/manual/en/mysqli.query.php#102904
        $stmt->close();
        $conn->next_result();
    } else {
        header('Location: index.php');
        exit;
    }
}

$content = !empty($registeredPatient) ? $registeredPatient : '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="POST">
    ' . (isEdit() ? '<h2>Edit Patient<small>Edit patient information</small>' : '<h2>New Patient
    <small>Please fill this form to register a new patient</small>') . '
    </h2>
    <table>
        <tr>
            <td> IC Number:</td>
            <td>
                <input type="text" name="ic" value="' . ($_POST['ic'] ?? '') . '">
                <div class="error">' . $errors['ic'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Full Name:</td>
            <td>
                <input type="text" name="fullName" value="' . ($_POST['fullName'] ?? '') . '">
                <div class="error">' . $errors['fullName'] . '</div>
            </td>
        </tr>
        <tr>
            <td>Gender:</td>
            <td>
                <input type="radio" name="gender" value="Male" ' . (($_POST['gender'] ?? '') === 'Male' ? 'checked=checked' : '') . '">Male
                <input type="radio" name="gender" value="Female" ' . (($_POST['gender'] ?? '') === 'Female' ? 'checked=checked' : '') . '">Female
                <div class="error">' . $errors['gender'] . '</div>
            </td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>
                <input type="text" name="address" value="' . ($_POST['address'] ?? '') . '">
                <div class="error">' . $errors['address'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Date of Birth:</td>
            <td>
                <input type="date" name="dob" value="' . ($_POST['dob'] ?? '') . '">
                <div class="error">' . $errors['dob'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Email:</td>
            <td>
                <input type="text" name="email" value="' . ($_POST['email'] ?? '') . '">
                <div class="error">' . $errors['email'] . '</div>
            </td>
        </tr>
        <tr>
            <td> Phone Number:</td>
            <td>
                <input type="text" name="phoneNumber" value="' . ($_POST['phoneNumber'] ?? '') . '">
                <div class="error">' . $errors['phoneNumber'] . '</div>
            </td>
        </tr>
    </table>
    <div class="error">' . ($errors['save'] ?? '') . '</div>
    <div>
        <input type="submit" name="submit" value="Save">
        <input type="hidden" name="id" value="' . (isEdit() ? $_POST['id'] : '') . '">
    </div>
</form>';
$styles = '<link href="css/create-edit.css" rel="stylesheet">';

function isEdit()
{
    return strpos($_SERVER['SCRIPT_NAME'], 'edit') !== false;
}

include './lib/template.php';
