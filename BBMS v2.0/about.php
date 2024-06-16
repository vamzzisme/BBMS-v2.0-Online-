<!--starting session-->
<?php session_start(); ?>
<?php $current_page = "About" ?>
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
    <link rel="stylesheet" href="css/about_style.css">
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
            <li><a id="about_focus" href="about.php">About</a></li>
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
    <div class="max-width">
        <div class="mission">
            <span>Our Mission</span>
            <p>We here at DonaBlood always take care and loo after our patient's satisfaction and safety. There is no question of unhygiene methods of treatment in DonaBlood. We carefull, ensuring safety of donar and patient, draw our the blood and serve millions of people who are in need of Blood out there. You can ensure the following from our services:
            <ul>
                <li>Hygiene</li>
                <li>Safety</li>
                <li>Patient's Satisfaction</li>
                <li>Truthful service</li>
                <li>24x7 'May I Help You?'</li>
                <li>Affordable prices</li>
            </ul>
            We hereafter and forever ,justlike before, continue to serve you with our greatest pleasure and strive to maintain our standards to satisfy out you divine donors.
            </p>
        </div>
        <div class="vision">
            <span>Our Vision</span>
            <p>We here at DonaBlood, want to serve those who are very in need of blood. We encourage donations of all types of blood and secure them in our highest apparatus. Our aim is to reduce the ration of people who are suffering from various blood diseases by providing them blood that suits their metobolism at affordable costs. We day to day try to minimise our prices and are trying to pioneer for providing service at reasonable cost. We here are veterans in blood transplantation and in ensuring safety of patients in the process of doing so. We provide post-donation facilities for those who become trivial after donation of blood in quantities by giving energized drinks and help them cope up with increasing their donated blood content. We also are figuring out to introduce offers like other metropolitan corps to enhance donations and to rise the number of donations and acceptions.</p>
        </div>
        <div class="future">
            <span>Future Goals</span>
            <p>We here at DonaBlood, have highly qualified employees with great knowledge in their duties. we hae at present around 300+ camps running around the nation. We are trying to enhance our offices to international level and want to hire more workers. We are trying to enchance our cleaning methods and hygiene techniques to maintain the highest standards. We are being the top corp having processing blood through heavy population. We are making every thing digital with the help of top-notch software engineers that ensure measure of data. We are being more interacting with people in mega cities, especially in Bangalore, Mumbai, Chennai, Hyderabad, Kolkata and Delhi. We truely appreciate you support and try to expand out bases to meet large connections. Hope we get the same support hereafter like before.</p>
        </div>
        <a class="about_btn" href="donations.php">Donate Now</a>
    </div>

    <?php require_once("footer.php"); ?>