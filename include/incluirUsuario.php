<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=incluirUsuarioAction" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Incluir Usuário</h2></th>
  </tr>
  <tr>
    <td width="205">
      <strong>Login:*</strong><br/>
      <input type="text" name="login" maxlength="32" pattern="[0-9A-Za-z]+" placeholder="Entre com o Login"
             required size="20" style="text-transform: lowercase;"
             title="Somente letras ou números.">
    </td>
    <td width="420">
      <strong>Nome:*</strong><br/>
      <input type="text" name="nome" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com o Nome"
             required size="50" title="Sem espaços em branco no começo ou no fim.">
    </td>
    <td width="205">
      <strong>Senha:*</strong><br/>
      <input type="password" name="senha" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a Senha"
               required title="Sem espaços em branco no começo ou no fim." size="20">
    </td>
    <td width="170">
      <strong>Administrador:</strong><br/>
      <input type="radio" name="administrador" required value="1"> Sim
      <input type="radio" name="administrador" required value="0" checked> Não
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
      <strong>Unidade Organizacional:*</strong><br/>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", null, true); ?>
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
      <input type="submit" name="acao" value="   Incluir   ">
      <input type="reset" name="redefinir" onclick="javascript: document.frm.login.focus();" value="   Redefinir   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.login.focus();

  function validaForm(frm) {
    return window.confirm("Confirma a inclusão do Usuário '" + frm.login.value.toLowerCase() + "'?");
  }
</script>

    </td>
  </tr>
</table>