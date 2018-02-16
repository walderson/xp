<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=alterarSenhaAction" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="5"><h2>Alterar Senha</h2></th>
  </tr>
  <tr>
    <td width="150" rowspan="4">&nbsp;</td>
    <td rowspan="4" width="128"><img src="image/lock.jpg" height="128" width="128"></td>
    <th style="text-align:left;" width="200">Senha Atual:</th>
    <td width="372"><input type="password" name="senhaAtual" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a senha atual"
                           required size="30" title="Sem espaços em branco no começo ou no fim."></td>
    <td width="150" rowspan="4">&nbsp;</td>
  </tr>
  <tr>
    <th style="text-align:left;">Nova Senha:</th>
    <td><input type="password" name="novaSenha" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a nova senha"
               required size="30" title="Sem espaços em branco no começo ou no fim."></td>
  </tr>
  <tr>
    <th style="text-align:left;">Confirme Nova Senha:</th>
    <td><input type="password" name="novaSenhaConf" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Confirme a nova senha"
               required size="30" title="Sem espaços em branco no começo ou no fim."></td>
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
      <input type="submit" name="acao" value="   Alterar Senha   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.senhaAtual.focus();

  function validaForm(frm) {
    if (frm.novaSenha.value != frm.novaSenhaConf.value) {
      window.alert("Os campos Nova Senha e Confirme Nova Senha devem possuir o mesmo valor.");
      frm.novaSenha.focus();
      return false;
    }

    return true;
  }
</script>

	</td>
  </tr>
</table>