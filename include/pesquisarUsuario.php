<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=pesquisarUsuario" method="POST">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Pesquisar Usuário</h2></th>
  </tr>
  <tr>
    <td width="200">
      <strong>Login:</strong><br/>
<?php
  if (isset($_POST["login"])) {
    $login = $_POST["login"];
  } else {
    $login = "";
  }
?>
      <input type="text" name="login" maxlength="32" pattern="[0-9A-Za-z]+" placeholder="Entre com o Login"
             size="20" style="text-transform: lowercase;"
             title="Somente letras ou números." value="<?php echo $login; ?>">
    </td>
    <td width="410">
      <strong>Nome:</strong><br/>
<?php
  if (isset($_POST["nome"])) {
    $nome = $_POST["nome"];
  } else {
    $nome = "";
  }
?>
      <input type="text" name="nome" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com o Nome"
             size="50" title="Sem espaços em branco no começo ou no fim." value="<?php echo $nome; ?>">
    </td>
    <td width="220">
      <strong>Unidade Organizacional:</strong><br/>
<?php
  if (isset($_POST["uo"])) {
    $uoId = getId($conn, "uo", $_POST["uo"]);
  } else {
    $uoId = null;
  }
?>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", $uoId, false); ?>
      <style>
        select[name="uo"] {
          max-width: 190px;
        }
      </style>
    </td>
    <td style="vertical-align: bottom" width="170">
      <input type="submit" name="acao" value="   Pesquisar   ">
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.login.focus();
  function excluir(id, login) {
    if (window.confirm("Confirma a exclusão do Usuário '" + login + "'?")) {
      window.location = "?operacao=excluirUsuario&id=" + id;
    }
  }
</script>

<?php
if (isset($_POST["acao"])) {
?>
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120">Login</th>
    <th style="background-color: #336699; color: #ffffff;" width="600">Nome</th>
    <th style="background-color: #336699; color: #ffffff;" width="120" title="Unidade Organizacional">UO</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Situação</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Ação</th>
  </tr>
<?php
  $sql = "SELECT u.hash, u.login, u.nome, uo.sigla uo, u.administrador, u.ativo
          FROM
            xp.usuario u
			INNER JOIN xp.uo uo ON (u.uo_id = uo.id)
          WHERE 1=1 ";
  $login = "";
  $nome = "";
  $uo = "";
  if (isset($_POST["login"]) && ($_POST["login"] != "")) {
    $sql .= "and u.login like ? ";
    $login = "%" . $_POST["login"] . "%";
  }
  if (isset($_POST["nome"]) && ($_POST["nome"] != "")) {
    $sql .= "and u.nome like ? ";
    $nome = "%" . $_POST["nome"] . "%";
  }
  if (isset($_POST["uo"]) && ($_POST["uo"] != "")) {
    $sql .= "and uo.hash = ? ";
    $uo = $_POST["uo"];
  }
  $sql .= "ORDER BY u.nome";
  $stmt = $conn->prepare($sql);
  if ($login != "")
    if ($nome != "")
      if ($uo != "")
        $stmt->bind_param('sss', $login, $nome, $uo);
      else
        $stmt->bind_param('ss', $login, $nome);
    else
      if ($uo != "")
        $stmt->bind_param('ss', $login, $uo);
      else
        $stmt->bind_param('s', $login);
  else
    if ($nome != "")
      if ($uo != "")
        $stmt->bind_param('ss', $nome, $uo);
      else
        $stmt->bind_param('s', $nome);
    else
      if ($uo != "")
        $stmt->bind_param('s', $uo);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
  <tr class="pesquisa">
    <td>
	  <?php if ($row["administrador"]) { ?>
	  <img src="image/administrator.png" title="Administrador" height="16" width="16">
	  <?php } ?>
	  <?php echo $row["login"]; ?>
	</td>
    <td><?php echo $row["nome"]; ?></td>
    <td><?php echo $row["uo"]; ?></td>
    <td><?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?></td>
    <td style="text-align: center">
      <a href="?operacao=visualizarUsuario&id=<?php echo $row["hash"]; ?>"><img src="image/magnifier.png" title="Visualizar" height="16" width="16"></a>
      <a href="?operacao=alterarUsuario&id=<?php echo $row["hash"]; ?>"><img src="image/pencil.png" title="Alterar" height="16" width="16"></a>
      <a href="javascript: excluir('<?php echo $row["hash"]; ?>', '<?php echo $row["login"]; ?>');"><img src="image/trash.png" title="Excluir" height="16" width="16"></a>
    </td>
  </tr>
<?php
    }
  } else {
?>
  <tr class="pesquisa">
    <td colspan="4">Nenhum registro encontrado.</td>
  </tr>
<?php
  }
?>
</table>
<?php
}
?>

    </td>
  </tr>
</table>