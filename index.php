<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Experiência</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="icon" href="favicon.ico" type="image/x-icon"/>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
  <link rel="stylesheet" type="text/css" href="style/xp.css">
</head>
<body>
<?php
  date_default_timezone_set('America/Cuiaba');

  //Inclui funções reutilizadas pelos arquivos incluídos
  include_once 'include/arrayUO.php';
  include_once 'include/combobox.php';
  include_once 'include/datalist.php';
  include_once 'include/getId.php';
  
  //Habilita o uso de variáveis de sessão
  session_start();

  //Variável para verificar a necessidade de efetuar login
  $acessoDireto = false;
  if (isset($_GET['operacao'])) {
    switch ($_GET['operacao']) {
      case "login":
      case "loginAction":
      case "logout":
      case "redefinirSenha":
      case "redefinirSenhaAction":
      case "avaliacao":
      case "avaliacaoForm":
      case "avaliacaoAction":
        $acessoDireto = true;
        break;
    }
  }

  //Inicia a conexão com o banco de dados
  include 'include/connect.php';

  //Exibe o cabeçalho do sistema
  include 'include/header.php';

  if ($acessoDireto) {
    //Funcionalidades que podem ser acessadas diretamente, sem necessidade de login
    switch ($_GET['operacao']) {
      case "login":
        include 'include/login.php';
        break;
      case "loginAction":
        include 'include/loginAction.php';
        break;
      case "logout":
        include 'include/login.php';
        break;
      case "redefinirSenha":
        include 'include/redefinirSenha.php';
        break;
      case "redefinirSenhaAction":
        include 'include/redefinirSenhaAction.php';
        break;
      case "avaliacao":
        include 'include/avaliacao.php';
        break;
      case "avaliacaoForm":
        include 'include/avaliacaoForm.php';
        break;
      case "avaliacaoAction":
        include 'include/avaliacaoAction.php';
        break;
      default:
        include 'include/login.php';
    }
  } else {
    //Verifica se existe sessão de usuário
    if(isset($_SESSION['login'])) {
      if (isset($_GET['operacao'])) {
        $administrador = false;
        if (isset($_SESSION['administrador']) && $_SESSION['administrador'] == 1)
          $administrador = true;
        switch ($_GET['operacao']) {
          case "home":
            include 'include/home.php';
            break;
          case "alterarSenha":
            include 'include/alterarSenha.php';
            break;
          case "alterarSenhaAction":
            include 'include/alterarSenhaAction.php';
            break;
          case "incluirUO":
            if ($administrador)
              include 'include/incluirUO.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "incluirUOAction":
            if ($administrador)
              include 'include/incluirUOAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "pesquisarUO":
            if ($administrador)
              include 'include/pesquisarUO.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "visualizarUO":
            if ($administrador || (isset($_SESSION['gestor']) && count($_SESSION['gestor']) > 0))
              include 'include/visualizarUO.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "alterarUO":
            if ($administrador)
              include 'include/alterarUO.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "alterarUOAction":
            if ($administrador)
              include 'include/alterarUOAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "excluirUO":
            if ($administrador)
              include 'include/excluirUO.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "estruturaUO":
            if ($administrador || (isset($_SESSION['gestor']) && count($_SESSION['gestor']) > 0))
              include 'include/estruturaUO.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "incluirUsuario":
            if ($administrador)
              include 'include/incluirUsuario.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "incluirUsuarioAction":
            if ($administrador)
              include 'include/incluirUsuarioAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "pesquisarUsuario":
            if ($administrador)
              include 'include/pesquisarUsuario.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "visualizarUsuario":
            if ($administrador)
              include 'include/visualizarUsuario.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "alterarUsuario":
            if ($administrador)
              include 'include/alterarUsuario.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "alterarUsuarioAction":
            if ($administrador)
              include 'include/alterarUsuarioAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "excluirUsuario":
            if ($administrador)
              include 'include/excluirUsuario.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "incluirCompetencia":
            if ($administrador)
              include 'include/incluirCompetencia.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "incluirCompetenciaAction":
            if ($administrador)
              include 'include/incluirCompetenciaAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "pesquisarCompetencia":
            if ($administrador)
              include 'include/pesquisarCompetencia.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "visualizarCompetencia":
            if ($administrador)
              include 'include/visualizarCompetencia.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "alterarCompetencia":
            if ($administrador)
              include 'include/alterarCompetencia.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "alterarCompetenciaAction":
            if ($administrador)
              include 'include/alterarCompetenciaAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "excluirCompetencia":
            if ($administrador)
              include 'include/excluirCompetencia.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "gerarAvaliacoes":
            if ($administrador)
              include 'include/gerarAvaliacoes.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "revisarAvaliacaoAction":
            if ($administrador || (isset($_SESSION['gestor']) && count($_SESSION['gestor']) > 0))
              include 'include/revisarAvaliacaoAction.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "pesquisarAvaliacao":
            if ($administrador)
              include 'include/pesquisarAvaliacao.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "matrizCompetencias":
            if ($administrador || (isset($_SESSION['gestor']) && count($_SESSION['gestor']) > 0))
              include 'include/matrizCompetencias.php';
            else
              include 'include/erroAdministrador.php';
            break;
          case "minhasAvaliacoes":
            include 'include/minhasAvaliacoes.php';
            break;
          default:
            include 'include/home.php';
        }
      } else {
        include 'include/home.php';
      }
    } else {
      if (isset($_GET['operacao']))
        $msgErro = "Sessão expirada ou login não efetuado.\n\nFavor autenticar-se.";
      include 'include/login.php';
    }
  }

  //Fecha a conexão com o banco de dados
  include 'include/close.php';
?>
</body>
</html>