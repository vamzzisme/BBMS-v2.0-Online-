<?php session_start(); ?>
<?php $current_page = "Sign Up" ?>
<?php require_once("db.php"); ?>

<?php
// we are preventing the user in login session to access signin and signup pages
if (isset($_SESSION['login']) || isset($_COOKIE['_uid_']) || isset($_COOKIE['_unn_'])) {
    header('Location: index.php');
}
?>

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
    <link rel="stylesheet" href="css/signup_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--SCRIPT-->
    <title><?php echo $current_page . " • "; ?>DonaBlood - Donate Blood Today!</title>
</head>

<body class="w3-animate-opacity">
    <!--<div class="signup_logo">
        <img src="images/blood32.png" alt="logo">
        <a href="#">Dona<span>Blood</span></a> 
    </div>-->
    <div class="signup_card">
        <h1>SIGN UP</h1>
        <?php
        // checking if the button named 'signup' is clicked
        if (isset($_POST['signup'])) {
            // accessing the input values and storing into variables.
            $firstnameAttempt = trim($_POST['first_name']);
            $lastnameAttempt = trim($_POST['last_name']);
            $nicknameAttempt = trim($_POST['nick_name']);
            $full_name = $firstnameAttempt . " " . $lastnameAttempt;
            $emailAttempt = trim($_POST['email']);
            $defaultPhoto = "default-image.png";

            // checking in database if there already exists such nickname
            // sql command to select only emails that user_nickname = $nicknameAttempt
            $sql = "SELECT * FROM users WHERE user_nickname = :user_nickname";
            // preparing the sql command
            $stmt = $pdo->prepare($sql);
            // executing the statement by binding variable to named parameter
            $stmt->execute([
                ':user_nickname' => $nicknameAttempt
            ]);

            // here rowCount() returns no. of rows in table affected by last sql statement
            $countNickname = $stmt->rowCount();
            // if there exist the same email already then $countNickname would be >= 1, i.e. != 0
            // that means he cannot proceed with entered email
            if ($countNickname != 0) {
                $nicknameExist_error = "Nickname already exits. Try another one";
            } else {
                // checking in database if there already exists such email
                // sql command to select only emails that user_email = $emailAttempt
                $sql1 = "SELECT * FROM users WHERE user_email = :user_email";
                // preparing the sql command
                $stmt1 = $pdo->prepare($sql1);
                // executing the statement by binding variable to named parameter
                $stmt1->execute([
                    ':user_email' => $emailAttempt
                ]);

                // here rowCount() returns no. of rows in table affected by last sql statement
                $countEmail = $stmt1->rowCount();
                // if there exist the same email already then $countEmail would be >= 1, i.e. != 0
                // that means he cannot proceed with entered email
                if ($countEmail != 0) {
                    $emailExist_error = "Email already exits. Try another one";
                } else { // if the $countEmail >= 1 then we can proceed further by hashing password
                    // accessing the input values and storing into variables.
                    $passwordAttempt = trim($_POST['password']);
                    $cnfpasswordAttempt = trim($_POST['confirm_password']);
                    // verifying if password and confirm password are equal
                    // if the both passwords are same then we go for password hashing or else we show up an alert
                    if ($passwordAttempt != $cnfpasswordAttempt) {
                        $passwordMatch_error = "Password did not match";
                    } else {
                        /* we are hashing the entered $passwordAttemp with PASSWORD_BCRYPT and increasing the process of hashing by cost of 12 */
                        $passwordHash = password_hash($passwordAttempt, PASSWORD_BCRYPT, array("cost" => 12));

                        //setting default time zone for created_on column
                        date_default_timezone_set("Asia/Kolkata");
                        // after hashing the password we insert the input values into database
                        // sql command to insert values that are binded to named parameters into the colomns
                        $sql2 = "INSERT INTO users (user_name, user_nickname, user_email, user_password, user_photo, created_on) VALUES (:user_name, :user_nickname, :user_email, :user_password, :user_photo, :created_on)";
                        // preparing the sql command
                        $stmt2 = $pdo->prepare($sql2);
                        // executing the statement by binding variable to named parameter
                        // execute() returns TRUE on success and FALSE on failure
                        $result = $stmt2->execute([
                            ':user_name' => $full_name,
                            ':user_nickname' => $nicknameAttempt,
                            ':user_email' => $emailAttempt,
                            ':user_password' => $passwordHash,
                            ':user_photo' => $defaultPhoto,
                            ':created_on' => date('M j, Y') . " at "  . date('h:i A')
                        ]);
                        // if success then we give an success alert
                        if ($result) {
                            $sigupSuccessful = "Account created successfully";
                        }
                    }
                }
            }
        }
        ?>
        <form class="signup_index" action="signup.php" method="POST">
            <?php if (isset($nicknameExist_error)) { // throwing alerts for respective errors
                echo "<p class='alert alert-danger'> {$nicknameExist_error} </p>";
            } else if (isset($emailExist_error)) {
                echo "<p class='alert alert-danger'> {$emailExist_error} </p>";
            } else if (isset($passwordMatch_error)) {
                echo "<p class='alert alert-danger'> {$passwordMatch_error} </p>";
            } else if (isset($sigupSuccessful)) {
                echo "<p class='alert alert-success'> 
                    {$sigupSuccessful}. 
                    <style>
                        a {color:crimson;}
                        a:hover {text-decoration: underline;}
                    </style>
                    <a href='signin.php'> Sign In now </a></p>";
            }
            ?>
            <div class="full_name">
                <div class="first_name">
                    <input type="text" name="first_name" required>
                    <span></span>
                    <label>First Name</label>
                </div>
                <div class="last_name">
                    <input type="text" name="last_name" required>
                    <span></span>
                    <label>Last Name</label>
                </div>
            </div>
            <div class="nick_name">
                <i class="fa fa-info-circle" data-animation=true data-placement="right" title="Please remember this value as it is required during password recovery"></i>
                <input type="text" name="nick_name" required>
                <span></span>
                <label>Nick Name</label>
            </div>
            <div class="email">
                <input type="email" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="final_password">
                <div class="password">
                    <input cols="30" rows="10" name="password" type="password" required></input>
                    <span></span>
                    <label>Password</label>
                </div>
                <div class="confirm_password">
                    <input cols="30" rows="10" name="confirm_password" type="password" required></input>
                    <span></span>
                    <label>Confirm Password</label>
                </div>
            </div>
            <div class="button">
                <button name="signup" type="submit">Sign Up</button>
            </div>
            <div class="signin_link">
                Have an account? <a href="signin.php"> Sign In!</a>
            </div>
        </form>
    </div>
</body>