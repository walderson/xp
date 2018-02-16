<?php
$titulo = "Alterar Unidade Organizacional";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  if (!isset($_POST["sigla"])) {
    $msgErro = "O campo Sigla é obrigatório.";
  }
  else if (!isset($_POST["nome"])) {
    $msgErro = "O campo Nome é obrigatório.";
  }

  if ($msgErro != "") {
    include 'include/alterarUO.php';
  } else {
    $sigla = $_POST["sigla"];
    $nome = $_POST["nome"];
    $ativo = $_POST["ativo"];

    $uoSuperior = NULL;
    if (isset($_POST["uoSuperior"]) && ($_POST["uoSuperior"] != "")) {
      $uoSuperior = getId($conn, "uo", $_POST["uoSuperior"]);
    }

    $stmt = $conn->prepare("UPDATE xp.uo
      SET uo_id = ?, sigla = ?, nome = ?, ativo = ?
      WHERE hash = ?");
    $stmt->bind_param('issis', $uoSuperior, $sigla, $nome, $ativo, $_GET["id"]);
    if ($stmt->execute())
      $msg = "Unidade Organizacional alterada com sucesso!";
    else
      $msgErro = "Erro ao alterar Unidade Organizacional: " . htmlspecialchars($stmt->error);

    include 'include/mensagem.php';
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>