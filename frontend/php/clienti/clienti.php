<?php
include 'config.php';

// Elimina cliente se richiesto
if (isset($_GET['elimina'])) {
    $id = intval($_GET['elimina']);
    $conn->query("DELETE FROM clienti WHERE id = $id");
    header("Location: clienti.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Clienti - Studio Dentistico Brusco</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Gestione Clienti</h1>

    <h2>Elenco Clienti</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Cellulare</th>
        <th>Azioni</th>
      </tr>
      <?php
      $sql = "SELECT id, nome, email, cellulare FROM clienti ORDER BY id";
      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id']}</td>
                  <td>{$row['nome']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['cellulare']}</td>
                  <td>
                    <a href='clienti.php?elimina={$row['id']}' onclick='return confirm(\"Sei sicuro di voler eliminare questo cliente?\")'>Elimina</a>
                  </td>
                </tr>";
      }
      ?>
    </table>

    <h2>Aggiungi Cliente</h2>
    <form action="aggiungi_cliente.php" method="POST" class="form-box">
      <label for="nome">Nome:</label>
      <input type="text" name="nome" id="nome" required><br><br>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required><br><br>

      <label for="cellulare">Cellulare:</label>
      <input type="text" name="cellulare" id="cellulare" required><br><br>

      <button type="submit">Aggiungi</button>
    </form>

    <br>
    <a href="index.php" class="btn-link">Torna alla Home</a>
  </div>
</body>
</html>
