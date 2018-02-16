<?php
if (!isset($_POST["senhaAtual"])) {
  $msgErro = "O campo Senha Atual é obrigatório.";
}
else if (!isset($_POST["novaSenha"])) {
  $msgErro = "O campo Nova Senha é obrigatório.";
}
else if (!isset($_POST["novaSenhaConf"])) {
  $msgErro = "O campo Confirme Nova Senha é obrigatório.";
}
else if ($_POST["novaSenha"] != $_POST["novaSenhaConf"]) {
  $msgErro = "Os campos Nova Senha e Confirme Nova Senha devem possuir o mesmo valor.";
}

if (isset($msgErro)) {
    include 'include/alterarSenha.php';
} else {
  $senhaAtual = $_POST["senhaAtual"];
  $novaSenha = $_POST["novaSenha"];
  $novaSenhaConf = $_POST["novaSenhaConf"];

  $login = $_SESSION['login'];
  $senhaAux = md5($login . $senhaAtual);

  $stmt = $conn->prepare("SELECT senha FROM xp.usuario where login=?");
  $stmt->bind_param('s', $login);
  $stmt->execute();
  $result = $stmt->get_result();

  $row = $result->fetch_assoc();
  if ($senhaAux == $row["senha"]) {
    $stmt = $conn->prepare("UPDATE xp.usuario SET senha = ? where login=?");
    $stmt->bind_param('ss', $novaSenha, $login);
    $stmt->execute();
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="5"><h2>Alterar Senha</h2></th>
  </tr>
  <tr>
    <td width="200" rowspan="4">&nbsp;</td>
    <td colspan="2" style="text-align:center;" width="600">
      <div style="color:#0000ff;">Senha alterada com sucesso!</div><br/>
    </td>
    <td width="200" rowspan="4">&nbsp;</td>
  </tr>
</table>

	</td>
  </tr>
</table>
<?php
  } else {
    $msgErro = "Senha Atual incorreta.\n\nTente novamente.";
    include 'include/alterarSenha.php';
  }
}
?>