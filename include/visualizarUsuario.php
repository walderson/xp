<?php
$titulo = "Visualizar Usuário";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $id = getId($conn, "usuario", $hash);
  $sql = "SELECT
    u.login, u.nome, u.administrador, u.ativo, uo.sigla, uo.nome uo
    FROM xp.usuario u
      INNER JOIN xp.uo uo on (u.uo_id = uo.id)
    WHERE u.hash=?";

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
    <th colspan="4"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td width="205">
      <strong>Login:</strong><br/>
      <?php echo $row["login"]; ?>
    </td>
    <td width="420">
      <strong>Nome:</strong><br/>
      <?php echo $row["nome"]; ?>
    </td>
    <td width="205">
      <strong>Perfil:</strong><br/>
      <?php echo $row["administrador"] == 1 ? "Administrador" : "Usuário"; ?>
    </td>
    <td width="170">
      <strong>Status:</strong><br/>
      <?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?>
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
      <strong>Unidade Organizacional:</strong><br/>
      <?php echo $row["sigla"]; ?> - <?php echo $row["uo"]; ?>
    </td>
  </tr>
</table>

<?php
    $sql = "SELECT sigla, nome, ativo
            FROM xp.uo
            WHERE gestor_id = ?
            ORDER BY sigla";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
?>
&nbsp;<br />
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff;" width="120">Sigla</th>
    <th style="background-color: #336699; color: #ffffff;" width="800">Gestor da Unidade Organizacional</th>
    <th style="background-color: #336699; color: #ffffff;" width="80">Situação</th>
  </tr>
<?php
      while ($row = $result->fetch_assoc()) {
?>
  <tr class="pesquisa">
    <td><?php echo $row["sigla"]; ?></td>
    <td><?php echo $row["nome"]; ?></td>
    <td style="text-align: center;"><?php echo $row["ativo"] == 1 ? "Ativo" : "Inativo"; ?></td>
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