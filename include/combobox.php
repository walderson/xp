<?php
function combobox($name, $conn, $table, $id, $value, $selectedId, $required) {
  $stmt = $conn->prepare("SELECT id, hash, $id, $value FROM xp.$table where ativo = 1 order by $id");
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
?>
<select name="<?php echo $name; ?>"<?php echo $required ? " required" : ""; ?>>
  <option value="">-- Selecione --</option>
<?php
    while ($row = $result->fetch_assoc()) {
      if ($selectedId != null && $selectedId == $row["id"]) {
?>
  <option value="<?php echo $row["hash"]; ?>" selected><?php echo $row[$id]; ?> - <?php echo $row[$value]; ?></option>
<?php
      } else {
?>
  <option value="<?php echo $row["hash"]; ?>"><?php echo $row[$id]; ?> - <?php echo $row[$value]; ?></option>
<?php
      }
    }
?>
</select>
<?php
  }
}
?>