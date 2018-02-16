<?php
function datalist($name, $conn, $table, $value, $ativo) {
  if ($ativo)
    $stmt = $conn->prepare("SELECT DISTINCT $value FROM xp.$table where ativo = 1 order by $value");
  else
    $stmt = $conn->prepare("SELECT DISTINCT $value FROM xp.$table order by $value");
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