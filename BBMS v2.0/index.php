<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "Home" ?>
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
    <link rel="stylesheet" href="css/index_style.css">
    <link rel="stylesheet" href="css/alert-display.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--SCRIPT-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
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
    <!--HOME SECTION-->
    <section class="home" id="home">
        <div class="max-width">
            <div class="home-content">
                <!--<form class="search_home" action="search.php" method="GET">
                    <div class="fields">
                        <div class="search">
                            <input type="search" name="search-keyword" placeholder="Search" required>
                        </div>
                        <div class="button">
                            <button type="submit">Search</button>
                        </div>
                    </div>
                </form>-->
                <div class="line-1">Welcome to Dona<span>Blood</span></div>
                <div class="line-2">Let your Blood uphold lives!!</div>
                <a href="donations.php">Donate Now</a>
            </div>
        </div>
    </section>
    <!--ACHIEVEMENTS-->
    <section class="achievements" id="achievements">
        <div class="max-width">
            <h2 class="title">Our Achievements</h2>
            <div class="achieve-content">
                <div class="card">
                    <div class="box">
                        <i class="fa fa-user-plus"></i>
                        <div class="text">Donors</div>
                        <p class="counter">156893</p>
                        <span>+ people</span>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <i class="fa fa-tint"></i>
                        <div class="text">Blood Donated</div>
                        <p class="counter">956289</p>
                        <span>+ litres</span>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <i class="fa fa-heartbeat"></i>
                        <div class="text">Lives Saved</div>
                        <p class="counter">10985</p>
                        <span>+ lives</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="script/app.js"></script>


    <?php require_once("footer.php"); ?>