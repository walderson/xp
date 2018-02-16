<?php
$titulo = "Alterar Usuário";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  if (!isset($_POST["nome"])) {
    $msgErro = "O campo Nome é obrigatório.";
  }
  else if (!isset($_POST["administrador"])) {
    $msgErro = "O campo Administrador é obrigatório.";
  }
  else if (!isset($_POST["uo"]) && $_POST["uo"] != "") {
    $msgErro = "O campo Unidade Organizacional é obrigatório.";
  }
  else if (!isset($_POST["ativo"])) {
    $msgErro = "O campo Ativo é obrigatório.";
  }

  if ($msgErro != "") {
    include 'include/alterarUsuario.php';
  } else {
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];
    $administrador = $_POST["administrador"];
    $uoHash = $_POST["uo"];
    $ativo = $_POST["ativo"];

    $uoId = getId($conn, "uo", $uoHash);

    $sql = "UPDATE xp.usuario ";
    if (isset($senha) && ($senha != null) && ($senha != ""))
      $sql .= "SET nome = ?, senha = ?, administrador = ?, uo_id = ?, ativo = ? ";
    else
      $sql .= "SET nome = ?, administrador = ?, uo_id = ?, ativo = ? ";
    $sql .= "WHERE hash = ?";
    $stmt = $conn->prepare($sql);
    if (isset($senha) && ($senha != null) && ($senha != ""))
      $stmt->bind_param('ssiiis', $nome, $senha, $administrador, $uoId, $ativo, $_GET["id"]);
    else
      $stmt->bind_param('siiis', $nome, $administrador, $uoId, $ativo, $_GET["id"]);
    if ($stmt->execute())
      $msg = "Usuário alterado com sucesso!";
    else
      $msgErro = "Erro ao alterar Usuário: " . htmlspecialchars($stmt->error);

    include 'include/mensagem.php';
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>