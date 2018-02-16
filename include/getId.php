<?php
function getId($conn, $table, $hash) {
  $stmt = $conn->prepare("SELECT id FROM xp.$table where hash = ?");
  $stmt->bind_param('s', $hash);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
      return $row["id"];
    }
  }
  return -1;
}
?>