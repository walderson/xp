<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=gerarAvaliacoes" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Gerar Avaliações</h2></th>
  </tr>
  <tr>
    <td width="140">
      <strong>Trimestre:*</strong><br/>
<?php
  if (isset($_POST["trimestre"])) {
    $trimestre = $_POST["trimestre"];
  } else {
    $curMonth = date("m", time());
    $trimestre = date("Y") . "-" . ceil($curMonth/3);
  }
?>
      <input type="text" name="trimestre" maxlength="6" pattern="^\d{4}[\-][1-4]$" placeholder="aaaa-t"
             required size="10" title="Informe o ano e o trimestre. Exemplos: 2018-1, 2018-2, 2018-3..."
             value="<?php echo $trimestre; ?>">
    </td>
    <td width="240">
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
          max-width: 200px;
        }
      </style>
    </td>
    <td width="180">
      <strong>Data Limite:*</strong><br/>
<?php
  if (isset($_POST["data_limite"])) {
    $dataLimite = $_POST["data_limite"];
  } else {
    $dataLimite = "";
  }
?>
      <input type="date" name="data_limite" maxlength="10" min="<?php echo date('Y-m-d', strtotime(date("Y-m-d") . ' +1 day')); ?>"
             pattern="^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$"
             placeholder="dd/mm/aaaa" required size="10"
             title="Data futura no formato dd/mm/aaaa." value="<?php echo $dataLimite; ?>">
    </td>
    <td style="vertical-align: bottom" width="440">
      <input type="submit" name="acao" value="   Gerar Avaliações   ">
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.trimestre.focus();

  function validaForm(frm) {
    var dataAux = frm.data_limite.value.split("-");
    var dataLimite = dataAux[2] + "/" + dataAux[1] + "/" + dataAux[0];
    return window.confirm("Confirma a geração de Avaliação para o trimestre '" + frm.trimestre.value
      + "', Unidade Organizacional '" + frm.uo.options[frm.uo.selectedIndex].text
      + "' que devem ser respondidas até '" + dataLimite + "'?");
  }
</script>

<?php
if (isset($_POST["acao"])) {
  $dataLimite = date('Y-m-d', strtotime(str_replace('/', '-', $_POST["data_limite"])));
  if ($dataLimite <= date("Y-m-d")) {
?>
<font color="#ff0000">Erro: Data limite deve ser maior que a data atual.</font><br/>
<?php
  } else {
    // Verifica se existem avaliações para os parâmetros informados
    $sql = "SELECT a.id
            FROM
              xp.avaliacao a
              INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
            WHERE a.trimestre = ?
              AND u.uo_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $trimestre, $uoId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
?>
<font color="#ff0000">Erro: Avaliações não foram geradas. Já existem avaliações para os parâmetros informados.</font><br/>
<?php
    } else {
      // Obtém a relação de usuários ativos na unidade
      $sql = "SELECT u.id, u.nome
              FROM
                xp.usuario u
              WHERE u.id <> 1
                AND u.ativo = 1
                AND u.uo_id = ?
                ORDER BY u.nome";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i', $uoId);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $uArray = array();
        while ($row = $result->fetch_assoc()) {
          $uArray[sizeof($uArray)] = $row["id"];
        }

        // Obtém a hierarquia de unidades organizacionais
        $uoArray = array();
        $uoArray = arrayUO($conn, $uoId, $uoArray);

        // Gera as avaliações
        for ($i = 0; $i < sizeof($uArray); $i++) {
          $idAvaliacao = incluirAvaliacao($conn, $trimestre, $uArray[$i], $dataLimite);
          if ($idAvaliacao != -1) {
            for ($j = 0; $j < sizeof($uoArray); $j++) {
              incluirQuestoes($conn, $uoId, $uoArray[$j], $idAvaliacao);
            }
          }
        }
      }
    }
?>
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120">Trimestre</th>
    <th style="background-color: #336699; color: #ffffff;" width="460">Colaborador</th>
    <th style="background-color: #336699; color: #ffffff;" width="120" title="Unidade Organizacional">UO</th>
    <th style="background-color: #336699; color: #ffffff;" width="300">Link para Avaliação</th>
  </tr>
<?php
    $sql = "SELECT a.hash, a.trimestre, u.nome, uo.sigla, uo.nome uo, a.data_limite, a.data_avaliacao, a.data_revisao
            FROM
              xp.avaliacao a
              INNER JOIN xp.usuario u ON (a.usuario_id = u.id)
              INNER JOIN xp.uo uo ON (u.uo_id = uo.id)
            WHERE a.trimestre = ?
              AND u.uo_id = ?
            ORDER BY u.nome";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $trimestre, $uoId);
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
    <td><?php echo $row["nome"]; ?></td>
    <td style="text-align: center;"><div title="<?php echo $row["uo"]; ?>"><?php echo $row["sigla"]; ?></div></td>
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
}
?>

    </td>
  </tr>
</table>
<?php
function arrayUO($conn, $id, $array) {
  if ($id != null) {
    $sql = "SELECT id, uo_id
            FROM
              xp.uo
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Prossegue com a recursão, somente quando não houver referência cíclica
      if (!in_array($row["id"], $array)) {
        $array = arrayUO($conn, $row["uo_id"], $array);
        $ind = sizeof($array);
        $array[$ind] = $row["id"];
      }
    }
  }
  return $array;
}

function incluirAvaliacao($conn, $trimestre, $uId, $dataLimite) {
  $stmt = $conn->prepare("INSERT INTO xp.avaliacao(trimestre, usuario_id, data_limite) VALUES(?, ?, ?)");
  $stmt->bind_param('sis', $trimestre, $uId, $dataLimite);
  if ($stmt->execute()) return $conn->insert_id;
  else return -1;
}

function incluirQuestoes($conn, $uoIdSelecionado, $uoId, $idAvaliacao) {
  $sql = "INSERT INTO xp.avaliacao_competencia(avaliacao_id, competencia_id)
    (SELECT ?, id
    FROM xp.competencia
    WHERE ativo = 1
      AND uo_id = ?    ";
  if ($uoIdSelecionado != $uoId) {
    $sql .= "AND replicar = 1 ";
  }
  $sql .= "ORDER BY ordem)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ii', $idAvaliacao, $uoId);
  if (!$stmt->execute()) echo htmlspecialchars($stmt->error);
}
?>
