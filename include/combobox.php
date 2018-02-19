<?php
function combobox($name, $conn, $table, $id, $value, $selectedId, $required) {
  $sql = "SELECT id, hash, $id, $value FROM xp.$table WHERE ativo = 1 ";
  // Não exibe o usuário Administrador
  if ($table == "usuario") $sql .= "AND id <> 1 ";
  $sql .= "ORDER BY $id";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
?>
<select name="<?php echo $name; ?>"<?php echo $required ? " required" : ""; ?>>
  <option value="">-- Selecione --</option>
<?php
    while ($row = $result->fetch_assoc()) {
      if ($selectedId != null && $selectedId == $row["id"])
        $selected = " selected";
      else
        $selected = "";
      if ($id != $value)
        $option = $row[$id] . " - " . $row[$value];
      else
        $option = $row[$id];
?>
  <option value="<?php echo $row["hash"]; ?>"<?php echo $selected; ?>><?php echo $option; ?></option>
<?php
    }
?>
</select>
<?php
  }
}
?>