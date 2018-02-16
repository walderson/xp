<table border="0" width="1200">
  <tr>
    <td width="200" style="vertical-align:top;"><?php
include 'include/menu.php';
?></td>
    <td width="1000" style="vertical-align:top;">

<form name="frm" action="?operacao=incluirCompetenciaAction" method="POST" onsubmit="javascript: return validaForm(this);">
<table border="0" width="1000">
  <tr>
    <th colspan="4"><h2>Incluir Competência</h2></th>
  </tr>
  <tr>
    <td colspan="3">
      <strong>Unidade Organizacional:*</strong><br/>
      <?php combobox("uo", $conn, "uo", "sigla", "nome", null, true); ?>
      <style>
        select[name="uo"] {
          max-width: 770px;
        }
      </style>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td width="220">
      <strong>Sigla:*</strong><br/>
      <input type="text" name="sigla" maxlength="4" pattern="[A-Za-z]+" placeholder="Entre com a Sigla"
             required size="20" style="text-transform: uppercase;"
             title="Somente letras.">
    </td>
    <td width="440">
      <strong>Nome da Competência:*</strong><br/>
      <input type="text" name="competencia" maxlength="100" pattern="^[\S]+( [\S]+)*$" placeholder="Entre com a Nome da Competência"
             required size="50" title="Sem espaços em branco no começo ou no fim.">
    </td>
    <td width="170">
      <strong>Replicar:</strong><br/>
      <input type="radio" name="replicar" required title="Replicar para as Unidades Subordinadas" value="1"> Sim
      <input type="radio" name="replicar" required title="Não replicar para as Unidades Subordinadas" value="0" checked> Não
    </td>
    <td width="170">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:center;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
      <strong>Descrição:*</strong><br/>
      <textarea cols="120" name="descricao" placeholder="Entre com a Descrição" required rows="5"></textarea>
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
      <input type="submit" name="acao" value="   Incluir   ">
      <input type="reset" name="redefinir" onclick="javascript: document.frm.uo.focus();" value="   Redefinir   ">
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">
  document.frm.uo.focus();

  function validaForm(frm) {
    return window.confirm("Confirma a inclusão da Competência '" + frm.sigla.value.toUpperCase() + "'?");
  }
</script>

    </td>
  </tr>
</table>