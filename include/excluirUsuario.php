<?php
$titulo = "Excluir Usuário";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
    $stmt = $conn->prepare("DELETE FROM xp.usuario
      WHERE hash = ?");
    $stmt->bind_param('s', $_GET["id"]);
    if ($stmt->execute())
      $msg = "Usuário excluído com sucesso!";
    else {
      if ($stmt->errno == 1451)
        $msgErro = "Erro ao excluir Usuário: Existem registros dependentes.";
      else
        $msgErro = "Erro ao excluir Usuário: " . htmlspecialchars($stmt->error);
    }

    include 'include/mensagem.php';
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>