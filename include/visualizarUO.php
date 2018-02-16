<?php
$titulo = "Visualizar Unidade Organizacional";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT 
    uo_superior.sigla sigla_superior, uo_superior.nome nome_superior, uo.sigla, uo.nome, uo.ativo
    FROM xp.uo uo
      LEFT JOIN xp.uo uo_superior on (uo.uo_id = uo_superior.id)
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