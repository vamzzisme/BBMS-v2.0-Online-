<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "Contact" ?>
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
    <link rel="stylesheet" href="css/contact_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--SCRIPT-->
    <title><?php echo $current_page . " • "; ?>DonaBlood - Donate Blood Today!</title>
</head>

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
            <li><a id="contact_focus" href="contact.php">Contact</a></li>
        </ul>
        <ul class="registration">
            <?php
            require_once("signin_info.php");
            ?>
        </ul>
    </div>
</nav>

<body>
    <?php
    if (isset($_COOKIE['_uid_']) || isset($_COOKIE['_unn_']) || isset($_SESSION['login'])) {  ?>
        <div class="container">
            <form class="message_contact" action="contact.php" method="POST">
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
                $user_email = $user['user_email'];

                // checking if send button is clicked
                if (isset($_POST['send'])) {
                    // getting the values entered by user in the fields
                    $ms_subject = trim($_POST['subject']);
                    $ms_detail = trim($_POST['message']);

                    //setting default time zone for created_on column
                    date_default_timezone_set("Asia/Kolkata");
                    // sql command to insert message where user_id = $user_id
                    $sql = "INSERT INTO messages SET ms_username = :ms_username, ms_email = :ms_email, ms_subject = :ms_subject, ms_detail = :ms_detail, ms_date = :ms_date";
                    // preparing the sql command
                    $stmt = $pdo->prepare($sql);
                    // executing the statement by binding variable to named parameter
                    $stmt->execute([
                        ':ms_username' => $user_name,
                        ':ms_email' => $user_email,
                        ':ms_subject' => $ms_subject,
                        ':ms_detail' => $ms_detail,
                        ':ms_date' => date('M j, Y') . " at "  . date('h:i A')
                    ]);
                    $messageSent = "Message sent successfully";
                    echo "<p class='alert alert-success'> {$messageSent}.<a class='alert-link' href='contact.php'>Complete Process</a> </p>";
                }
                ?>
                <div class="fields">
                    <div class="name">
                        <label>Full name</label>
                        <input type="text" name="name" readonly="true" placeholder="Name" value="<?php echo $user_name; ?>" required>
                    </div>
                    <div class="email">
                        <label>Email</label>
                        <input type="email" name="email" readonly="true" placeholder="abc@gmail.com" value="<?php echo $user_email; ?>" required>
                    </div>
                    <div class="subject">
                        <label>Subject</label>
                        <input type="text" name="subject" placeholder="Subject" required>
                    </div>
                    <div class="message">
                        <label>Message</label>
                        <textarea cols="30" rows="10" name="message" placeholder="Message your query" required></textarea>
                    </div>
                    <div class="button">
                        <button name="send" type="submit">Send Message</button>
                    </div>
                </div>
            </form>
        <?php } else { ?>
            <div class="error">
                <p class='alert alert-warning'> Please sign in to contact us.
                    <a href='signin.php' class="alert-link"> Sign In now</a>
                </p>
            </div>
        <?php
    }
        ?>
        </div>

        <?php require_once("footer.php"); ?>