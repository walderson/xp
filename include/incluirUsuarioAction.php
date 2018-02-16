<?php
if (!isset($_POST["login"])) {
  $msgErro = "O campo Login é obrigatório.";
}
else if (!isset($_POST["nome"])) {
  $msgErro = "O campo Nome é obrigatório.";
}
else if (!isset($_POST["senha"])) {
  $msgErro = "O campo Senha é obrigatório.";
}
else if (!isset($_POST["administrador"])) {
  $msgErro = "O campo Administrador é obrigatório.";
}
else if (!isset($_POST["uo"]) && $_POST["uo"] != "") {
  $msgErro = "O campo Unidade Organizacional é obrigatório.";
}

if (isset($msgErro)) {
  include 'include/incluirUsuario.php';
} else {
  $login = $_POST["login"];
  $nome = $_POST["nome"];
  $senha = $_POST["senha"];
  $administrador = $_POST["administrador"];
  $uoHash = $_POST["uo"];

  $uoId = getId($conn, "uo", $uoHash);

  $stmt = $conn->prepare("INSERT INTO xp.usuario(login, nome, senha, administrador, uo_id) values(?, ?, ?, ?, ?)");
  $stmt->bind_param('sssii', $login, $nome, $senha, $administrador, $uoId);
  $msg = "";
  $msgErro = "";
  if ($stmt->execute())
    $msg = "Usuário incluído com sucesso!";
  else
    $msgErro = "Erro ao incluir Usuário: " . htmlspecialchars($stmt->error);

  $titulo = "Incluir Usuário";
  include 'include/mensagem.php';
}
?>