<?php
// if SESSION name is 'login' then we show sign out button with user_name
if (isset($_SESSION['login'])) {
?>
    <?php
    // getting session user_id to put profile photo
    $user_id = $_SESSION['user_id'];
    // selecting from database the signed in user's id
    // sql command to select only users that user_id = $user_id
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    // preparing the sql command
    $stmt = $pdo->prepare($sql);
    // executing the statement by binding variable to named parameter
    $stmt->execute([
        ':user_id' => $user_id
    ]);
    // we fetch the user photo as an associative array from users table as $stmt dealt with users table
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // get user id , user photo and store it in cookie
    $user_id = $user['user_id'];
    $user_photo = $user['user_photo'];
    $user_email = $user['user_email'];
    ?>
    <div class="dashboard">
        <button class="notification">
            <?php
            // checking in database if there already exists messages
            // sql command to select only messages that user_email = $user_email and state ,read, status
            $sql = "SELECT * FROM messages WHERE ms_email = :ms_email AND ms_read = :ms_read AND ms_state = :ms_state AND ms_status = :ms_status";
            // preparing the sql command
            $stmt = $pdo->prepare($sql);
            // executing the statement by binding variable to named parameter
            $stmt->execute([
                ':ms_email' => $user_email,
                ':ms_read' => 1,
                ':ms_state' => 1,
                ':ms_status' => "replied"
            ]);
            // here rowCount() returns no. of rows in table affected by last sql statement
            $count = $stmt->rowCount();
            ?>
            <span class="fa fa-envelope-o"></span>
            <span class='badge'> <?php echo $count ?> </span>
        </button>
        <div class="messages">
            <h6>Message Notifications</h6>
            <?php
            if ($count >= 1) { // if rowcount >= 1 we fetch the messages
                if ($messages = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $ms_reply = $messages['ms_reply'];
                    $ms_date = $messages['ms_date']; ?>
                    <a href="message.php">
                        <div class=" message">
                            <span class="fa fa-envelope-o"></span>
                            <?php echo $ms_reply; ?>
                        </div>
                        <div class="messaged-by">
                            Admin • <?php echo $ms_date; ?>
                        </div>
                    </a>
                <?php
                }
                ?>
                <a class="read-all" href="message.php">Read all messages...</a>
            <?php } else { ?>
                <a href="message.php">
                    <div class="message">
                        No new messages
                    </div>
                </a>
            <?php
            }
            ?>
        </div>
        <button class="profile">
            <div class="profile-photo"><img src="images/<?php echo $user_photo; ?>" alt="user_photo" width="35" height="35"></div>
            <div class="profile-user"><?php echo $_SESSION['user_name']; ?><span class="fa fa-caret-down"></span></div>
        </button>
        <div class="wrapper">
            <ul class="contents">
                <h6>My Dashboard</h6>
                <a href="profile.php">
                    <li>
                        <div class="icon"><span class="fa fa-user"></span></div>My Profile
                    </li>
                </a>
                <a href="donations.php">
                    <li>
                        <div class="icon"><span class="fa fa-user-plus"></span></div>My Donations
                    </li>
                </a>
                <a href="signout.php">
                    <li>
                        <div class="icon"><span class="fa fa-sign-out"></span></div>Sign Out
                    </li>
                </a>
            </ul>
        </div>
    </div>
<?php
} else {
    // if cookie id is not set then it will go back to sign in and sign up or else till
    // the expiry of cookie which we set it to 24hrs, the log out button will be shown
    if (!isset($_COOKIE['_uid_']) && !isset($_COOKIE['_unn_'])) {
        echo '<li><a class="btn-crimson" href="signin.php">SIGN IN</a></li>';
        echo '<li><a class="btn-crimson" href="signup.php">SIGN UP</a></li>';
    } else {
        // here we are checking if the same user had come
        // since we encoded the user_id and user_nicknamefor security we need to decode it
        $user_id = base64_decode($_COOKIE['_uid_']);
        $user_nickname = base64_decode($_COOKIE['_unn_']);

        // we are binding the COOKIE id and nickname to see which user had come
        // sql command to select only id wher user_id = $user_id and user_nickname = $user_nickname
        $sql = "SELECT * FROM users WHERE user_id = :user_id AND user_nickname = :user_nickname";
        // preparing the sql command
        $stmt = $pdo->prepare($sql);
        // executing the statement by binding variable to named parameter
        $stmt->execute([
            ':user_id' => $user_id,
            ':user_nickname' => $user_nickname
        ]);
        // we fetch the user details as an associative array from users table as $stmt dealt with users table
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // we store the user_name into a variable
        $user_name = $user['user_name'];
        // then we show back the $user_name of the user who had logged in
        echo '<form class="log_out" action="signout.php" method="post">
                <button class="btn-log">LOG OUT (<?php echo {$user_name}; ?>)</button>
                </form>';
    }
}
?>