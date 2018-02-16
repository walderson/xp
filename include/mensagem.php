<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
if(isset($_SESSION['usuario'])) {
  include 'include/menu.php';
} else echo "&nbsp;";
?></td>
    <td width="1000" style="vertical-align:top;">

<table border="0" width="1000">
  <tr>
    <th colspan="3"><h2><?php echo $titulo; ?></h2></th>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td style="text-align:center;" width="600">
      <div style="color:#0000ff;"><?php echo $msg; ?></div>
      <div style="color:#ff0000;"><?php echo $msgErro; ?></div>
    </td>
    <td width="200">&nbsp;</td>
  </tr>
</table>

	</td>
  </tr>
</table>
