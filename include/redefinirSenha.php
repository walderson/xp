<?php
$titulo = "Redefinir Senha";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT 
    u.id, u.login, u.nome
    FROM xp.usuario u
    WHERE u.redefinir_senha = ?";

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
if(isset($_SESSION['usuario'])) {
  include 'include/menu.php';
} else echo "&nbsp;";
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=redefinirSenhaAction&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="5"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td width="100" rowspan="4">&nbsp;</td>
    <td rowspan="4" width="128"><img src="image/lock.jpg" height="128" width="128"></td>
    <th style="text-align:left;" width="200">Usuário:</th>
    <td width="472"><?php echo $row["login"]; ?> - <?php echo $row["nome"]; ?></td>
    <td width="100" rowspan="4">&nbsp;</td>
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
      <input type="submit" name="acao" value="   Redefinir Senha   ">
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