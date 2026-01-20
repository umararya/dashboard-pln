<?php
/**
 * Logout Handler
 * Path: auth/logout.php
 */

session_start();
session_destroy();
header('Location: login.php');
exit;