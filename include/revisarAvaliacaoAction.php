<?php
$titulo = "Revisar Avaliação";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.id, a.data_avaliacao, a.data_revisao
    FROM xp.avaliacao a
    WHERE a.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["data_avaliacao"] != null) {
      if ($row["data_revisao"] == null) {
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
              $msgErro = "Erro ao revisar Autoavaliação: " . htmlspecialchars($stmt->error);
          }
        }

        if ($msgErro == "") {
          $stmt = $conn->prepare("UPDATE xp.avaliacao
            SET revisor_id = ?, data_revisao = NOW(), comentario_revisor = ?
            WHERE hash = ?");
          $stmt->bind_param('iss', $_SESSION["usuarioId"], $_POST["comentario"], $hash);
          if ($stmt->execute()) {
            $msg = nl2br("Avaliação revisada com sucesso.");
          }
          else
            $msgErro = "Erro ao revisar Autoavaliação: " . htmlspecialchars($stmt->error);
        }
        include 'include/mensagem.php';
      } else {
          include 'include/visualizarAvaliacao.php';
      }
    } else {
      $msgErro = "Erro: Avaliação ainda não preenchida pelo colaborador.";
      include 'include/mensagem.php';
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