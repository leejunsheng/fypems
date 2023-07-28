<?php
// To check user are login
session_start();

ob_start(); // Start output buffering

if (!isset($_SESSION["login"])) {
    header("Location: login.php?error=access");
    
}


