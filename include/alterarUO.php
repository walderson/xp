<?php
$titulo = "Alterar Unidade Organizacional";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT 
    uo_id, uo.sigla, uo.nome, uo.ativo
    FROM xp.uo uo
    WHERE uo.hash=?";

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

<form name="frm" action="?operacao=alterarUOAction&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Unidade Organizacional Superior:</strong><br/>
      <?php combobox("uoSuperior", $conn, "uo", "sigla", "nome", $row["uo_id"], false); ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td width="250">
      <strong>Sigla:*</strong><br/>
      <input type="text" name="sigla" maxlength="32" pattern="[A-Za-z]+" placeholder="Entre com a Sigla"
             required size="20" style="text-transform: uppercase;"
             title="Somente letras." value="<?php echo $row["sigla"]; ?>">
    </td>
    <td width="500">
      <strong>Nome:*</strong><br/>
      <input type="text" name="nome" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com o Nome"
             required size="50" title="Sem espaços em branco no começo ou no fim."
             value="<?php echo $row["nome"]; ?>">
    </td>
    <td width="250">
      <strong>Ativo:</strong><br/>
      <input type="radio" name="ativo" required value="1"<?php if ($row["ativo"] == 1) echo " checked"; ?>> Sim
      <input type="radio" name="ativo" required value="0"<?php if ($row["ativo"] == 0) echo " checked"; ?>> Não
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">
<?php
if (isset($msgErro)) {
?>
  <div style="color:#ff0000;"><?php echo $msgErro; ?></div><br/>
<?php
}
?>
      <input type="submit" name="acao" value="   Alterar   ">
      <input type="reset" name="redefinir" onclick="javascript: document.frm.uoSuperior.focus();" value="   Redefinir   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.uoSuperior.focus();

  function validaForm(frm) {
    return window.confirm("Confirma a alteração da Unidade Organizacional '" + frm.sigla.value.toUpperCase() + "'?");
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