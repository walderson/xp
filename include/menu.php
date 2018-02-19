<a href="?operacao=home" title="Ir para a página inicial">[Home]</a><br/>
<a href="?operacao=alterarSenha" title="Alterar a sua senha atual">[Alterar Senha]</a><br/>
<?php
if (isset($_SESSION['administrador']) && $_SESSION['administrador'] == 1) {
?>
<a href="?operacao=incluirUO" title="Incluir uma nova Unidade Organizacional">[Incluir UO]</a><br/>
<a href="?operacao=pesquisarUO" title="Pesquisar Unidades Organizacionais cadastradas">[Pesquisar UO]</a><br/>
<a href="?operacao=estruturaUO" title="Visualizar Estrutura Organizacional">[Organograma]</a><br/>
<a href="?operacao=incluirUsuario" title="Incluir um novo Usuário">[Incluir Usuário]</a><br/>
<a href="?operacao=pesquisarUsuario" title="Pesquisar Usuários cadastrados">[Pesquisar Usuário]</a><br/>
<a href="?operacao=incluirCompetencia" title="Incluir uma nova Competência">[Incluir Competência]</a><br/>
<a href="?operacao=pesquisarCompetencia" title="Pesquisar Competências cadastradas">[Pesquisar Competência]</a><br/>
<a href="?operacao=gerarAvaliacoes" title="Gerar Avaliações para a autoavaliação dos colaboradores">[Gerar Avaliações]</a><br/>
<a href="?operacao=pesquisarAvaliacao" title="Pesquisar Avaliações geradas">[Pesquisar Avaliação]</a><br/>
<a href="?operacao=matrizCompetencias" title="Visualizar Matriz de Competências">[Matriz Competências]</a><br/>
<?php
} else if (isset($_SESSION['gestor']) && count($_SESSION['gestor']) > 0) {
?>
<a href="?operacao=estruturaUO" title="Visualizar Estrutura Organizacional">[Organograma]</a><br/>
<a href="?operacao=matrizCompetencias" title="Visualizar Matriz de Competências">[Matriz Competências]</a><br/>
<?php
}
?>
<a href="?operacao=minhasAvaliacoes" title="Listar as Avaliações existentes">[Minhas Avaliações]</a><br/>
<a href="?operacao=logout" title="Sair da Aplicação">[Logout]</a><br/>