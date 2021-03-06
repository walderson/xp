﻿<?php
$titulo = "Visualizar Estrutura Organizacional";
$exibirUOInativas = isset($_POST["exibirUOInativas"]);
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=estruturaUO" method="POST">
<table border="0" width="1000">
  <tr>
    <th><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input type="checkbox" name="exibirUOInativas" value="true"
             onchange="javascript: document.frm.submit();"<?php echo $exibirUOInativas ? " checked" : ""; ?>>
      Exibir Unidades Organizacionais Inativas
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
<?php estrutura($conn, null, $exibirUOInativas, 0); ?>
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.exibirUOInativas.focus();
</script>

    </td>
  </tr>
</table>
<style>
/* Transformar listas com marcadores em árvore */
* {margin: 0; padding: 0; list-style: none;}
ul li {
  margin-left: 15px;
  position: relative;
  padding-left: 5px;
}
ul li::before {
  content: " ";
  position: absolute;
  width: 1px;
  background-color: #000;
  top: 5px;
  bottom: -12px;
  left: -10px;
}
td > ul > li:first-child::before {top: 12px;}
ul li:not(:first-child):last-child::before {display: none;}
ul li:only-child::before {
  display: list-item;
  content: " ";
  position: absolute;
  width: 1px;
  background-color: #000;
  top: 5px;
  bottom: 7px;
  height: 7px;
  left: -10px;
}
ul li::after {
  content: " ";
  position: absolute;
  left: -10px;
  width: 10px;
  height: 1px;
  background-color: #000;
  top: 12px;
}
</style>
<?php
function estrutura($conn, $id, $exibirUOInativas, $qtdc) {
  $sql = "SELECT 
    uo.id, uo.hash, uo.sigla, uo.nome, g.nome gestor, uo.ativo,
    (SELECT COUNT(id) FROM xp.usuario u WHERE u.id <> 1 AND u.uo_id = uo.id AND u.ativo = 1) qtdu,
    (SELECT COUNT(id) FROM xp.competencia c WHERE c.uo_id = uo.id AND c.ativo = 1) qtdc,
    (SELECT COUNT(id) FROM xp.competencia cr WHERE cr.uo_id = uo.id AND cr.ativo = 1 AND cr.replicar = 1) qtdr
    FROM xp.uo uo
    LEFT JOIN xp.usuario g ON (uo.gestor_id = g.id)
    WHERE 1 = 1 ";
  if (!$exibirUOInativas) $sql .= "AND uo.ativo = 1 ";
  $sql .= "AND uo.uo_id ";
  if ($id == null)
    $sql .= "is NULL ";
  else
    $sql .= "= ? ";
  $sql .= "GROUP BY uo.id, uo.hash, uo.sigla, uo.nome, g.nome, uo.ativo
    ORDER BY uo.sigla";

  $stmt = $conn->prepare($sql);
  if ($id != null)
    $stmt->bind_param('i', $id);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
?>
<ul>
<?php
    while ($row = $result->fetch_assoc()) {
      $uo = $row["sigla"] . " - Colaboradores: " . $row["qtdu"]
            . "; Competências: " . ($row["qtdc"] + $qtdc);
      if ($qtdc >= 1) {
        $uo .= " (" . $row["qtdc"] . " +" . $qtdc . " replicada";
        if ($qtdc > 1)
          $uo .= "s";
        $uo .= ")";
      }
      if ($row["gestor"] != null) $uo .= "
Gestor: " . $row["gestor"];
?>
  <li>
    <div style="<?php echo $row["ativo"] == 0 ? "color: red; " : "" ; ?>"
         title="<?php echo $uo; ?>">
      <a href="?operacao=visualizarUO&id=<?php echo $row["hash"]; ?>"><?php echo $row["sigla"]; ?> - <?php echo $row["nome"]; ?></a>
    </div>
<?php estrutura($conn, $row["id"], $exibirUOInativas, ($qtdc + $row["qtdr"])); ?>
  </li>
<?php
    }
?>
</ul>
<?php
  } else if ($id == null) {
      echo "Nenhum registro encontrado.";
  }
}
?>