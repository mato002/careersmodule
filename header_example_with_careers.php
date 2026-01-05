<?php
/**
 * Example header.php with Careers link added
 * This is a template - adapt it to match your actual header.php structure
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "Prady Technologies"; ?></title>
    <!-- Your existing CSS links here -->
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="/">Prady Technologies</a>
            </div>
            <ul class="navbar-nav">
                <li><a href="/">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="/careers">Careers</a></li>  <!-- ADD THIS LINE -->
                <li><a href="#clients">Our Clients</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
        </div>
    </nav>

    <!-- Alternative: If your navigation is simpler, just add: -->
    <!-- 
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="about.php">About Us</a>
        <a href="services.php">Services</a>
        <a href="/careers">Careers</a>  <!-- ADD THIS -->
        <a href="#clients">Our Clients</a>
        <a href="#contact">Contact Us</a>
    </div>
    -->


