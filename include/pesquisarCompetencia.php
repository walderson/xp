<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=pesquisarCompetencia" method="POST">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Pesquisar Competência</h2></th>
  </tr>
  <tr>
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
    <td width="200">
      <strong>Sigla:</strong><br/>
<?php
  if (isset($_POST["sigla"])) {
    $sigla = $_POST["sigla"];
  } else {
    $sigla = "";
  }
?>
      <input type="text" name="sigla" maxlength="4" pattern="[A-Za-z]+" placeholder="Entre com a Sigla"
             size="20" style="text-transform: uppercase;"
             title="Somente letras." value="<?php echo $sigla; ?>">
    </td>
    <td width="410">
      <strong>Nome da Competência:</strong><br/>
<?php
  if (isset($_POST["competencia"])) {
    $competencia = $_POST["competencia"];
  } else {
    $competencia = "";
  }
?>
      <input type="text" name="competencia" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a Nome da Competência"
             size="50" title="Sem espaços em branco no começo ou no fim." value="<?php echo $competencia; ?>">
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
  document.frm.uo.focus();
  function excluir(id, sigla) {
    if (window.confirm("Confirma a exclusão da Competência '" + sigla + "'?")) {
      window.location = "?operacao=excluirCompetencia&id=" + id;
    }
  }
</script>

<?php
if (isset($_POST["acao"])) {
?>
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120" title="Unidade Organizacional">UO</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Ordem</th>
    <th style="background-color: #336699; color: #ffffff;" width="640">Competência</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Situação</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Ação</th>
  </tr>
<?php
  $sql = "SELECT c.hash, uo.sigla uo, c.ordem, c.sigla, c.competencia, c.descricao, c.replicar, c.ativo
          FROM
            xp.competencia c
			inner join xp.uo uo on (c.uo_id = uo.id)
          WHERE 1=1 ";
  $uo = "";
  $sigla = "";
  $competencia = "";
  if (isset($_POST["sigla"]) && ($_POST["sigla"] != "")) {
    $sql .= "and u.sigla like ? ";
    $sigla = "%" . $_POST["sigla"] . "%";
  }
  if (isset($_POST["competencia"]) && ($_POST["competencia"] != "")) {
    $sql .= "and u.competencia like ? ";
    $competencia = "%" . $_POST["competencia"] . "%";
  }
  if (isset($_POST["uo"]) && ($_POST["uo"] != "")) {
    $sql .= "and uo.hash = ? ";
    $uo = $_POST["uo"];
  }
  $sql .= "ORDER BY uo.sigla, c.ordem";
  $stmt = $conn->prepare($sql);
  if ($sigla != "")
    if ($competencia != "")
      if ($uo != "")
        $stmt->bind_param('sss', $sigla, $competencia, $uo);
      else
        $stmt->bind_param('ss', $sigla, $competencia);
    else
      if ($uo != "")
        $stmt->bind_param('ss', $sigla, $uo);
      else
        $stmt->bind_param('s', $sigla);
  else
    if ($competencia != "")
      if ($uo != "")
        $stmt->bind_param('ss', $competencia, $uo);
      else
        $stmt->bind_param('s', $competencia);
    else
      if ($uo != "")
        $stmt->bind_param('s', $uo);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
  <tr class="pesquisa">
    <td><?php echo $row["uo"]; ?></td>
    <td style="text-align: center;"><?php echo $row["ordem"]; ?></td>
    <td title="<?php echo $row["descricao"]; ?>">
      <?php if ($row["replicar"] == 1) { ?><img src="image/structure.png" title="Replica para as UOs subordinadas" height="16" width="18"><?php } ?>
      <?php echo $row["sigla"]; ?> - <?php echo $row["competencia"]; ?>
    </td>
    <td style="text-align: center;"><?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?></td>
    <td style="text-align: center">
      <a href="?operacao=visualizarCompetencia&id=<?php echo $row["hash"]; ?>"><img src="image/magnifier.png" title="Visualizar" height="16" width="16"></a>
      <a href="?operacao=alterarCompetencia&id=<?php echo $row["hash"]; ?>"><img src="image/pencil.png" title="Alterar" height="16" width="16"></a>
      <a href="javascript: excluir('<?php echo $row["hash"]; ?>', '<?php echo $row["sigla"]; ?>');"><img src="image/trash.png" title="Excluir" height="16" width="16"></a>
    </td>
  </tr>
<?php
    }
  } else {
?>
  <tr class="pesquisa">
    <td colspan="5">Nenhum registro encontrado.</td>
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