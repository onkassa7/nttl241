<?php
require_once __DIR__ . '/../config.php';
unset($_SESSION['nttl_admin']);
session_destroy();
header('Location: index.php');
exit;
