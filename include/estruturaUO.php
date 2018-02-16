<?php
$titulo = "Visualizar Estrutura Organizacional";
$msg = "";
$msgErro = "";

function estrutura($conn, $id) {
  $sql = "SELECT 
    uo.id, uo.sigla, uo.nome,
	(SELECT COUNT(id) FROM xp.usuario u WHERE u.id <> 1 AND u.uo_id = uo.id AND u.ativo = 1) qtdu,
	(SELECT COUNT(id) FROM xp.competencia c WHERE c.uo_id = uo.id AND c.ativo = 1) qtdc
    FROM xp.uo uo
    WHERE
      uo.ativo = 1
      AND uo.uo_id ";
  if ($id == null)
    $sql .= "is NULL ";
  else
    $sql .= "= ? ";
  $sql .= "GROUP BY uo.id, uo.sigla, uo.nome
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
?>
  <li>
    <div title="<?php echo $row["sigla"] ?> - Colaboradores: <?php echo $row["qtdu"] ?>; Competências: <?php echo $row["qtdc"] ?>">
	  <?php echo $row["sigla"] ?> - <?php echo $row["nome"] ?></div>
<?php estrutura($conn, $row["id"]); ?>
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
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td>
<?php estrutura($conn, null); ?>
    </td>
  </tr>
</table>

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