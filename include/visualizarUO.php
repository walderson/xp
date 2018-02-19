<?php
$titulo = "Visualizar Unidade Organizacional";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $uoId = getId($conn, "uo", $hash);
  $sql = "SELECT 
    uo_superior.sigla sigla_superior, uo_superior.nome nome_superior, uo.sigla, uo.nome, g.nome gestor, uo.ativo
    FROM xp.uo uo
      LEFT JOIN xp.uo uo_superior on (uo.uo_id = uo_superior.id)
      LEFT JOIN xp.usuario g ON (uo.gestor_id = g.id)
    WHERE uo.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td width="150">
      <strong>Sigla:</strong><br/>
      <?php echo $row["sigla"]; ?>
    </td>
    <td width="750">
      <strong>Nome:</strong><br/>
      <?php echo $row["nome"]; ?>
    </td>
    <td width="100">
      <strong>Status:</strong><br/>
      <?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Unidade Organizacional Superior:</strong><br/>
<?php if ($row["sigla_superior"] != null) { ?>
      <?php echo $row["sigla_superior"]; ?> - <?php echo $row["nome_superior"]; ?>
<?php } else { ?>
      Sem Unidade Organizacional Superior
<?php } ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Gestor:</strong><br/>
<?php if ($row["gestor"] != null) { ?>
      <?php echo $row["gestor"]; ?>
<?php } else { ?>
      Sem Gestor
<?php } ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;">&nbsp;</td>
  </tr>
</table>

<table border="0" width="1000">
  <tr>
    <th colspan="4" style="background-color: #336699; color: #ffffff;">Colaboradores</th>
  </tr>
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120">Login</th>
    <th style="background-color: #336699; color: #ffffff;" width="680">Nome</th>
    <th style="background-color: #336699; color: #ffffff;" width="120" title="Unidade Organizacional">UO</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Situação</th>
  </tr>
<?php
  $sql = "SELECT u.hash, u.login, u.nome, uo.sigla uo, u.administrador, u.ativo
          FROM
            xp.usuario u
            INNER JOIN xp.uo uo ON (u.uo_id = uo.id)
          WHERE u.id <> 1
          AND uo.hash = ?
          ORDER BY u.nome";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
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
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>

<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120" title="Unidade Organizacional">UO</th>
    <th style="background-color: #336699; color: #ffffff;" width="800">Competência</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Situação</th>
  </tr>
<?php
  $sql = "SELECT c.hash, uo.sigla uo, uo.nome, c.sigla, c.competencia, c.descricao, c.replicar, c.ativo
          FROM
            xp.competencia c
            inner join xp.uo uo on (c.uo_id = uo.id)
          WHERE uo.id = ?
		  ORDER BY uo.sigla, c.ordem";
  $stmt = $conn->prepare($sql);

  // Obtém a hierarquia de unidades organizacionais
  $uos = array();
  $uos = arrayUO($conn, $uoId, $uos);

  $qtdc = 0;
  for ($i = 0; $i < count($uos); $i++) {
    $stmt->bind_param('i', $uos[$i]);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      if (($uoId != $uos[$i]) && ($row["replicar"] != 1)) continue;
      $qtdc++;
?>
  <tr class="pesquisa">
    <td title="<?php echo $row["nome"]; ?>"><?php echo $row["uo"]; ?></td>
    <td title="<?php echo $row["descricao"]; ?>">
      <?php if ($row["replicar"] == 1) {
  $replicar = $uoId != $uos[$i] ? "Replicada de Unidade Organizacional superior" : "Replica para as UOs subordinadas";
?><img src="image/structure.png" title="<?php echo $replicar; ?>" height="16" width="18">
<?php } ?>
      <?php echo $row["sigla"]; ?> - <?php echo $row["competencia"]; ?>
    </td>
    <td style="text-align: center;"><?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?></td>
  </tr>
<?php
    }
  }
  if ($qtdc == 0) {
?>
  <tr class="pesquisa">
    <td colspan="3">Nenhum registro encontrado.</td>
  </tr>
<?php
  }
?>
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
?>