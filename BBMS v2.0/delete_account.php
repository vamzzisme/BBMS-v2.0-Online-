<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "Delete Account" ?>
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
    <link rel="stylesheet" href="css/delete_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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

<body class="w3-animate-opacity">
    <!--<div class="signin_logo">
        <img src="images/blood32.png" alt="logo">
        <a href="#">Dona<span>Blood</span></a>
    </div> -->
    <div class="delete_card">
        <h1>DELETE ACCOUNT</h1>
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
        $user_nickname = $user['user_nickname'];
        $user_email = $user['user_email'];
        ?>

        <?php
        if (isset($_POST['delete_account'])) {
            // getting the values entered by user in the fields
            $userPassword = trim($_POST['password']);
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
            $user_id = $user['user_id'];
            // we store the hashed password into a variable
            $user_password_hash = $user['user_password'];
            // password_verify() returns TRUE if hash matched with password else FALSE
            $validPassword = password_verify($userPassword, $user_password_hash);
            if ($validPassword) { // if TRUE we delete the user based on their id
                // deleting in database
                // sql command to delete only user that user_id = $user_id
                $sql1 = "DELETE FROM users WHERE user_id = :user_id";
                // preparing the sql command
                $stmt1 = $pdo->prepare($sql1);
                // executing the statement by binding variable to named parameter
                $stmt1->execute([
                    ':user_id' => $user_id
                ]);
                // success alert
                $deleteSuccessful = "Your account has been deleted successfully.";
            } else { // incorrect password alert
                $password_error = "Incorrect password entered";
            }
        }
        ?>
        <form class="delete_index" action="delete_account.php" method="POST">
            <?php if (isset($credential_error)) { // throwing alerts for respective errors
                echo "<p class='alert alert-danger'> {$credential_error} </p>";
            } else if (isset($deleteSuccessful)) { // throwing alerts for respective errors
                echo "<p class='alert alert-success'> {$deleteSuccessful} <a href='signout.php' class='alert-link'> Complete Process </a> </p>";
            } else if (isset($password_error)) {
                echo "<p class='alert alert-danger'> {$password_error} </p>";
            }
            ?>
            <div class="box1">
                <div class="email">
                    <input type="email" name="email" readonly="true" value="<?php echo $user_email; ?>" required>
                    <span></span>
                    <label>Email</label>
                </div>
                <div class="nickname">
                    <input type="text" name="nickname" readonly="true" value="<?php echo $user_nickname; ?>" required>
                    <span></span>
                    <label>Nick Name</label>
                </div>
            </div>
            <div class="password">
                <input name="password" type="password" required></input>
                <span></span>
                <label>Password</label>
            </div>
            <?php echo "<p class='alert alert-danger'> <i class='fa fa-exclamation-triangle'></i> Remember that if you delete this account all your data given to us will be erased and this action cannot be reverted!! </p>"; ?>
            <div class="button">
                <button name="delete_account" type="submit">Delete Account</button>
            </div>
            <div class="signup_link">
                Need an account? <a href="signup.php"> Sign Up!</a>
            </div>
        </form>
</body>