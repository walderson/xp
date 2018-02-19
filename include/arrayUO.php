<?php
function arrayUO($conn, $id, $array) {
  if ($id != null) {
    $sql = "SELECT id, uo_id
            FROM
              xp.uo
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Prossegue com a recursão, somente quando não houver referência cíclica
      if (!in_array($row["id"], $array)) {
        $array = arrayUO($conn, $row["uo_id"], $array);
        $ind = sizeof($array);
        $array[$ind] = $row["id"];
      }
    }
  }
  return $array;
}
?>