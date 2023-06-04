<?php
// To check user are login
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php?error=access");
}
