<?php
$titulo = "Visualizar Competência";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
    uo.sigla uo_sigla, uo.nome uo_nome, c.ordem, c.sigla, c.competencia, c.replicar, c.ativo, c.descricao
    FROM xp.competencia c
      INNER JOIN xp.uo uo on (c.uo_id = uo.id)
    WHERE c.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td colspan="2">
      <strong>Unidade Organizacional:</strong><br/>
	  <?php echo $row["uo_sigla"]; ?> - <?php echo $row["uo_nome"]; ?>
    </td>
    <td>
      <strong>Ordem:</strong><br/>
      <?php echo $row["ordem"]; ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td width="660">
      <strong>Competência:</strong><br/>
      <?php echo $row["sigla"]; ?> - <?php echo $row["competencia"]; ?>
    </td>
    <td width="170">
      <strong title="Replicar para as Unidades Subordinadas">Replicar:</strong><br/>
      <?php echo $row["replicar"] == 1 ? "Sim" : "Não"; ?>
    </td>
    <td width="170">
      <strong>Status:</strong><br/>
      <?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Descrição:</strong><br/>
      <?php echo nl2br(htmlspecialchars($row["descricao"])); ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
</table>

    </td>
  </tr>
</table>
<?php
  } else {
    $msgErro = "Erro: Registro informado inexistente: " . $_GET["id"];
    include 'include/mensagem.php';
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>