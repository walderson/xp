<?php
$titulo = "Autoavaliação: Conhecimentos e Habilidades<br />Orientações Gerais";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.data_limite, u.nome, a.data_avaliacao, a.data_revisao
    FROM xp.avaliacao a
      INNER JOIN xp.usuario u on (a.usuario_id = u.id)
    WHERE a.hash=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $hash);
  $stmt->execute();

  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["data_avaliacao"] == null) {
?>
<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
if(isset($_SESSION['usuario'])) {
  include 'include/menu.php';
} else echo "&nbsp;";
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=avaliacaoForm&id=<?php echo $hash; ?>" method="POST" onsubmit="javascript: return confirma();">
<table border="0" width="1000">
  <tr>
    <th><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Olá, <strong><?php echo $row["nome"]; ?></strong>.<br/>
      <br/>
      Com o objetivo de compartilhar o conhecimento e habilidades das equipes de trabalho de Tecnologia da Informação, esta autoavaliação pretende coletar tais dados para que estes possam ser usados como insumos para melhorar a rotina de trabalho.
      <br/>
      Para cada um dos itens (conhecimento ou habilidade) a ser informado, considere as seguintes definições:
      <ul>
        <li><strong>Nível 1:</strong> Não conhece ou conhece insuficiente, tendo bastante dificuldade;</li>
        <li><strong>Nível 2:</strong> Conhece bem, mas ainda precisa de assistência de vez em quando;</li>
        <li><strong>Nível 3:</strong> Tem domínio, podendo se considerar um expert.</li>
      </ul>
      <strong>Obs.:</strong> Esta autoavaliação deverá ser respondida até <?php echo date('d/m/Y', strtotime($row["data_limite"])); ?>.
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align: center;">
      <input type="submit" name="acao" value="   Iniciar a autoavaliação   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  function confirma() {
    if (!window.confirm("Confirma o início da autoavaliação?")) return false;
    return true;
  }
</script>

    </td>
  </tr>
</table>
<?php
    } else {
      if (isset($_SESSION['administrador']) && $_SESSION['administrador'] == 1)
        if ($row["data_revisao"] == null)
          include 'include/revisarAvaliacao.php';
        else
          include 'include/visualizarAvaliacao.php';
      else
        include 'include/visualizarAvaliacao.php';
    }
  } else {
    $msgErro = "Erro: Registro informado inexistente: " . $_GET["id"];
    include 'include/mensagem.php';
  }
} else {
  $msgErro = "Erro: Identificador do registro não informado.";
  include 'include/mensagem.php';
}
?>