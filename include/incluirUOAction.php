<?php
if (!isset($_POST["sigla"])) {
  $msgErro = "O campo Sigla é obrigatório.";
}
else if (!isset($_POST["nome"])) {
  $msgErro = "O campo Nome é obrigatório.";
}

if (isset($msgErro)) {
  include 'include/incluirUO.php';
} else {
  $sigla = $_POST["sigla"];
  $nome = $_POST["nome"];

  $uoSuperior = NULL;
  if (isset($_POST["uoSuperior"]) && ($_POST["uoSuperior"] != "")) {
    $uoSuperior = getId($conn, "uo", $_POST["uoSuperior"]);
  }

  $stmt = $conn->prepare("INSERT INTO xp.uo(uo_id, sigla, nome) values(?, ?, ?)");
  $stmt->bind_param('iss', $uoSuperior, $sigla, $nome);
  $msg = "";
  $msgErro = "";
  if ($stmt->execute())
    $msg = "Unidade Organizacional incluída com sucesso!";
  else
    $msgErro = "Erro ao incluir Unidade Organizacional: " . htmlspecialchars($stmt->error);

  $titulo = "Incluir Unidade Organizacional";
  include 'include/mensagem.php';
}
?>