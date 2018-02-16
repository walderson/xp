<?php
$titulo = "Autoavaliação: Conhecimentos e Habilidades";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.id, data_avaliacao, a.comentario_colaborador, r.nome revisor, a.data_revisao, a.comentario_revisor
    FROM xp.avaliacao a
      INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
      LEFT JOIN xp.usuario r ON (a.revisor_id = r.id)
    WHERE a.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dataAvaliacao = date('d/m/Y H:i:s', strtotime($row["data_avaliacao"]));
    $comentarioColaborador = $row["comentario_colaborador"];
    $revisor = $row["revisor"];
    $dataRevisao = date('d/m/Y H:i:s', strtotime($row["data_revisao"]));
    $comentarioRevisor = $row["comentario_revisor"];
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
if(isset($_SESSION['usuario'])) {
  include 'include/menu.php';
} else echo "&nbsp;";
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="2"><h2><?php echo $titulo; ?></h2></th>
  </tr>
<?php
      //Consulta as questões da avaliação
      $i = 0;
      $sql = "SELECT
        c.competencia, c.descricao, ac.nivel
        FROM xp.avaliacao_competencia ac
        INNER JOIN xp.competencia c ON (ac.competencia_id = c.id)
        WHERE ac.avaliacao_id = ?
        ORDER BY ac.id";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i', $row["id"]);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
?>
  <tr class="avaliacao">
    <td width="940" style="border-bottom: 1px solid #888;">
      <strong><?php echo ++$i; ?>: <?php echo $row["competencia"]; ?></strong>
    </td>
    <td width="60" style="border-bottom: 1px solid #888; text-align: center;">
<?php
  switch($row["nivel"]) {
    case 1: ?><div title="Nível 1: Pouco conhecimento ou nenhum">★</div>
<?php
      break;
    case 2: ?><div title="Nível 2: Bom conhecimento">★★</div>
<?php
      break;
    case 3: ?><div title="Nível 3: Expert, possuindo domínio">★★★</div>
<?php
  }
?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo nl2br($row["descricao"]); ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
        }
      }
?>
<?php
  if ($dataAvaliacao != null) {
?>
  <tr class="avaliacao">
    <td colspan="2" style="border-bottom: 1px solid #888;">
      <strong>Data/Hora de Envio da Avaliação:</strong>
	  <?php echo $dataAvaliacao; ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="avaliacao">
    <td colspan="2" style="border-bottom: 1px solid #888;">
      <strong>Comentários, Sugestões e Críticas do Colaborador</strong>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo nl2br($comentarioColaborador); ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
  }
?>
<?php
  if ($revisor != null) {
?>
  <tr class="avaliacao">
    <td colspan="2" style="border-bottom: 1px solid #888;">
      <strong>Revisor:</strong>
	  <?php echo $revisor; ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="avaliacao">
    <td colspan="2" style="border-bottom: 1px solid #888;">
      <strong>Data/Hora da Revisão:</strong>
	  <?php echo $dataRevisao; ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="avaliacao">
    <td colspan="2" style="border-bottom: 1px solid #888;">
      <strong>Comentários e Observações do Revisor</strong>
    </td>
  </tr>
  <tr>
    <td colspan="2"><?php echo nl2br($comentarioRevisor); ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
  }
?>
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