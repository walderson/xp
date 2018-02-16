<?php
$titulo = "Autoavaliação: Conhecimentos e Habilidades";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.id, a.data_avaliacao, a.data_revisao
    FROM xp.avaliacao a
      INNER JOIN xp.usuario u on (a.usuario_id = u.id)
    WHERE a.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["data_avaliacao"] == null) {
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
if(isset($_SESSION['usuario'])) {
  include 'include/menu.php';
} else echo "&nbsp;";
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=avaliacaoAction&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return confirma();">
<table border="0" width="1000">
  <tr>
    <th colspan="2"><h2><?php echo $titulo; ?></h2></th>
  </tr>
<?php
      //Consulta as questões da avaliação
      $i = 0;
      $sql = "SELECT
        ac.hash, c.competencia, c.descricao
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
        <option value="1" title="Nível 1: Pouco conhecimento ou nenhum">★</option>
        <option value="2" title="Nível 2: Bom conhecimento">★★</option>
        <option value="3" title="Nível 3: Expert, possuindo domínio">★★★</option>
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
      <strong>Comentários, sugestões e críticas</strong>
    </td>
  </tr>
  <tr>
    <td colspan="2">
	  <textarea cols="120" name="comentario"
                placeholder="A sua contribuição é muito importante, inclua comentários, sugestões e críticas aqui para ajudar a melhorar o nosso ambiente tecnológico, incluindo a rotina diária.

Sentiu falta de alguma habilidade/conhecimento que não consta acima? Por favor, comente." required rows="5"></textarea></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
      <input type="submit" name="acao" value="   Enviar Autoavaliação   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.getElementsByTagName("select")[0].focus();

  function confirma() {
    if (!window.confirm("Confirma o envio desta autoavaliação?")) return false;
    return true;
  }
</script>

    </td>
  </tr>
</table>
<?php
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