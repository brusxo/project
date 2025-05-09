<?php
// Includi il file di configurazione per la connessione al database
require_once __DIR__ . '/../../../backend/config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica che la connessione sia stata stabilita
    if ($pdo) {
        try {
            $cliente = $_POST['cliente'];
            $data = $_POST['data'];
            $ora = $_POST['ora'];

            // Prepara la query
            $stmt = $pdo->prepare("INSERT INTO appuntamenti (cliente, data, ora) VALUES (:cliente, :data, :ora)");
            $stmt->bindParam(':cliente', $cliente);
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':ora', $ora);

            // Esegui la query
            $stmt->execute();
            echo "Appuntamento aggiunto con successo!";
        } catch (PDOException $e) {
            echo "Errore durante l'inserimento dell'appuntamento: " . $e->getMessage();
        }
    } else {
        echo "Errore nella connessione al database.";
    }
}
?>
