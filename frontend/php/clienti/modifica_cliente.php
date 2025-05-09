<?php
include 'config.php';
$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE clienti SET nome=?, cognome=?, telefono=?, email=? WHERE id_cliente=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $_POST['nome'], $_POST['cognome'], $_POST['telefono'], $_POST['email'], $id);
    $stmt->execute();
    header("Location: clienti.php");
}
$result = $conn->query("SELECT * FROM clienti WHERE id_cliente=$id");
$cliente = $result->fetch_assoc();
?>
<form method="post">
    Nome: <input name="nome" value="<?= $cliente['nome'] ?>"><br>
    Cognome: <input name="cognome" value="<?= $cliente['cognome'] ?>"><br>
    Telefono: <input name="telefono" value="<?= $cliente['telefono'] ?>"><br>
    Email: <input name="email" value="<?= $cliente['email'] ?>"><br>
    <input type="submit" value="Modifica">
</form>