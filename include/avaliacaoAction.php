<?php
$titulo = "Autoavaliação: Conhecimentos e Habilidades";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.id, a.usuario_id, a.data_avaliacao, a.data_revisao
    FROM xp.avaliacao a
    WHERE a.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["data_avaliacao"] == null) {
      $usuarioId = $row["usuario_id"];
      $sql = "SELECT
          ac.hash
        FROM xp.avaliacao_competencia ac
        WHERE ac.avaliacao_id = ?
        ORDER BY ac.id";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i', $row["id"]);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE xp.avaliacao_competencia
          SET nivel = ?
          WHERE hash = ?");
        while (($row = $result->fetch_assoc()) && ($msgErro == "")) {
          $stmt->bind_param('is', $_POST[$row["hash"]], $row["hash"]);
          if (!$stmt->execute())
            $msgErro = "Erro ao salvar Autoavaliação: " . htmlspecialchars($stmt->error);
        }
      }

      if ($msgErro == "") {
        $stmt = $conn->prepare("UPDATE xp.avaliacao
          SET data_avaliacao = NOW(), comentario_colaborador = ?
          WHERE hash = ?");
        $stmt->bind_param('ss', $_POST["comentario"], $hash);
        if ($stmt->execute()) {
          $redefinirSenha = md5(round(microtime(true) * 1000) . "+" . mt_rand());
          $stmt = $conn->prepare("UPDATE xp.usuario
            SET redefinir_senha = ?
            WHERE id = ?");
          $stmt->bind_param('si', $redefinirSenha, $usuarioId);
		  $stmt->execute();

          $msg = nl2br("Avaliação salva com sucesso.
            
            Para consultar o histórico de avaliações que participou, <a href=\"?operacao=login\">faça o login</a>.
            
            <strong>Obs.:</strong> Se necessário, <a href=\"?operacao=redefinirSenha&id=" . $redefinirSenha
            . "\">clique aqui</a> para redefinir a sua senha.");
        }
        else
          $msgErro = "Erro ao salvar Autoavaliação: " . htmlspecialchars($stmt->error);
      }
      include 'include/mensagem.php';
    } else {
      if (isset($_SESSION['administrador']) && $_SESSION['administrador'] == 1)
        if ($row["data_revisao"] == null)
          include 'include/revisarAvaliacao.php';
        else
          include 'include/visualizarAvaliacao.php';
      else
        include 'include/visualizarAvaliacao.php';
    }
  } else {
    $msgErro = "Erro: Registro informado inexistente: " . $_GET["id"];
    include 'include/mensagem.php';
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>