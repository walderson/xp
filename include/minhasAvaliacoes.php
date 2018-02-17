<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Minhas Avaliações</h2></th>
  </tr>
</table>

<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="150">Trimestre</th>
    <th style="background-color: #336699; color: #ffffff;" width="150">Data Limite</th>
    <th style="background-color: #336699; color: #ffffff;" width="200">Data/Hora Resposta</th>
    <th style="background-color: #336699; color: #ffffff;" width="200">Data/Hora Revisão</th>
    <th style="background-color: #336699; color: #ffffff;" width="300">Link para Avaliação</th>
  </tr>
<?php
  $sql = "SELECT a.hash, a.trimestre, a.data_limite, a.data_avaliacao, a.data_revisao
          FROM
            xp.avaliacao a
            INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
          WHERE u.id = ?
		  ORDER BY a.trimestre, a.id";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $_SESSION['usuarioId']);
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
    <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($row["data_limite"])); ?></td>
    <td style="text-align: center;"><?php echo $row["data_avaliacao"] != null ? date('d/m/Y H:i:s', strtotime($row["data_avaliacao"])) : ""; ?></td>
    <td style="text-align: center;"><?php echo $row["data_revisao"] != null ? date('d/m/Y H:i:s', strtotime($row["data_revisao"])) : ""; ?></td>
    <td style="text-align: center;">
      <a href="?operacao=avaliacao&id=<?php echo $row["hash"]; ?>"><?php echo $row["hash"]; ?></a>
    </td>
  </tr>
<?php
    }
  } else {
?>
  <tr class="pesquisa">
    <td colspan="5">Nenhuma avaliação encontrada.</td>
  </tr>
<?php
  }
?>
</table>

    </td>
  </tr>
</table>