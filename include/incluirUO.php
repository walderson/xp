<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=incluirUOAction" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="2"><h2>Incluir Unidade Organizacional</h2></th>
  </tr>
  <tr>
    <td width="250">
      <strong>Sigla:*</strong><br/>
      <input type="text" name="sigla" maxlength="32" pattern="[A-Za-z]+" placeholder="Entre com a Sigla"
             required size="20" style="text-transform: uppercase;"
             title="Somente letras.">
    </td>
    <td width="750">
      <strong>Nome:*</strong><br/>
      <input type="text" name="nome" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com o Nome"
             required size="50" title="Sem espaços em branco no começo ou no fim.">
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <strong>Unidade Organizacional Superior:</strong><br/>
      <?php combobox("uoSuperior", $conn, "uo", "sigla", "nome", null, false); ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <strong>Gestor:</strong><br/>
      <?php combobox("gestor", $conn, "usuario", "nome", "nome", null, false); ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
<?php
if (isset($msgErro)) {
?>
  <div style="color:#ff0000;"><?php echo $msgErro; ?></div><br/>
<?php
}
?>
      <input type="submit" name="acao" value="   Incluir   ">
      <input type="reset" name="redefinir" onclick="javascript: document.frm.uoSuperior.focus();" value="   Redefinir   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.sigla.focus();

  function validaForm(frm) {
    return window.confirm("Confirma a inclusão da Unidade Organizacional '" + frm.sigla.value.toUpperCase() + "'?");
  }
</script>

    </td>
  </tr>
</table>