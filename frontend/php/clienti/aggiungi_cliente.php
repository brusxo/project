<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cellulare = $_POST["cellulare"];

    $statement = $conn->prepare("INSERT INTO clienti (nome, email, cellulare) VALUES (?, ?, ?)");
    $statement->bind_param("sss", $nome, $email, $cellulare);

    if ($statement->execute()) {
        header("Location: clienti.php");
        exit();
    } else {
        echo "Errore durante l'aggiunta del cliente: " . $conn->error;
    }
}
?>
