<?php
include 'config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM clienti WHERE id_cliente=$id");
header("Location: clienti.php");
?>