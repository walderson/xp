<?php
$titulo = "Autoavaliação: Conhecimentos e Habilidades<br />Orientações Gerais";
$msg = "";
$msgErro = "";

if (isset($_GET["id"]) && $_GET["id"] != null) {
  $hash = $_GET["id"];
  $sql = "SELECT
      a.trimestre, a.data_limite, u.nome, data_avaliacao
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
      <br/>
      A tabela abaixo apresenta as descrições para o perfil geral do profissional de Tecnologia da Informação, dividida em três níveis:
      <table border="0" width="1000" class="avaliacao">
        <tr class="avaliacao">
          <th class="avaliacao" width="150"></th>
          <th class="avaliacao" width="850">Nível 1: Aprendiz</th>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Autonomia</th>
          <td class="avaliacao">Trabalha sob supervisão próxima. Tem pouca discrição. Procura por orientação e ajuda em situações inesperadas.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Influência</th>
          <td class="avaliacao">Interage com o departamento.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Complexidade</th>
          <td class="avaliacao">Realiza atividades de rotina em um ambiente estruturado. Requer assistência na resolução de problemas inesperados.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Habilidades do Negócio</th>
          <td class="avaliacao">Usa sistemas básicos de informação e funções de tecnologia, aplicações, e processos. Demonstra uma abordagem organizada para o trabalho. Capaz de aprender novas habilidades e aplicar conhecimentos adquiridos recentemente. Habilidades básicas de comunicação oral e escrita. Contribui para a identificação das próprias oportunidades de desenvolvimento.</td>
        </tr>
        <tr class="avaliacao">
          <td class="avaliacao" colspan="2">&nbsp;</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao" width="150"></th>
          <th class="avaliacao" width="850">Nível 2: Assistente</th>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Autonomia</th>
          <td class="avaliacao">Trabalha sob supervisão de rotina. Tem boa discrição na resolução problemas ou perguntas. Trabalha sem auxílio frequente dos outros.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Influência</th>
          <td class="avaliacao">Interage com o departamento e pode influenciá-lo. Pode ter algum contato externo com clientes e fornecedores. Pode ter mais influência em seus próprios domínios.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Complexidade</th>
          <td class="avaliacao">Executa uma variedade de atividades de trabalho em diversos ambientes estrurados.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Habilidades do Negócio</th>
          <td class="avaliacao">Entende e usa ferramentas e aplicativos com métodos adequados. Demonstra uma abordagem racional e organizada para o trabalho. Consciente de problemas de saúde e segurança. Identifica e negocia o desenvolvimento próprío de oportunidades. Capacidades de comunicação suficientes para um diálogo efetivo com colegas. Capaz de trabalhar em equipe. Capaz de planejar, agendar e monitorar trabalho próprio dentro de horizonte de curto prazo. Pode absorver informações técnicas quando apresentado de forma sistemática e aplicando-o efetivamente.</td>
        </tr>
        <tr class="avaliacao">
          <td class="avaliacao" colspan="2">&nbsp;</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao" width="150"></th>
          <th class="avaliacao" width="850">Nível 3: Expert</th>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Autonomia</th>
          <td class="avaliacao">Trabalha sob supervisão geral. Tem discrição na identificação e resolve problemas e atribuições complexas. Dada uma instrução específica, o andamento do trabalho pode ser revisado em marcos frequentes. Determina quando os problemas devem ser escalados para um nível mais alto.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Influência</th>
          <td class="avaliacao">Interage e influencia os membros da equipe do departamento/projeto. Contato externo frequente com clientes e fornecedores. Pode supervisionar os outros em áreas previsíveis e estruturadas. As decisões podem afetar o trabalho atribuído indivíduos/fases do projeto.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Complexidade</th>
          <td class="avaliacao">Atua em uma ampla gama de trabalho, às vezes complexa e não rotineira, em uma boa variedade de ambientes.</td>
        </tr>
        <tr class="avaliacao">
          <th class="avaliacao">Habilidades do Negócio</th>
          <td class="avaliacao">Entende e usa ferramentas e aplicativos adequados do método. Demonstra abordagem analítica e sistemática para a resolução de problemas. Toma iniciativa para identificar e negociar o desenvolvimento adequado das oportunidades. Demonstra habilidades efetivas de comunicação. Contribui plenamente ao trabalho dos times/equipes. Pode planejar, agendar e monitorar seu próprio trabalho (e a dos outros, quando aplicável) com competência dentro de prazos limitados e de acordo com os procedimentos de saúde e segurança. É capaz de absorver e aplicar novas informações técnicas. É capaz de trabalhar com os padrões requeridos e de entender e usar os métodos, ferramentas e aplicações apropriados. Aprecia um campo mais amplo de sistemas de informação, como seu próprio papel se relaciona com outros papéis e com os negócios do empregador ou cliente.</td>
        </tr>
      </table>
      <br/>
    </td>
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