<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "Messages" ?>
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
    <link rel="stylesheet" href="css/message_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--SCRIPT-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
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
    <table class="table-show" width="90%" cellspacing="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Response</th>
                <th>Mark As Read</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_POST['read'])) {
                $ms_status = "replied";
                $ms_state = 1;
                $ms_read = 0;
                $ms_id = $_POST['id'];
                // sql command to select all messages 
                $sql = "UPDATE messages SET ms_read = :ms_read, ms_state = :ms_state, ms_status = :ms_status WHERE ms_id = :ms_id";
                // preparing the sql command
                $stmt = $pdo->prepare($sql);
                // executing the statement by binding variable to named parameter
                $stmt->execute([
                    ':ms_read' => $ms_read,
                    ':ms_state' => $ms_state,
                    ':ms_status' => $ms_status,
                    ':ms_id' => $ms_id
                ]);
            }
            ?>
            <?php
            // sql command to select all messages 
            $sql = "SELECT * FROM messages WHERE ms_email = :ms_email";
            // preparing the sql command
            $stmt = $pdo->prepare($sql);
            // executing the statement by binding variable to named parameter
            $stmt->execute([
                ':ms_email' => $user_email
            ]);

            if ($count >= 1) {
                // we fetch the user details as an associative array from messages table as $stmt dealt with messages table
                echo "<p class='alert alert-success'> If you have read all messages. <a href='message.php' class='alert-link'>Click here to see changes</a></p>";
                while ($ms = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $ms_id = $ms['ms_id'];
                    $ms_username = $ms['ms_username'];
                    $ms_email = $ms['ms_email'];
                    $ms_subject = $ms['ms_subject'];
                    $ms_detail = $ms['ms_detail'];
                    $ms_date = $ms['ms_date'];
                    $ms_status = $ms['ms_status'];
                    $ms_state = $ms['ms_state'];
                    $ms_reply = $ms['ms_reply'];
                    $ms_read = $ms['ms_read']; ?>

                    <tr>
                        <td><?php echo $ms_id; ?></td>
                        <td><?php echo $ms_username; ?></td>
                        <td><?php echo $ms_email; ?></td>
                        <td><?php echo $ms_subject; ?></td>
                        <td><?php echo $ms_detail; ?></td>
                        <td><?php echo $ms_date; ?></td>
                        <td><?php if ($ms_status == "pending") { ?>
                                <div class="marker-red"><?php echo $ms_status; ?></div>
                            <?php } else { ?>
                                <div class="marker-green"><?php echo $ms_status; ?></div>
                            <?php } ?>
                        </td>
                        <td><?php echo $ms_reply; ?></td>
                        <td>
                            <?php
                            // to show mark as read buttons based on if the message is sent and message is read
                            if ($ms_state == 1 && $ms_read == 1) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <button name="read" type="submit"><span class="fa fa-envelope-o"></span></button>
                                </form>
                            <?php } else if ($ms_state == 1 && $ms_read == 0) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <button name="read" type="submit"><span class="fa fa-check"></span></button>
                                </form>
                            <?php } else if ($ms_state == 0 && $ms_read == 1) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <span class="fa fa-minus"></span>
                                </form>
                            <?php } else if ($ms_state == 0 && $ms_read == 0) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <span class="fa fa-minus"></span>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                }
            } else {
                echo "<p class='alert alert-warning'> You have no new messages</p>";
                // we fetch the user details as an associative array from messages table as $stmt dealt with messages table
                while ($ms = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $ms_id = $ms['ms_id'];
                    $ms_username = $ms['ms_username'];
                    $ms_email = $ms['ms_email'];
                    $ms_subject = $ms['ms_subject'];
                    $ms_detail = $ms['ms_detail'];
                    $ms_date = $ms['ms_date'];
                    $ms_status = $ms['ms_status'];
                    $ms_state = $ms['ms_state'];
                    $ms_reply = $ms['ms_reply'];
                    $ms_read = $ms['ms_read']; ?>

                    <tr>
                        <td><?php echo $ms_id; ?></td>
                        <td><?php echo $ms_username; ?></td>
                        <td><?php echo $ms_email; ?></td>
                        <td><?php echo $ms_subject; ?></td>
                        <td><?php echo $ms_detail; ?></td>
                        <td><?php echo $ms_date; ?></td>
                        <td><?php if ($ms_status == "pending") { ?>
                                <div class="marker-red"><?php echo $ms_status; ?></div>
                            <?php } else { ?>
                                <div class="marker-green"><?php echo $ms_status; ?></div>
                            <?php } ?>
                        </td>
                        <td><?php echo $ms_reply; ?></td>
                        <td>
                            <?php
                            if ($ms_state == 1 && $ms_read == 1) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <button name="read" type="submit"><span class="fa fa-envelope-o"></span></button>
                                </form>
                            <?php } else if ($ms_state == 1 && $ms_read == 0) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <button name="read" type="submit"><span class="fa fa-check"></span></button>
                                </form>
                            <?php } else if ($ms_state == 0 && $ms_read == 1) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <span class="fa fa-minus"></span>
                                </form>
                            <?php } else if ($ms_state == 0 && $ms_read == 0) { ?>
                                <form action="message.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $ms_id; ?>">
                                    <span class="fa fa-minus"></span>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php require_once("footer.php"); ?>