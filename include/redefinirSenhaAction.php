<?php
$titulo = "Redefinir Senha";
$msg = "";
$msgErro = "";

if (!isset($_POST["novaSenha"])) {
  $msgErro = "O campo Nova Senha é obrigatório.";
}
else if (!isset($_POST["novaSenhaConf"])) {
  $msgErro = "O campo Confirme Nova Senha é obrigatório.";
}
else if ($_POST["novaSenha"] != $_POST["novaSenhaConf"]) {
  $msgErro = "Os campos Nova Senha e Confirme Nova Senha devem possuir o mesmo valor.";
}

if ($msgErro != "") {
    include 'include/redefinirSenha.php';
} else {
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
      $novaSenha = $_POST["novaSenha"];
      $novaSenhaConf = $_POST["novaSenhaConf"];

      $login = $row["login"];

      $stmt = $conn->prepare("UPDATE xp.usuario SET senha = ?, redefinir_senha = NULL where login=?");
      $stmt->bind_param('ss', $novaSenha, $login);
      $stmt->execute();
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
if(isset($_SESSION['usuario'])) {
  include 'include/menu.php';
} else echo "&nbsp;";
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td width="200" rowspan="4">&nbsp;</td>
    <td colspan="2" style="text-align:center;" width="600">
      <div style="color:#0000ff;">Senha redefinida com sucesso!</div><br/><br/>
	  <a href="?operacao=login">Clique aqui</a> para efetuar o login.
    </td>
    <td width="200" rowspan="4">&nbsp;</td>
  </tr>
</table>

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
}
?>