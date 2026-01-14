<?php
$host = 'localhost';
$database = 'airline_travel_company';
$user = 'Hussein';
$pass = 'fourthyear';
$attr = "mysql:host=$host;dbname=$database";
$opts = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>