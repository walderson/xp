<?php
$titulo = "Revisar Avaliação";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.id, a.data_avaliacao, a.data_revisao, a.comentario_colaborador
    FROM xp.avaliacao a
      INNER JOIN xp.usuario u on (a.usuario_id = u.id)
    WHERE a.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["data_avaliacao"] != null) {
      if ($row["data_revisao"] == null) {
        $dataAvaliacao = date('d/m/Y H:i:s', strtotime($row["data_avaliacao"]));
        $comentarioColaborador = $row["comentario_colaborador"];
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php include 'include/menu.php'; ?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=revisarAvaliacaoAction&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return confirma();">
<table border="0" width="1000">
  <tr>
    <th colspan="2"><h2><?php echo $titulo; ?></h2></th>
  </tr>
<?php
        //Consulta as questões da avaliação
        $i = 0;
        $sql = "SELECT
          ac.hash, c.competencia, c.descricao, ac.nivel
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
    <td width="60">
      <select name="<?php echo $row["hash"]; ?>" required>
        <option value="" title="Selecione uma das opções abaixo"></option>
<?php
  switch($row["nivel"]) {
    case 1: ?>
        <option value="1" title="Nível 1: Pouco conhecimento ou nenhum" selected>★</option>
        <option value="2" title="Nível 2: Bom conhecimento">★★</option>
        <option value="3" title="Nível 3: Expert, possuindo domínio">★★★</option>
<?php
      break;
    case 2: ?>
        <option value="1" title="Nível 1: Pouco conhecimento ou nenhum">★</option>
        <option value="2" title="Nível 2: Bom conhecimento" selected>★★</option>
        <option value="3" title="Nível 3: Expert, possuindo domínio">★★★</option>
<?php
      break;
    case 3: ?>
        <option value="1" title="Nível 1: Pouco conhecimento ou nenhum">★</option>
        <option value="2" title="Nível 2: Bom conhecimento">★★</option>
        <option value="3" title="Nível 3: Expert, possuindo domínio" selected>★★★</option>
<?php
  }
?>
      </select>
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
  <tr class="avaliacao">
    <td colspan="2" style="border-bottom: 1px solid #888;">
      <strong>Comentários e Observações</strong>
    </td>
  </tr>
  <tr>
    <td colspan="2">
	  <textarea cols="120" name="comentario"
                placeholder="Preencha este campo com os comentários extras, obtidas durante a revisão feita junto ao colaborador.

Por Exemplo, se houve a necessidade de esclarecer alguma dúvida, ou se o colaborador ressaltou alguma necessidade, ou ainda se foi necessário classificar o nível de algum item acima." required rows="5"></textarea></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
      <input type="submit" name="acao" value="   Revisar Avaliação   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.getElementsByTagName("select")[0].focus();

  function confirma() {
    if (!window.confirm("Confirma a revisão desta avaliação?")) return false;
    return true;
  }
</script>

    </td>
  </tr>
</table>
<?php
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