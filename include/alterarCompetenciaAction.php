<?php
$titulo = "Alterar Competência";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  if (!isset($_POST["uo"]) && $_POST["uo"] != "") {
    $msgErro = "O campo Unidade Organizacional é obrigatório.";
  }
  else if (!isset($_POST["ordem"])) {
    $msgErro = "O campo Ordem é obrigatório.";
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
  else if (!isset($_POST["ativo"])) {
    $msgErro = "O campo Ativo é obrigatório.";
  }
  else if (!isset($_POST["descricao"])) {
    $msgErro = "O campo Descrição é obrigatório.";
  }

  if ($msgErro != "") {
    include 'include/alterarCompetencia.php';
  } else {
    $hash = $_GET["id"];
    $ordem = $_POST["ordem"];
    $sigla = $_POST["sigla"];
    $competencia = $_POST["competencia"];
    $replicar = $_POST["replicar"];
    $ativo = $_POST["ativo"];
    $descricao = $_POST["descricao"];
    $uoHash = $_POST["uo"];

    $uoId = getId($conn, "uo", $uoHash);

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

      // Mantém a ordem dentro dos limites
      if ($ordem < 1) $ordem = 1;
      else {
        $stmt = $conn->prepare("SELECT IFNULL(MAX(ordem), 0) + 1 max FROM xp.competencia WHERE uo_id = ?");
        $stmt->bind_param('i', $uoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $max = $result->fetch_assoc();
        if ($ordem > $max["max"]) $ordem = $max["max"];
      }

      $sql = "UPDATE xp.competencia 
        SET uo_id = ?, ordem = ?, sigla = ?, competencia = ?, replicar = ?, descricao = ?, ativo = ? 
        WHERE hash = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('iissisis', $uoId, $ordem, $sigla, $competencia, $replicar, $descricao, $ativo, $hash);
      if ($stmt->execute()) {
        // Precisa atualizar a ordenação?
        if ($uoId == $ant["uo_id"]) {
          if ($ordem < $ant["ordem"]) {
            $sql = "UPDATE xp.competencia
              SET ordem = ordem + 1
              WHERE id <> ?
                AND uo_id = ?
                AND ordem >= ?
                AND ordem < ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iiii', $ant["id"], $uoId, $ordem, $ant["ordem"]);
            $stmt->execute();
          }
          if ($ordem > $ant["ordem"]) {
            $sql = "UPDATE xp.competencia
              SET ordem = ordem - 1
              WHERE id <> ?
                AND uo_id = ?
                AND ordem <= ?
                AND ordem > ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iiii', $ant["id"], $uoId, $ordem, $ant["ordem"]);
            $stmt->execute();
          }
        } else {
          $sql = "UPDATE xp.competencia
            SET ordem = ordem - 1
            WHERE uo_id = ?
              AND ordem > ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('ii', $ant["uo_id"], $ant["ordem"]);
          $stmt->execute();
          $sql = "UPDATE xp.competencia
            SET ordem = ordem + 1
            WHERE id <> ?
              AND uo_id = ?
              AND ordem >= ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('iii', $ant["id"], $uoId, $ordem);
          $stmt->execute();
        }

        $msg = "Competência alterada com sucesso!";
      } else
        $msgErro = "Erro ao alterar Competência: " . htmlspecialchars($stmt->error);

      include 'include/mensagem.php';
    } else {
      $msgErro = "Erro: Registro informado inexistente: " . $hash;
      include 'include/mensagem.php';
    }
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>