<?php
function datalist($name, $conn, $table, $value, $ativo) {
  $sql = "SELECT DISTINCT $value FROM xp.$table WHERE 1 = 1 ";
  // Não exibe o usuário Administrador
  if ($table == "usuario") $sql .= "AND id <> 1 ";
  if ($ativo) $sql .= "AND ativo = 1 ";
  $sql .= "ORDER BY $value";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
?>
<datalist id="<?php echo $name; ?>">
<?php
    while ($row = $result->fetch_assoc()) {
?>
  <option value="<?php echo $row[$value]; ?>">
<?php
    }
?>
</datalist>
<?php
  }
}
?>