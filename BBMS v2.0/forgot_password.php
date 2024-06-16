<?php $current_page = "Password Recovery" ?>
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
    <link rel="stylesheet" href="css/forgot_password_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--SCRIPT-->
    <title><?php echo $current_page . " • "; ?>DonaBlood - Donate Blood Today!</title>
</head>

<body class="w3-animate-opacity">
    <!--<div class="recover_logo">
        <img src="images/blood32.png" alt="logo">
        <a href="#">Dona<span>Blood</span></a>
    </div>-->
    <div class="recover_card">
        <h1>Password Recovery</h1>
        <?php
        // checking if the button named 'verify_details' is clicked
        if (isset($_POST['verify_details'])) {
            // accessing the input values and storing into variables.
            $userEmail = trim($_POST['email']);
            $userNickname = trim($_POST['nickname']);

            // checking in database if there exists such email and nickname
            // sql command to select only emails that user_email = $userEmail and user_nickname = $userNickname
            $sql = "SELECT * FROM users WHERE user_email = :user_email AND user_nickname = :user_nickname";
            // preparing the sql command
            $stmt = $pdo->prepare($sql);
            // executing the statement by binding variable to named parameter
            $stmt->execute([
                ':user_email' => $userEmail,
                ':user_nickname' => $userNickname
            ]);

            // here rowCount() returns no. of rows in table affected by last sql statement
            $count = $stmt->rowCount();
            // if there exist the same credentials already then $count would be only = 1, i.e. != 0
            // that means we cannot proceed with entered details if $count == 0 or $count > 1 then we show up an alert
            if ($count == 1) {
                // we fetch the user details as an associative array from users table as $stmt dealt with users table
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // we need other user details for further comparison
                $user_id = $user['user_id'];
                // here $userFound is triggering to hide the 1st page of forgot password
                $userFound = "found the user";
            } else {
                $credential_error = "Incorrect credentials entered";
            }
        }

        // checking if the button named 'reset' is clicked
        if (isset($_POST['reset'])) {
            // accessing the input values and storing into variables.
            $enteredPassword = trim($_POST['new_password']);
            $enteredcnfPassword = trim($_POST['confirm_password']);
            $user_id = $_POST['user_id'];
            $userFound = "found the user";

            // then we compare if new password and confirm password are same
            if ($enteredPassword == $enteredcnfPassword) {
                // we hash the new password and store it in $new_password_hash
                $new_password_hash = password_hash($enteredPassword, PASSWORD_BCRYPT, array("cost" => 12));

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
                $resetSuccessful = "Password reset successfully";
            } else {
                $passwordMatch_error = "Password did not match";
            }
        }
        ?>
        <?php
        if (!isset($userFound)) { ?>
            <form class="recover_index" action="forgot_password.php" method="POST">
                <?php if (isset($credential_error)) { // throwing alerts for respective errors
                    echo "<p class='alert alert-danger'> {$credential_error} </p>";
                }
                ?>
                <div class="email">
                    <input type="email" name="email" required>
                    <span></span>
                    <label>Email</label>
                </div>
                <div class="nickname">
                    <input type="text" name="nickname" required>
                    <span></span>
                    <label>Nick Name</label>
                </div>
                <div class="back_to_signin">
                    <a href="signin.php">Back to Sign In!</a>
                </div>
                <div class="button">
                    <button name="verify_details" type="submit">Verify</button>
                </div>
                <div class="signup_link">
                    Need an account? <a href="signup.php"> Sign Up!</a>
                </div>
            </form>
        <?php
        } else { ?>
            <form class="recover_index" action="forgot_password.php" method="POST">
                <?php
                if (isset($resetSuccessful)) { // throwing alerts for respective errors
                    echo "<p class='alert alert-success'>
                        {$resetSuccessful}. 
                        <style>
                            a {color:crimson;}
                            a:hover {text-decoration: underline;}
                        </style>
                        <a href='signin.php'> Sign In now </a> </p>";
                } else if (isset($passwordMatch_error)) {
                    echo "<p class='alert alert-danger'> {$passwordMatch_error} </p>";
                }
                ?>
                <div class="new_password">
                    <input name="user_id" type="hidden" value="<?php echo $user_id; ?>">
                    <input type="password" name="new_password" required>
                    <span></span>
                    <label>New Password</label>
                </div>
                <div class="confirm_password">
                    <input type="password" name="confirm_password" required>
                    <span></span>
                    <label>Confirm Password</label>
                </div>
                <div class="button">
                    <button name="reset" type="submit">Reset Password</button>
                </div>
                <div class="signup_link">
                    Need an account? <a href="signup.php"> Sign Up!</a>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</body>