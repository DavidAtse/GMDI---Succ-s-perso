<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS gmdi_communication CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "OK: Base de donnees gmdi_communication creee ou deja existante\n";
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Verifiez que MySQL est actif (XAMPP Control Panel)\n";
    exit(1);
}
