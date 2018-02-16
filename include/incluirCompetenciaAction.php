<?php
if (!isset($_POST["uo"]) && $_POST["uo"] != "") {
  $msgErro = "O campo Unidade Organizacional é obrigatório.";
}
else if (!isset($_POST["sigla"])) {
  $msgErro = "O campo Sigla é obrigatório.";
}
else if (!isset($_POST["competencia"])) {
  $msgErro = "O campo Nome da Competência é obrigatório.";
}
else if (!isset($_POST["replicar"])) {
  $msgErro = "O campo Replicar é obrigatório.";
}
else if (!isset($_POST["descricao"])) {
  $msgErro = "O campo Descrição é obrigatório.";
}

if (isset($msgErro)) {
  include 'include/incluirCompetencia.php';
} else {
  $sigla = $_POST["sigla"];
  $competencia = $_POST["competencia"];
  $replicar = $_POST["replicar"];
  $descricao = $_POST["descricao"];
  $uoHash = $_POST["uo"];

  $uoId = getId($conn, "uo", $uoHash);

  $stmt = $conn->prepare("INSERT INTO xp.competencia(uo_id, sigla, competencia, replicar, descricao) values(?, ?, ?, ?, ?)");
  $stmt->bind_param('issis', $uoId, $sigla, $competencia, $replicar, $descricao);
  $msg = "";
  $msgErro = "";
  if ($stmt->execute())
    $msg = "Competência incluída com sucesso!";
  else
    $msgErro = "Erro ao incluir Competência: " . htmlspecialchars($stmt->error);

  $titulo = "Incluir Competência";
  include 'include/mensagem.php';
}
?>