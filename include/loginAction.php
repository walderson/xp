<?php
if (!isset($_POST["login"])) {
  $msgErro = "O campo Login é obrigatório.";
}
else if (!isset($_POST["senha"])) {
  $msgErro = "O campo Senha é obrigatório.";
}

if (isset($msgErro)) {
    include 'include/login.php';
} else {
  $login = $_POST["login"];
  $senha = $_POST["senha"];
  $senhaAux = md5($login . $senha);

  $stmt = $conn->prepare("SELECT id, nome, senha, administrador, ativo, redefinir_senha FROM xp.usuario where login=?");
  $stmt->bind_param('s', $login);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($senhaAux == $row["senha"]) {
      if (isset($row["ativo"]) && ($row["ativo"] == 1)) {
        if ($row["redefinir_senha"] != null) {
          $stmt = $conn->prepare("UPDATE xp.usuario
            SET redefinir_senha = NULL
            WHERE id = ?");
          $stmt->bind_param('i', $row["id"]);
          $stmt->execute();
        }

        $_SESSION['usuarioId'] = $row["id"];
        $_SESSION['login'] = $login;
        $_SESSION['usuario'] = $row["nome"];
        $_SESSION['administrador'] = $row["administrador"];
        $_SESSION['gestor'] = buscarUOGestor($conn, $row["id"]);
        include 'include/home.php';
      } else {
        $msgErro = "Login inativo.\n\nFavor procurar o administrador.";
        include 'include/login.php';
      }
    } else {
      $msgErro = "Login não encontrado ou senha incorreta.\n\nTente novamente.";
      include 'include/login.php';
    }
  } else {
    $msgErro = "Login não encontrado ou senha incorreta.\n\nTente novamente.";
    include 'include/login.php';
  }
}

function buscarUOGestor($conn, $gestorId) {
  $array = array();

  $stmt = $conn->prepare("SELECT id FROM xp.uo WHERE gestor_id = ?");
  $stmt->bind_param('i', $gestorId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $i = 0;
    while ($row = $result->fetch_assoc()) {
      $array[$i++] = $row["id"];
    }
  }

  return $array;
}
?>