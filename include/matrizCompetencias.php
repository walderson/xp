<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=matrizCompetencias" method="POST">
<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2>Matriz de Competências</h2></th>
  </tr>
  <tr>
    <td width="140">
      <strong>Trimestre:*</strong><br/>
<?php
  if (isset($_POST["trimestre"])) {
    $trimestre = $_POST["trimestre"];
  } else {
    $trimestre = "";
  }
?>
      <input type="text" name="trimestre" autocomplete="off" list="trimestres" maxlength="6" pattern="^\d{4}[\-][1-4]$"
             placeholder="aaaa-t" required size="10"
             title="Informe o ano e o trimestre. Exemplos: 2018-1, 2018-2, 2018-3..."
             value="<?php echo $trimestre; ?>">
<?php datalist("trimestres", $conn, "avaliacao", "trimestre", false); ?>
    </td>
    <td width="740">
<?php
  if (isset($_POST["uo"])) {
    $uoId = getId($conn, "uo", $_POST["uo"]);
  } else {
    $uoId = null;
  }
?>
      <strong>Unidade Organizacional:*</strong><br/>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", $uoId, true); ?>
      <style>
        select[name="uo"] {
          width: 700px;
        }
      </style>
    </td>
    <td style="vertical-align: bottom" width="120">
      <input type="submit" name="acao" value="   Pesquisar   ">
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.trimestre.focus();
</script>

<?php
if (isset($_POST["acao"])) {
  $nivelCompetencia = array();
?>
<table border="0" style="table-layout:fixed;" width="0">
  <tr>
    <th style="background-color: #336699; color: #ffffff; width: 500px;">Colaborador</th>
<?php
  //Busca trimestre da avaliação anterior
  $sql = "SELECT MAX(a.trimestre) trimestre
          FROM
            xp.avaliacao a
            INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
            INNER JOIN xp.uo uo ON (a.uo_id = uo.id)
          WHERE 1 = 1
          AND a.trimestre < ?
          AND uo.hash = ?
          ORDER BY a.trimestre";
  $trimestre = $_POST["trimestre"];
  $trimestreAnt = null;
  $uo = $_POST["uo"];
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $trimestre, $uo);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc())
    $trimestreAnt = $row["trimestre"];

  //Consulta avaliações que atendam aos critérios do filtro de pesquisa
  $sql = "SELECT uo.id uo_id, a.id, a.hash, a.usuario_id, u.nome, a.data_limite, a.data_avaliacao, a.data_revisao
          FROM
            xp.avaliacao a
            INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
            INNER JOIN xp.uo uo ON (a.uo_id = uo.id)
          WHERE 1 = 1
          AND a.trimestre = ?
          AND uo.hash = ?
          ORDER BY a.trimestre, uo.sigla, u.nome";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $trimestre, $uo);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Monta os títulos das colunas para as competências avaliadas
    $qtdCompetencias = 0;
    $primeiroRegistro = true;
    $competencias = array();
    while ($row = $result->fetch_assoc()) {
      if ($primeiroRegistro) {
        $primeiroRegistro = false;
        $sql = "SELECT DISTINCT ac.competencia_id id, c.sigla, c.competencia, uo.sigla uo
          FROM xp.avaliacao a
          INNER JOIN xp.avaliacao_competencia ac ON (ac.avaliacao_id = a.id)
          INNER JOIN xp.competencia c ON (ac.competencia_id = c.id)
          INNER JOIN xp.uo uo ON (c.uo_id = uo.id)
          WHERE a.id = ?
          ORDER BY ac.id";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $row["id"]);
        $stmt->execute();
        $rc = $stmt->get_result();
        while ($c = $rc->fetch_assoc()) {
          $competencias[$qtdCompetencias] = $c["id"];
          $nivelCompetencia[$qtdCompetencias++] = array(0, 0, 0);
?>
    <th style="background-color: #336699; color: #ffffff;" width="60" title="<?php echo $c["uo"] . "/" . $c["competencia"]; ?>"><?php echo $c["sigla"]; ?></th>
<?php
        }
?>
  </tr>
<?php
      }
?>
  <tr class="pesquisa">
    <td>
<?php
  if (isset($_SESSION['gestor']) && in_array($row["uo_id"], $_SESSION['gestor'])) {
?>
      <a href="?operacao=avaliacao&id=<?php echo $row["hash"]; ?>" title="Acessar a avaliação"><?php echo $row["nome"]; ?>.</a>
<?php
  } else {
?>
      <?php echo $row["nome"]; ?>
<?php
  }
?>
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
<?php
      // Busca as avaliações preenchidas, para comparação
      for ($i = 0; $i < $qtdCompetencias; $i++) {
        $sql = "SELECT ac.nivel, aca.nivel nivela
          FROM xp.avaliacao_competencia ac
          LEFT JOIN xp.avaliacao aa ON (aa.trimestre = ? AND aa.uo_id = ? AND aa.usuario_id = ?)
          LEFT JOIN xp.avaliacao_competencia aca ON (aca.avaliacao_id = aa.id AND aca.competencia_id = ?)
          WHERE ac.avaliacao_id = ?
          AND ac.competencia_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siiiii', $trimestreAnt, $row["uo_id"], $row["usuario_id"], $competencias[$i], $row["id"], $competencias[$i]);
        $stmt->execute();
        $rac = $stmt->get_result();
        if ($ac = $rac->fetch_assoc()) {
          if ($ac["nivel"] != null) {
            $style = " color: gray;";
            $nivela = "";
            if ($ac["nivela"] != null) {
              $nivela = "
Anterior: Nível " . $ac["nivela"];
              if ($ac["nivel"] > $ac["nivela"]) $style = " color: green;";
              else if ($ac["nivel"] < $ac["nivela"]) $style = " color: red;";
              else $style = " color: orange;";
            }
?>
    <td class="competencia<?php echo $i; ?>" style="text-align: center;<?php echo $style; ?>">
<?php
  switch($ac["nivel"]) {
    case 1: ?><div title="Nível 1: Pouco conhecimento ou nenhum<?php echo $nivela; ?>">★</div>
<?php
      $nivelCompetencia[$i][0]++;
      break;
    case 2: ?><div title="Nível 2: Bom conhecimento<?php echo $nivela; ?>">★★</div>
<?php
      $nivelCompetencia[$i][1]++;
      break;
    case 3: ?><div title="Nível 3: Expert, possuindo domínio<?php echo $nivela; ?>">★★★</div>
<?php
      $nivelCompetencia[$i][2]++;
  }
?>
    </td>
<?php
          } else {
?>
    <td style="text-align: center;" title="Aguardando resposta do Colaborador">-</td>
<?php
          }
        } else {
?>
    <td style="text-align: center;" title="Não aplicável">N/A</td>
<?php
        }
      }
?>
  </tr>
<?php
    }
  } else {
    echo "  </tr>";
?>
  <tr class="pesquisa">
    <td>Nenhum registro encontrado.</td>
  </tr>
<?php
  }
?>
</table>
<?php
  if (sizeof($nivelCompetencia) != 0) {
?>
<style>
<?php
    for ($i = 0; $i < $qtdCompetencias; $i++) {
      switch ($nivelCompetencia[$i][2]) {
		case 0:
?>
td [class="competencia<?php echo $i; ?>"] {
  background-color: #ffe0e0;
}
<?php
		  break;
		case 1:
?>
td [class="competencia<?php echo $i; ?>"] {
  background-color: #ffffc0;
}
<?php
		  break;
      }
    }
?>
</style>
<?php
  }
?>
<?php
}
?>

    </td>
  </tr>
</table>