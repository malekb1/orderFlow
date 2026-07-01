<?php
session_start();
require_once __DIR__ . '/includes/connexion.php';
session_destroy();
header('Location: ' . BASE_URL . '/login.php');
exit();
