<?php

$content = '<form action="searchUpdateDelete.php" method="post">

    <label for="id">ID to search: </label><br>
    <input type="text" name="id" id="id"><br><br>
    <input type="submit" name="search" value="search">

    </form>';

include 'template.php';

?>

<?php
include("config.php");
$errors = array('fullName' => '', 'gender' => '', 'address' => '', 'dob' => '', 'email' => '', 'age' => '', 'phoneNumber' => '');

if(isset($_POST["search"]))
{
    $id = $_POST["id"];
    $sql = 'SELECT id, fullName, gender, address, dob, email, age, phoneNumber FROM patients WHERE id = (SELECT LAST_INSERT_ID())';
    $results = mysqli_query($conn, $sql);

    if(mysqli_num_rows($results) > 0)
    {
        while($row = mysqli_fetch_array($results))
        {
        ?>
            <form action="searchUpdateDelete.php" method="post">
                <label for="id"> ID </label><br>
                <input type="text" name="id" readonly value="<?php echo $row['id']?>"><br><br>

                <label for="id"> Full Name </label><br>
                <input type="text" name="fullName" value="<?php echo $row['fullName']?>"><br><br>
               
                <label for="id"> Gender </label><br>
                <input type="text" name="gender" value="<?php echo $row['gender']?>"><br><br>

                <label for="id"> Address </label><br>
                <input type="text" name="address" value="<?php echo $row['address']?>"><br><br>

                <label for="id"> Date of Birth </label><br>
                <input type="text" name="dob" value="<?php echo $row['dob']?>"><br><br>

                <label for="id"> Email </label><br>
                <input type="text" name="email" value="<?php echo $row['email']?>"><br><br>

                <label for="id"> Age </label><br>
                <input type="text" name="age" value="<?php echo $row['age']?>"><br><br>

                <label for="id"> Phone Number </label><br>
                <input type="text" name="phoneNumber" value="<?php echo $row['phoneNumber']?>"><br><br>

                <input type="submit" name="update" value="Update">
                <input type="submit" name="delete" value="Delete">

        
            </form>
            <?php
        }
    }

    else
    {
        echo "The ID $id is not found.";
    }
}

if(isset($_POST["update"]))
{ 
    $id=$_POST['id'];

     if(empty($_POST["fullName"]))
     {
         $errors['fullName'] = "Full Name is required. Please ensure the full name is filled out. </br>";
     }
     else
     {
         $fullName =$_POST['fullName'];
     }

     if(empty($_POST["gender"]))
     {
         $errors['gender'] = "Gender is required. Please ensure the gender is filled out. </br>";
     }
     else
     {
         $gender =$_POST['gender'];
     }

     if(empty($_POST["address"]))
     {
         $errors['address'] = "Address is required. Please ensure the address is filled out. </br>";
     }
     else
     {
         $address =$_POST['address'];
     }

     if(empty($_POST["dob"]))
     {
         $errors['dob'] = "Date of Birth is required. Please ensure the date of birth is filled out. </br>";
     }
     else
     {
         $dob =$_POST['dob'];
     }
 
     /*Email---------------------------------------------*/
 
     if(empty($_POST["email"]))
     {
         $errors['email'] = "Email is required. Please ensure the email is filled out. </br>";
     }
     else
     {
         $email = $_POST['email'];
 
         if(!filter_var($email,FILTER_VALIDATE_EMAIL))
         {
            $errors['email'] = "Please insert a valid email. Example: abc@taylors.edu.my </br>";
         }
     }
 
     /*Age------------------------------------------*/
     if(empty($_POST["age"]))
     {
         $errors['age'] = "Age is required. Please ensure the age is filled out. </br>";
     }
     else
     {
         $age = $_POST['age'];
 
         if(!filter_var($age,FILTER_VALIDATE_AGE))
         {
             $errors['age'] = "Please insert a valid age. Example: 16. </br>";
         }
     }
     
      /*Phone Number------------------------------------------*/
     if(empty($_POST["phoneNumber"]))
     {
         $errors['phoneNumber'] = "Phone number is required. Please ensure the phone number is filled out. </br>";
     }
     else
     {
         $phoneNumber = $_POST['phoneNumber'];
 
         if(!filter_var($phoneNumber,FILTER_VALIDATE_PHONENUMBER))
         {
             $errors['phoneNumber'] = "Please insert a valid phone number. Example: +60. </br>";
         }
     }         

     if(!array_filter($errors))
     { 
        $id=$_POST["id"];
        $fullName = mysqli_real_escape_string ($conn, $_POST['fullName']);
        $gender = mysqli_real_escape_string ($conn, $_POST['gender']);
        $address = mysqli_real_escape_string ($conn, $_POST['address']);
        $dob = mysqli_real_escape_string ($conn, $_POST['dob']);
        $email = mysqli_real_escape_string ($conn, $_POST['email']);
        $age = mysqli_real_escape_string ($conn, $_POST['age']);
        $phoneNumber = mysqli_real_escape_string ($conn, $_POST['phoneNumber']);

        $sql = "UPDATE patients SET name='$fullName', gender='$gender', address='$address', dob='$dob', email='$email', age='$age', phoneNumber='$phoneNumber' WHERE id='$id'";

        $results = mysqli_query($conn, $sql);

        if($results)
        {
        header("location:retrievedata.php");
        }
        else
        {
            echo "Data is NOT updated.";
        }
    }
    else
    {
        ?>
    <form action="searchUpdateDelete.php" method="post">
        <label for="id"> ID </label><br>
        <input type="text" name="id" readonly value="<?php echo $id?>"><br><br>

        <label for="id"> Full Name </label><br>
        <input type="text" name="fullName" value="<?php echo $fullName?>"><br>
        <div style="color:red"><?php echo $errors['fullName'] ?></div><br>
        
        <label for="id"> Gender </label><br>
        <input type="text" name="gender" value="<?php echo $gender?>"><br>
        <div style="color:red"><?php echo $errors['gender'] ?></div><br>

        <label for="id"> Address </label><br>
        <input type="text" name="address" value="<?php echo $address?>"><br>
        <div style="color:red"><?php echo $errors['address'] ?></div><br>

        <label for="id"> Date of Birth </label><br>
        <input type="text" name="dob" value="<?php echo $dob?>"><br>
        <div style="color:red"><?php echo $errors['dob'] ?></div><br>

        <label for="id"> Email </label><br>
        <input type="text" name="email" value="<?php echo $email?>"><br>
        <div style="color:red"><?php echo $errors['email'] ?></div><br>

        <label for="id"> Age </label><br>
        <input type="text" name="age" value="<?php echo $age?>"><br>
        <div style="color:red"><?php echo $errors['age'] ?></div><br>

        <label for="id"> Phone Number </label><br>
        <input type="text" name="phoneNumber" value="<?php echo $phoneNumber?>"><br>
        <div style="color:red"><?php echo $errors['phoneNumber'] ?></div><br>        

        <input type="submit" name="update" value="Update">
        <input type="submit" name="delete" value="Delete">
    </form>

        <?php
    }
}

if(isset($_POST["delete"]))
{
    $id = $_POST["id"];
    $sql = "DELETE FROM patients WHERE id='$id'";
    $results = mysqli_query($conn, $sql);

    if($results)
    {
        header("location:retrievedata.php");
    }
    else
    {
        echo "Date is NOT deleted.";
    }
}
?>
