<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "Donations" ?>
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
    <link rel="stylesheet" href="css/donations_style.css">
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
    $signin_error = "Please sign in to donate";
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
        <span class="fa fa-plus"></span> &nbsp;<span class="page-title">Donations</span>
    </div>
    <?php
    if (isset($_COOKIE['_uid_']) || isset($_COOKIE['_unn_']) || isset($_SESSION['login'])) {  ?>
        <div class="container">
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
            $donar_name = $user['user_name'];
            $donar_email = $user['user_email'];
            ?>
            <?php
            if (isset($_POST['donate'])) {
                $donar_bloodgrp = $_POST['bloodgrp'];
                $blood_amount = $_POST['amount'];

                //setting default time zone for created_on column
                date_default_timezone_set("Asia/Kolkata");

                // sql command to update donars by setting values to their respective columns based on their email
                $sql = "INSERT INTO donars SET donar_name = :donar_name, donar_email = :donar_email, donar_bloodgrp = :donar_bloodgrp, blood_amount = :blood_amount, donated_time = :donated_time";
                // preparing the sql command
                $stmt = $pdo->prepare($sql);
                // executing the statement by binding variable to named parameter
                $stmt->execute([
                    ':donar_name' => $donar_name,
                    ':donar_email' => $donar_email,
                    ':donar_bloodgrp' => $donar_bloodgrp,
                    ':blood_amount' => $blood_amount,
                    ':donated_time' => date('M j, Y') . " at "  . date('h:i A'),
                ]);
                // after everything we redirect into profile.php by giving a success alert
                $donateSuccessful = "Donated Successfully";
            }
            ?>
            <form class="donation_index" action="donations.php" method="POST">
                <span class="header"> DONATE NOW: </span>
                <?php if (isset($donateSuccessful)) {
                    echo "<p class='alert alert-success'> 
                {$donateSuccessful}. <a class='alert-link' href='donations.php'>Complete Process</a>";
                }
                ?>
                <div class="fields">
                    <div class="name">
                        <label>Donar Name</label>
                        <input type="text" readonly="true" name="name" placeholder="Name" value="<?php echo $donar_name; ?>" required>
                    </div>
                    <div class="email">
                        <label>Email</label>
                        <input type="email" readonly="true" name="email" value="<?php echo $donar_email; ?>" placeholder="abc@gmail.com" required>
                    </div>
                    <div class="bloodgrp">
                        <label>Blood Group</label>
                        <select name="bloodgrp" required>
                            <option disabled selected></option>
                            <option value="A+">A+</option>
                            <option value="A-">A- </option>
                            <option value="B+">B+</option>
                            <option value="B-">B- </option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB- </option>
                            <option value="O+">O+</option>
                            <option value="O-">O- </option>
                        </select>
                    </div>
                    <div class="amount">
                        <label>Amount (in ml) </label>
                        <input type="number" name="amount" placeholder="Amount (in ml)" required>
                    </div>
                    <div class="button">
                        <button name="donate" type="submit">Donate</button>
                    </div>
                </div>
            </form>
            <span class="header"> MY DONATIONS: </span>
            <table class="table-show" width="85%" cellspacing="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donar Name</th>
                        <th>Donar Email</th>
                        <th>Donar Blood Group</th>
                        <th>Amount Donated (in ml)</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // sql command to select all donations 
                    $sql = "SELECT * FROM donars WHERE donar_email = :donar_email";
                    // preparing the sql command
                    $stmt = $pdo->prepare($sql);
                    // executing the statement by binding variable to named parameter
                    $stmt->execute([
                        ':donar_email' => $donar_email
                    ]);
                    while ($donar = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $donar_id = $donar['donar_id'];
                        $donar_name = $donar['donar_name'];
                        $donar_email = $donar['donar_email'];
                        $donar_bloodgrp = $donar['donar_bloodgrp'];
                        $blood_amount = $donar['blood_amount'];
                        $donate_time = $donar['donated_time'];
                        $donate_status = $donar['donate_status']; ?>
                        <tr>
                            <td><?php echo $donar_id; ?></td>
                            <td><?php echo $donar_name; ?></td>
                            <td><?php echo $donar_email; ?></td>
                            <td><?php echo $donar_bloodgrp; ?></td>
                            <td><?php echo $blood_amount; ?></td>
                            <td><?php echo $donate_time; ?></td>
                            <td><?php if ($donate_status == "pending") { ?>
                                    <div class="marker-red"><?php echo $donate_status; ?></div>
                                <?php } else { ?>
                                    <div class="marker-green"><?php echo $donate_status; ?></div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } else { ?>
            <div class="error">
                <p class='alert alert-warning'> Please sign in to donate.
                    <a href='signin.php' class='alert-link'> Sign In now
                </p>
            </div>
        <?php
    } ?>

        <?php require_once("footer.php"); ?>