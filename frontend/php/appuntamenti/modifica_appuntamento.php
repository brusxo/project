<?php
include 'config.php';
$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE appuntamenti SET id_cliente=?, data_appuntamento=?, ora=?, descrizione=? WHERE id_appuntamento=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $_POST['id_cliente'], $_POST['data'], $_POST['ora'], $_POST['descrizione'], $id);
    $stmt->execute();
    header("Location: appuntamenti.php");
}
$appuntamento = $conn->query("SELECT * FROM appuntamenti WHERE id_appuntamento=$id")->fetch_assoc();
$clienti = $conn->query("SELECT * FROM clienti");
?>
<form method="post">
    Cliente: <select name="id_cliente">
        <?php while ($c = $clienti->fetch_assoc()) { ?>
            <option value="<?= $c['id_cliente'] ?>" <?= $c['id_cliente'] == $appuntamento['id_cliente'] ? 'selected' : '' ?>>
                <?= $c['nome'] . " " . $c['cognome'] ?>
            </option>
        <?php } ?>
    </select><br>
    Data: <input type="date" name="data" value="<?= $appuntamento['data_appuntamento'] ?>"><br>
    Ora: <input type="time" name="ora" value="<?= $appuntamento['ora'] ?>"><br>
    Descrizione: <input name="descrizione" value="<?= $appuntamento['descrizione'] ?>"><br>
    <input type="submit" value="Modifica">
</form>