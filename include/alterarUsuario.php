<?php
$titulo = "Alterar Usuário";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
    u.login, u.nome, u.administrador, u.ativo, u.uo_id
    FROM xp.usuario u
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

<form name="frm" action="?operacao=alterarUsuarioAction&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return validaForm(this);">
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
      <strong>Nome:*</strong><br/>
      <input type="text" name="nome" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com o Nome"
             required size="50" title="Sem espaços em branco no começo ou no fim." value="<?php echo $row["nome"]; ?>">
    </td>
    <td width="205">
      <strong>Senha:</strong><br/>
      <input type="password" name="senha" maxlength="32" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a nova Senha"
               title="Sem espaços em branco no começo ou no fim." size="20">
    </td>
    <td width="170">
      <strong>Administrador:*</strong><br/>
      <input type="radio" name="administrador" required value="1"<?php echo $row["administrador"] == 1 ? "checked" : ""; ?>> Sim
      <input type="radio" name="administrador" required value="0"<?php echo $row["administrador"] == 0 ? "checked" : ""; ?>> Não
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Unidade Organizacional:*</strong><br/>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", $row["uo_id"], true); ?>
      <style>
        select[name="uo"] {
          max-width: 780px;
        }
      </style>
    </td>
    <td>
      <strong>Ativo:*</strong><br/>
      <input type="radio" name="ativo" required value="1"<?php if ($row["ativo"] == 1) echo " checked"; ?>> Sim
      <input type="radio" name="ativo" required value="0"<?php if ($row["ativo"] == 0) echo " checked"; ?>> Não
    </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">
<?php
if (isset($msgErro)) {
?>
  <div style="color:#ff0000;"><?php echo $msgErro; ?></div><br/>
<?php
}
?>
      <input type="submit" name="acao" value="   Alterar   ">
      <input type="reset" name="redefinir" onclick="javascript: document.frm.nome.focus();" value="   Redefinir   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.nome.focus();

  function validaForm(frm) {
    return window.confirm("Confirma a alteração do Usuário '<?php echo $row["login"]; ?>'?");
  }
</script>

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