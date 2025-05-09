<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../query/index.php';

if(!$pdo) {
    return;
}

function getAppuntamenti() {
    global $pdo;
    try {
        $stmt = $pdo->query(ORDERED_CUSTOMER_QUERY);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore nella query degli appuntamenti: " . $e->getMessage());
        return [];
    }
}