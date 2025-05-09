<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Appuntamenti - Studio Dentistico Brusco</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <div class="container">
        <h1>Gestione Appuntamenti</h1>

        <h2>Elenco Appuntamenti</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Ora</th>
            </tr>
            <?php
            // Elenco degli appuntamenti (output PHP)
            ?>
        </table>

        <h2>Aggiungi Appuntamento</h2>
        <form action="./aggiungi_appuntamento.php" method="POST" class="form-box">
            <label for="cliente">Cliente:</label>
            <select name="cliente" id="cliente" required>
                <?php require_once __DIR__ . '/./lista_clienti.php'; ?>
            </select><br><br>

            <label for="data">Data:</label>
            <input type="text" name="data" id="data" required><br><br>

            <label for="ora">Ora:</label>
            <input type="time" name="ora" id="ora" required><br><br>

            <button type="submit">Aggiungi</button>
        </form>

        <br>
        <div class="bottoni">
            <a class="btn-home" href="">🏠 Torna alla Home</a>
        </div>
    </div>

    <script>
        flatpickr("#data", {
            dateFormat: "Y-m-d",
            locale: "it"
        });
    </script>
</body>

</html>
