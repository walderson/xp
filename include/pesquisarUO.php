<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=pesquisarUO" method="POST">
<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2>Pesquisar Unidade Organizacional</h2></th>
  </tr>
  <tr>
    <td width="200">
      <strong>Sigla:</strong><br/>
<?php
  if (isset($_POST["sigla"])) {
    $sigla = $_POST["sigla"];
  } else {
    $sigla = "";
  }
?>
      <input type="text" name="sigla" maxlength="32" pattern="[A-Za-z]+"
             placeholder="Entre com a Sigla" size="20" style="text-transform: uppercase;"
             title="Somente letras." value="<?php echo $sigla; ?>">
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
    <td style="vertical-align: bottom" width="390">
      <input type="submit" name="acao" value="   Pesquisar   ">
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.sigla.focus();
  function excluir(id, sigla) {
    if (window.confirm("Confirma a exclusão da Unidade Organizacional '" + sigla + "'?")) {
      window.location = "?operacao=excluirUO&id=" + id;
	}
  }
</script>

<?php
if (isset($_POST["acao"])) {
?>
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120">Sigla</th>
    <th style="background-color: #336699; color: #ffffff;" width="720">Nome</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Situação</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Ação</th>
  </tr>
<?php
  $sql = "SELECT hash, sigla, nome, ativo FROM xp.uo WHERE 1=1 ";
  $sigla = "";
  $nome = "";
  if (isset($_POST["sigla"]) && ($_POST["sigla"] != "")) {
    $sql .= "and sigla like ? ";
    $sigla = "%" . $_POST["sigla"] . "%";
  }
  if (isset($_POST["nome"]) && ($_POST["nome"] != "")) {
    $sql .= "and nome like ? ";
    $nome = "%" . $_POST["nome"] . "%";
  }
  $sql .= "ORDER BY sigla";
  $stmt = $conn->prepare($sql);
  if ($sigla != "")
    if ($nome != "")
      $stmt->bind_param('ss', $sigla, $nome);
    else
      $stmt->bind_param('s', $sigla);
  else
    if ($nome != "")
      $stmt->bind_param('s', $nome);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
  <tr class="pesquisa">
    <td><?php echo $row["sigla"]; ?></td>
    <td><?php echo $row["nome"]; ?></td>
    <td><?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?></td>
    <td style="text-align: center">
      <a href="?operacao=visualizarUO&id=<?php echo $row["hash"]; ?>"><img src="image/magnifier.png" title="Visualizar" height="16" width="16"></a>
      <a href="?operacao=alterarUO&id=<?php echo $row["hash"]; ?>"><img src="image/pencil.png" title="Alterar" height="16" width="16"></a>
      <a href="javascript: excluir('<?php echo $row["hash"]; ?>', '<?php echo $row["sigla"]; ?>');"><img src="image/trash.png" title="Excluir" height="16" width="16"></a>
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