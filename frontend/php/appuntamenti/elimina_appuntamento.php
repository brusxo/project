<?php
include 'config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM appuntamenti WHERE id_appuntamento=$id");
header("Location: appuntamenti.php");
?>