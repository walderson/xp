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
    $id = getId($conn, "uo", $_GET["id"]);
    $sigla = $_POST["sigla"];
    $nome = $_POST["nome"];
    $ativo = $_POST["ativo"];

    $uoSuperior = NULL;
    if (isset($_POST["uoSuperior"]) && ($_POST["uoSuperior"] != "")) {
      $uoSuperior = getId($conn, "uo", $_POST["uoSuperior"]);
    }
    $gestor = NULL;
    if (isset($_POST["gestor"]) && ($_POST["gestor"] != "")) {
      $gestor = getId($conn, "usuario", $_POST["gestor"]);
    }

    if (!possuiReferenciaCiclica($conn, $id, $uoSuperior)) {
      $stmt = $conn->prepare("UPDATE xp.uo
        SET uo_id = ?, sigla = ?, nome = ?, gestor_id = ?, ativo = ?
        WHERE id = ?");
      $stmt->bind_param('issiii', $uoSuperior, $sigla, $nome, $gestor, $ativo, $id);
      if ($stmt->execute())
        $msg = "Unidade Organizacional alterada com sucesso!";
      else
        $msgErro = "Erro ao alterar Unidade Organizacional: " . htmlspecialchars($stmt->error);

      include 'include/mensagem.php';
    } else {
      $msgErro = "Erro: Referência cíclica não permitida.";
      include 'include/alterarUO.php';
    }
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}

function possuiReferenciaCiclica($conn, $id, $uoId) {
  if ($uoId == null) return false;
  if ($id == $uoId) return true;
  $sql = "SELECT id, uo_id
          FROM
            xp.uo
          WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $uoId);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return possuiReferenciaCiclica($conn, $id, $row["uo_id"]);
}
?>