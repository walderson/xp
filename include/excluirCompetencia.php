<?php
$titulo = "Excluir Competência";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];

  // Obtém a versão anterior da Competência
  $sql = "SELECT
    c.id, c.uo_id, c.ordem
    FROM xp.competencia c
    WHERE c.hash=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $ant = $result->fetch_assoc();

    $stmt = $conn->prepare("DELETE FROM xp.competencia
      WHERE hash = ?");
    $stmt->bind_param('s', $hash);
    if ($stmt->execute()) {
      $sql = "UPDATE xp.competencia
        SET ordem = ordem - 1
        WHERE uo_id = ?
          AND ordem > ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('ii', $ant["uo_id"], $ant["ordem"]);
      $stmt->execute();

      $msg = "Competência excluída com sucesso!";
    } else {
      if ($stmt->errno == 1451)
        $msgErro = "Erro ao excluir Competência: Existem registros dependentes.";
      else
        $msgErro = "Erro ao excluir Competência: " . htmlspecialchars($stmt->error);
    }

    include 'include/mensagem.php';
  } else {
    $msgErro = "Erro: Registro informado inexistente: " . $hash;
    include 'include/mensagem.php';
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>