<?php session_start(); ?>
<?php $current_page = "Sign In" ?>
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
    <link rel="stylesheet" href="css/signin_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!--SCRIPT-->
    <title><?php echo $current_page . " • "; ?>DonaBlood - Donate Blood Today!</title>
</head>

<body class="w3-animate-opacity">
    <!--<div class="signin_logo">
        <img src="images/blood32.png" alt="logo">
        <a href="#">Dona<span>Blood</span></a>
    </div> -->
    <div class="signin_card">
        <h1>SIGN IN</h1>
        <?php
        // checking if the button named 'signin' is clicked
        if (isset($_POST['signin'])) {
            // accessing the input values and storing into variables.
            $emailAttempt = trim($_POST['email']);
            $passwordAttempt = trim($_POST['password']);

            // checking in database if there already exists such email
            // sql command to select only emails that user_email = $emailAttempt
            $sql = "SELECT * FROM users WHERE user_email = :user_email";
            // preparing the sql command
            $stmt = $pdo->prepare($sql);
            // executing the statement by binding variable to named parameter
            $stmt->execute([
                ':user_email' => $emailAttempt
            ]);

            // here rowCount() returns no. of rows in table affected by last sql statement
            $count = $stmt->rowCount();
            // if there exist the same email already then $count would be only = 1, i.e. != 0
            // that means we cannot proceed with entered email if $count == 0 or $count > 1 then we show up an alert
            if ($count == 0) {
                $credential_error = "Incorrect credentials entered";
            } else if ($count > 1) {
                $credential_error = "Incorrect credentials entered";
            } else if ($count == 1) { // if the $count ==1 then we can proceed further by password verifying
                // we fetch the user details as an associative array from users table as $stmt dealt with users table
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // we store the hashed password into a variable
                $user_password_hash = $user['user_password'];
                // password_verify() returns TRUE if hash matched with password else FALSE
                $validPassword = password_verify($passwordAttempt, $user_password_hash);
                // we need other user details for showing in index page
                $user_name = $user['user_name'];
                $user_id = $user['user_id'];
                if ($validPassword) { // if TRUE we redirect user to index.php
                    if (!empty($_POST['check_rem_pass'])) {
                        // get user id , user nickname and store it in cookie
                        $user_id = $user['user_id'];
                        $user_nickname = $user['user_nickname'];
                        //since direct storage of id in cookie is dangerous, we will encode it and then store it
                        $d_user_id = base64_encode($user_id);
                        $d_user_nickname = base64_encode($user_nickname);
                        // we are setting cookie expiry to 24 hrs
                        setcookie('_uid_', $d_user_id, time() + 86400, "/", "", "", true);
                        setcookie('_unn_', $d_user_id, time() + 86400, "/", "", "", true);
                    }
                    // we are giving a name to session
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['login'] = 'success';
                    header('Location: index.php');
                    /*header('Refresh:2;Location: index.php');*/
                } else { // if FALSE we show up an alert
                    $password_error = "Incorrect password entered";
                }
            }
        }
        ?>
        <form class="signin_index" action="signin.php" method="POST">
            <?php if (isset($credential_error)) { // throwing alerts for respective errors
                echo "<p class='alert alert-danger'> {$credential_error} </p>";
            } else if (isset($password_error)) {
                echo "<p class='alert alert-danger'> {$password_error} </p>";
            }
            ?>
            <div class="email">
                <input type="email" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="password">
                <input cols="30" rows="10" name="password" type="password" id="myInput1" required></input>
                <input type="checkbox" onclick="myFunction('myInput1')">
                <span></span>
                <label>Password</label>
            </div>
            <div class="remember_password">
                <input name="check_rem_pass" type="checkbox">
                <label>Remember Me</label>
            </div>
            <div class="forgot_password">
                <a href="forgot_password.php"> Forgot Password?</a>
            </div>
            <div class="button">
                <button name="signin" type="submit">Sign In</button>
            </div>
            <div class="signup_link">
                Need an account? <a href="signup.php"> Sign Up!</a>
            </div>
        </form>
    </div>
    <script src="script/app.js"></script>
</body>