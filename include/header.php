<table border="0" width="1200">
  <tr>
    <td colspan="2" height="209" style="background-image:url(image/knowledge-header.jpg);text-align:center">
	  <h1>Compartilhamento de Experiência e Nivelamento de Competências</h1>
	  <h3>"Não se aprende bem a não ser pela experiência."<br/>Francis Bacon</h3>
	</td>
  </tr>
  <tr>
    <th style="text-align:left;"><?php
if(isset($_SESSION['usuario'])) {
  echo "Usuário: " . $_SESSION['login'] . " - " . $_SESSION['usuario'];
} else {
  echo "&nbsp;";
}
?></th>
    <th style="text-align:right;"><?php
echo date('d/m/Y H:i:s', time());
?></th>
  </tr>
</table>