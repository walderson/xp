<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=pesquisarAvaliacao" method="POST">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Pesquisar Avaliação</h2></th>
  </tr>
  <tr>
    <td width="140">
      <strong>Trimestre:</strong><br/>
<?php
  if (isset($_POST["trimestre"])) {
    $trimestre = $_POST["trimestre"];
  } else {
    $trimestre = "";
  }
?>
      <input type="text" name="trimestre" list="trimestres" maxlength="6" pattern="^\d{4}[\-][1-4]$"
             placeholder="aaaa-t" size="10"
             title="Informe o ano e o trimestre. Exemplos: 2018-1, 2018-2, 2018-3..."
             value="<?php echo $trimestre; ?>">
<?php datalist("trimestres", $conn, "avaliacao", "trimestre", false); ?>
    </td>
    <td width="240">
<?php
  if (isset($_POST["uo"])) {
    $uoId = getId($conn, "uo", $_POST["uo"]);
  } else {
    $uoId = null;
  }
?>
      <strong>Unidade Organizacional:</strong><br/>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", $uoId, false); ?>
      <style>
        select[name="uo"] {
          max-width: 200px;
        }
      </style>
    </td>
    <td width="320">
      <strong>Colaborador:</strong><br/>
<?php
  if (isset($_POST["colaborador"])) {
    $colaborador = $_POST["colaborador"];
  } else {
    $colaborador = "";
  }
?>
      <input type="text" name="colaborador" maxlength="100" list="colaboradores" pattern="^[\S]+( [\S]+)*$"
             placeholder="Entre com o Nome do Colaborador" size="35" title="Sem espaços em branco no começo ou no fim."
             value="<?php echo $colaborador; ?>">
<?php datalist("colaboradores", $conn, "usuario", "nome", true); ?>
    </td>
    <td style="vertical-align: bottom" width="300">
      <input type="submit" name="acao" value="   Pesquisar   ">
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.trimestre.focus();
</script>

<?php
if (isset($_POST["acao"])) {
?>
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120">Trimestre</th>
    <th style="background-color: #336699; color: #ffffff;" width="120" title="Unidade Organizacional">UO</th>
    <th style="background-color: #336699; color: #ffffff;" width="460">Colaborador</th>
    <th style="background-color: #336699; color: #ffffff;" width="300">Link para Avaliação</th>
  </tr>
<?php
  $sql = "SELECT a.hash, a.trimestre, uo.sigla, uo.nome uo, u.nome, a.data_limite, a.data_avaliacao, a.data_revisao
          FROM
            xp.avaliacao a
            INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
            INNER JOIN xp.uo uo ON (u.uo_id = uo.id)
          WHERE 1 = 1 ";
  $trimestre = "";
  $uo = "";
  $colaborador = "";
  if (isset($_POST["trimestre"]) && ($_POST["trimestre"] != "")) {
    $sql .= "and a.trimestre = ? ";
    $trimestre = $_POST["trimestre"];
  }
  if (isset($_POST["uo"]) && ($_POST["uo"] != "")) {
    $sql .= "and uo.hash = ? ";
    $uo = $_POST["uo"];
  }
  if (isset($_POST["colaborador"]) && ($_POST["colaborador"] != "")) {
    $sql .= "and u.nome like ? ";
    $colaborador = "%" . $_POST["colaborador"] . "%";
  }
  $sql .= "ORDER BY a.trimestre, uo.sigla, u.nome";
  $stmt = $conn->prepare($sql);
  if ($trimestre != "")
    if ($uo != "")
      if ($colaborador != "")
        $stmt->bind_param('sss', $trimestre, $uo, $colaborador);
      else
        $stmt->bind_param('ss', $trimestre, $uo);
    else
      if ($colaborador != "")
        $stmt->bind_param('ss', $trimestre, $colaborador);
      else
        $stmt->bind_param('s', $trimestre);
  else
    if ($uo != "")
      if ($colaborador != "")
        $stmt->bind_param('ss', $uo, $colaborador);
      else
        $stmt->bind_param('s', $uo);
    else
      if ($colaborador != "")
        $stmt->bind_param('s', $colaborador);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
  <tr class="pesquisa">
    <td style="text-align: center;">
      <?php echo $row["trimestre"]; ?>
      <?php if ($row["data_avaliacao"] == null) { ?>
      <?php if (strtotime(date("Y-m-d")) <= strtotime($row["data_limite"])) { ?>
      <img src="image/alert-blue.png" title="Colaborador deve realizar autoavaliação até <?php echo date('d/m/Y', strtotime($row["data_limite"])); ?>." height="16" width="16">
      <?php } else { ?>
      <img src="image/alert-red.png" title="Colaborador não realizou autoavaliação, vencida em <?php echo date('d/m/Y', strtotime($row["data_limite"])); ?>." height="16" width="16">
      <?php } ?>
      <?php } else if ($row["data_revisao"] == null) { ?>
      <?php if (strtotime(date("Y-m-d H:i:s")) <= strtotime($row["data_avaliacao"] . " +7 days")) { ?>
      <img src="image/gears-blue.png" title="Aguardando revisão da avaliação, enviada em <?php echo date('d/m/Y H:i:s', strtotime($row["data_avaliacao"])); ?>." height="16" width="16">
      <?php } else { ?>
      <img src="image/gears-red.png" title="Aguardando revisão da avaliação, enviada em <?php echo date('d/m/Y H:i:s', strtotime($row["data_avaliacao"])); ?>." height="16" width="16">
      <?php } ?>
      <?php } else { ?>
      <img src="image/check.png" title="Avaliação finalizada em <?php echo date('d/m/Y H:i:s', strtotime($row["data_revisao"])); ?>." height="16" width="16">
      <?php } ?>
    </td>
    <td style="text-align: center;"><div title="<?php echo $row["uo"]; ?>"><?php echo $row["sigla"]; ?></div></td>
    <td><?php echo $row["nome"]; ?></td>
    <td style="text-align: center;">
      <a href="?operacao=avaliacao&id=<?php echo $row["hash"]; ?>"><?php echo $row["hash"]; ?></a>
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