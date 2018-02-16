<?php
$titulo = "Excluir Unidade Organizacional";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
    $stmt = $conn->prepare("DELETE FROM xp.uo
      WHERE hash = ?");
    $stmt->bind_param('s', $_GET["id"]);
    if ($stmt->execute())
      $msg = "Unidade Organizacional excluída com sucesso!";
    else {
      if ($stmt->errno == 1451)
        $msgErro = "Erro ao excluir Unidade Organizacional: Existem registros dependentes.";
      else
        $msgErro = "Erro ao excluir Unidade Organizacional: " . htmlspecialchars($stmt->error);
    }

    include 'include/mensagem.php';
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>