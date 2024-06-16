<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "Profile" ?>
<?php require_once("db.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--FONTS-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Open+Sans&family=PT+Sans&family=Poppins&family=Ubuntu&display=swap" rel="stylesheet">
    <!--STYLESHEET-->
    <link rel="icon" href="images\blood.png" type="image/x-icon">
    <link rel="stylesheet" href="css/profile_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--SCRIPT-->
    <title><?php echo $current_page . " • "; ?>DonaBlood - Donate Blood Today!</title>
</head>

<!-- allowing user to use only if he is logged in -->
<?php
if (isset($_SESSION['login'])) {
    // its ok to use
} else {
    header('Location: index.php');
}
?>

<!--NAVIGATION BAR-->
<nav class="navbar">
    <div class="max-width">
        <div class="logo">
            <img src="images/blood32.png" alt="logo">
            <a href="#">Dona<span>Blood</span></a>
        </div>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <ul class="registration">
            <?php
            require_once("signin_info.php");
            ?>
        </ul>
    </div>
</nav>

<body>
    <div class="heading">
        <span class="fa fa-tasks"></span> <span class="page-title">Manage Profile</span>
    </div>
    <?php
    //if either cookie of id is set or session name of id is set, we get the user_id
    if (isset($_COOKIE['_uid_'])) {
        // if we access through cookie, since we hashed it, we decode and store it in $user_id
        $user_id = base64_decode($_COOKIE['_uid_']);
    } else if (isset($_SESSION['user_id'])) {
        // if we access through session, we can directly store it in $user_id
        $user_id = $_SESSION['user_id'];
    } else {
        // if he is not signed in we take it to be -1
        $user_id = -1;
    }
    // selecting from database the user of such id
    // sql command to select only users that user_ud = $user_id
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    // preparing the sql command
    $stmt = $pdo->prepare($sql);
    // executing the statement by binding variable to named parameter
    $stmt->execute([
        ':user_id' => $user_id
    ]);
    // get user details and store it in variables and show them in the fields
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $user['user_id'];
    $user_name = $user['user_name'];
    $user_nickname = $user['user_nickname'];
    $user_email = $user['user_email'];
    $user_mob = $user['user_mob'];
    $user_dob = $user['user_dob'];
    $user_gender = $user['user_gender'];
    $user_photo = $user['user_photo'];
    $user_address = $user['user_address'];
    ?>

    <?php
    // checking if update button is clicked
    if (isset($_POST['update'])) {
        // getting the values entered by user in the fields
        $user_name = $_POST['full_name'];
        $user_nickname = $_POST['nick_name'];
        $user_email = $_POST['email'];
        $user_mob = $_POST['mobile'];
        $user_dob = $_POST['dob'];
        $user_gender = $_POST['gender'];
        $user_address = $_POST['address'];
        $user_photo = $_FILES['photo']['name'];
        $user_photo_tmp = $_FILES['photo']['tmp_name'];
        move_uploaded_file("{$user_photo_tmp}", "images/{$user_photo}");
        if (empty($user_photo)) { // if photo is not uploaded 
            // sql command to select user where user_id = $user_id
            $sql = "SELECT * FROM users WHERE user_id = :user_id";
            // preparing the sql command
            $stmt = $pdo->prepare($sql);
            // executing the statement by binding variable to named parameter
            $stmt->execute([
                ':user_id' => $user_id
            ]);
            // we take users existing photo and store it in variables
            $u = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_photo = $u['user_photo'];
        }
        // if everything is entered
        // sql command to update users by setting values to their respective columns based on their id
        $sql = "UPDATE users SET user_name = :user_name, user_nickname = :user_nickname, user_email = :user_email, user_mob = :user_mob, user_dob = :user_dob, user_gender = :user_gender, user_address = :user_address, user_photo = :user_photo WHERE user_id = :user_id";
        // preparing the sql command
        $stmt = $pdo->prepare($sql);
        // executing the statement by binding variable to named parameter
        $stmt->execute([
            ':user_name' => $user_name,
            ':user_nickname' => $user_nickname,
            ':user_email' => $user_email,
            ':user_mob' => $user_mob,
            ':user_dob' => $user_dob,
            ':user_gender' => $user_gender,
            ':user_address' => $user_address,
            ':user_photo' => $user_photo,
            ':user_id' => $user_id
        ]);
        // after everything we redirect into profile.php by giving a success alert
        $updateSuccessful = "Updated Successfully. Please re-signin for viewing changes";
    }

    // checking if update button is clicked
    if (isset($_POST['change_password'])) {
        // getting the values entered by user in the fields
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        // selecting from database the user of such id
        // sql command to select only users that user_id = $user_id
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        // preparing the sql command
        $stmt = $pdo->prepare($sql);
        // executing the statement by binding variable to named parameter
        $stmt->execute([
            ':user_id' => $user_id
        ]);
        // we fetch user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // we store the hashed password into a variable
        $user_password_hash = $user['user_password'];
        // password_verify() returns TRUE if hash matched with password else FALSE
        $validPassword = password_verify($old_password, $user_password_hash);
        if ($validPassword) {
            // then we compare if new password and confirm password are same
            if ($new_password == $confirm_password) {
                // we hash the new password and store it in $new_password_hash
                $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT, array("cost" => 12));

                // sql command to update only user that user_id = $user_id
                $sql = "UPDATE users SET user_password = :user_password WHERE user_id = :user_id";
                // preparing the sql command
                $stmt = $pdo->prepare($sql);
                // executing the statement by binding variable to named parameter
                $stmt->execute([
                    ':user_password' => $new_password_hash,
                    ':user_id' => $user_id
                ]);
                // giving alert that reset is done
                $resetSuccessful = "Password reset successfully. Please re-signin for viewing changes";
            } else {
                $passwordMatch_error = "Password did not match";
            }
        } else { // if FALSE we show up an alert
            $password_error = "Incorrect password entered";
        }
    }
    ?>
    <form class="update-profile" action="profile.php" method="POST" enctype="multipart/form-data">
        <span class="header"> UPDATE PROFILE: </span>
        <?php if (isset($updateSuccessful)) {
            echo "<p class='alert alert-success'> 
                {$updateSuccessful}.
                <a href='signout.php' class='alert-link'> Re-Sign In now </a> </p>";
        }
        ?>
        <div class="fields">
            <div class="full_name">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo $user_name; ?>" placeholder="Full Name" required>
            </div>
            <div class="box2">
                <div class="nick_name">
                    <label>Nick Name</label>
                    <i class="fa fa-info-circle" data-animation=true data-placement="right" title="Please remember this value as it is required during password recovery"></i>
                    <input type="text" name="nick_name" value="<?php echo $user_nickname; ?>" placeholder="Nick Name" required>
                </div>
                <div class="email">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user_email; ?>" placeholder="abc@gmail.com" required>
                </div>
            </div>
            <div class="box3">
                <div class="mobile">
                    <label>Mobile Number</label>
                    <i class="fa fa-info-circle" data-animation=true data-placement="right" title="Please enter only 10-digit mobile number"></i>
                    <input type="tel" pattern="[0-9]{10}" name="mobile" value="<?php echo $user_mob; ?>" placeholder="123456789" required>
                </div>
                <div class="dob">
                    <label>Date of Birth</label>
                    <input type="text" name="dob" value="<?php echo $user_dob; ?>" placeholder="dd-mm-yyyy" required>
                </div>
            </div>
            <div class="box4">
                <div class="gender">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option disabled></option>
                        <option value="male" <?php if ($user_gender == 'male') echo 'selected'; ?>>MALE</option>
                        <option value="female" <?php if ($user_gender == 'female') echo 'selected'; ?>>FEMALE</option>
                        <option value="other" <?php if ($user_gender == 'other') echo 'selected'; ?>>OTHER</option>
                    </select>
                </div>
                <div class="photo">
                    <label>Profile Image</label>
                    <input type="file" name="photo" value="<?php echo $user_photo; ?>">
                    <img src="images/<?php echo $user_photo; ?>" alt="user_photo" width="45" height="45">
                </div>
            </div>
            <div class="address">
                <label>Address</label>
                <input cols="30" rows="10" type="text" name="address" value="<?php echo $user_address; ?>" placeholder="Address" required></input>
            </div>
            <div class="button">
                <button name="update" type="submit">Update Profile</button>
            </div>
        </div>
    </form>
    <div class="change-password">
        <span class="header"> CHANGE PASSWORD: </span>
        <?php
        if (isset($password_error)) {
            echo "<p class='alert alert-danger'> {$password_error} </p>";
        } else if (isset($resetSuccessful)) { // throwing alerts for respective errors
            echo "<p class='alert alert-success'>
                    {$resetSuccessful}.
                    <a href='signout.php' class='alert-link'> Re-Sign In now </a> </p>";
        } else if (isset($passwordMatch_error)) {
            echo "<p class='alert alert-danger'> {$passwordMatch_error} </p>";
        }
        ?>
        <form action="profile.php" method="POST">
            <div class="fields">
                <div class="old_password">
                    <label>Old Password</label>
                    <input type="password" name="old_password" required>
                </div>
                <div class="new_password">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="confirm_password">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <div class="button">
                    <button name="change_password" type="submit">Change Password</button>
                </div>
            </div>
        </form>
    </div>
    <div class="delete_account">
        <span class="header"> DELETE ACCOUNT: </span>
        <form action="delete_account.php" method="POST">
            <div class="fields">
                <div class="delete">
                    <label>If you are really sure to delete your account, click the following button</label>
                    <button name="delete" type="submit">Delete Account</button>
                </div>
            </div>
        </form>
    </div>

    <?php require_once("footer.php"); ?>