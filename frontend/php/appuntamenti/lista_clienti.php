<?php
require_once __DIR__ . '/../../../backend/php/utils/getOrderedCustomer.php';
$clienti = getAppuntamenti();

if (!empty($clienti)) {
    foreach ($clienti as $row) {
        echo "<option value=\"{$row['id']}\">" . htmlspecialchars($row['cliente']) . "</option>";
    }
} else {
    echo "<option disabled>Nessun cliente disponibile</option>";
}
