<?php
$titulo = "Alterar Competência";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
    c.uo_id, c.ordem, c.sigla, c.competencia, c.replicar, c.ativo, c.descricao
    FROM xp.competencia c
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

<form name="frm" action="?operacao=alterarCompetenciaAction&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Unidade Organizacional:*</strong><br/>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", $row["uo_id"], true); ?>
      <style>
        select[name="uo"] {
          max-width: 770px;
        }
      </style>
    </td>
    <td>
      <strong>Ordem:*</strong><br/>
      <input type="text" name="ordem" maxlength="3" pattern="[0-9]+" placeholder="Ordem"
             required size="10" title="Somente números." value="<?php echo $row["ordem"]; ?>">
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td width="220">
      <strong>Sigla:*</strong><br/>
      <input type="text" name="sigla" maxlength="4" pattern="[A-Za-z]+" placeholder="Entre com a Sigla"
             required size="20" style="text-transform: uppercase;"
             title="Somente letras." value="<?php echo $row["sigla"]; ?>">
    </td>
    <td width="440">
      <strong>Nome da Competência:*</strong><br/>
      <input type="text" name="competencia" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a Nome da Competência"
             required size="50" title="Sem espaços em branco no começo ou no fim." value="<?php echo $row["competencia"]; ?>">
    </td>
    <td width="170">
      <strong>Replicar:*</strong><br/>
      <input type="radio" name="replicar" required title="Replicar para as Unidades Subordinadas" value="1"
	         <?php echo $row["replicar"] == 1 ? "checked" : ""; ?>> Sim
      <input type="radio" name="replicar" required title="Não replicar para as Unidades Subordinadas" value="0"
	         <?php echo $row["replicar"] == 0 ? "checked" : ""; ?>> Não
    </td>
    <td width="170">
      <strong>Ativo:*</strong><br/>
      <input type="radio" name="ativo" required value="1"<?php echo $row["ativo"] == 1 ? "checked" : ""; ?>> Sim
      <input type="radio" name="ativo" required value="0"<?php echo $row["ativo"] == 0 ? "checked" : ""; ?>> Não
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
      <strong>Descrição:*</strong><br/>
      <textarea cols="120" name="descricao" placeholder="Entre com a Descrição" required rows="5"><?php echo $row["descricao"]; ?></textarea>
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">
<?php
if (isset($msgErro)) {
?>
  <div style="color:#ff0000;"><?php echo $msgErro; ?></div><br/>
<?php
}
?>
      <input type="submit" name="acao" value="   Alterar   ">
      <input type="reset" name="redefinir" onclick="javascript: document.frm.uo.focus();" value="   Redefinir   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.uo.focus();

  function validaForm(frm) {
    return window.confirm("Confirma a alteração da Competência '" + frm.sigla.value.toUpperCase() + "'?");
  }
</script>

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