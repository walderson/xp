<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th><h2>Bem-vindo</h2></th>
  </tr>
  <tr>
    <td style="text-align: justify;">
      Olá, <strong><?php echo $_SESSION['usuario']; ?></strong>.<br/>
      &nbsp;<br/>
      Use o menu ao lado para acessar as funcionalidades disponíveis.

<?php
$id = $_SESSION['usuarioId'];
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
&nbsp;<br />
<table border="0" width="1000">
  <tr>
    <th style="background-color: #336699; color: #ffffff; text-align: center;" width="120">Sigla</th>
    <th style="background-color: #336699; color: #ffffff; text-align: center;" width="800">Gestor da Unidade Organizacional</th>
    <th style="background-color: #336699; color: #ffffff; text-align: center;" width="80">Situação</th>
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

    </td>
  </tr>
</table>