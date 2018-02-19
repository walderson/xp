<?php
//Efetua o logoff do usuário
unset($_SESSION['usuarioId']);
unset($_SESSION['login']);
unset($_SESSION['usuario']);
unset($_SESSION['administrador']);
unset($_SESSION['gestor']);
?>
<form name="frm" action="?operacao=loginAction" method="POST">
<table border="0" width="1200">
  <tr>
    <th colspan="5"><h2>Efetuar Login</h2></th>
  </tr>
  <tr>
    <td width="350" rowspan="3">&nbsp;</td>
    <td rowspan="3" width="128"><img src="image/user-access.png" height="128" width="128"></td>
    <th style="text-align:left;" width="72">Login:*</th>
    <td><input type="text" name="login" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com o seu login"
               required size="30" title="Sem espaços em branco no começo ou no fim." width="300"></td>
    <td width="350" rowspan="3">&nbsp;</td>
  </tr>
  <tr>
    <th style="text-align:left;">Senha:*</th>
    <td><input type="password" name="senha" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a senha"
               required  title="Sem espaços em branco no começo ou no fim." size="30"></td>
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
      <input type="submit" name="acao" value="   Entrar   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.login.focus();
</script>