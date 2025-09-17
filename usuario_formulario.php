<!DOCTYPE html>
<html>
    <head>
        <title>Usuário | Projeto para Web com PHP</title>
        <link rel="stylesheet" 
              href="lib/bootstrap-4.2.1-dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php include 'includes/topo.php'; ?>
                </div>
            </div>

            <div class="row" style="min-height: 500px;">
                <div class="col-md-12">
                    <?php include 'includes/menu.php'; ?>
                </div>
                <div class="col-md-10" style="padding-top: 50px;">
                    <?php
                        require_once 'includes/funcoes.php';
                        require_once 'core/conexao_mysql.php';
                        require_once 'core/sql.php';
                        require_once 'core/mysql.php';

                        if (isset($_SESSION['login'])) {
                            $id = (int) $_SESSION['login']['usuario']['id'];

                            $criterio = [
                                ['id', '=', $id]
                            ];

                            $retorno = buscar(
                                'usuario',
                                ['id', 'nome', 'email'],
                                $criterio
                            );

                            $entidade = $retorno[0];
                        }
                    ?>
                <h2>Usuário</h2>
<form method="post" action="core/usuario_repositorio.php">
    <input type="hidden" name="acao" value="<?php echo empty($id) ? 'insert' : 'update'; ?>" />
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>" />
    
    <div class="form-group">
        <label for="nome">Nome</label>
        <input class="form-control" type="text" name="nome" required="required" value="<?php echo isset($nome) ? $nome : ''; ?>" />
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input class="form-control" type="text" name="email" required="required" value="<?php echo isset($email) ? $email : ''; ?>" />
    </div>

    <div class="form-group">
        <label for="senha">Senha</label>
        <input class="form-control" type="password" name="senha" required="required" />
    </div>

    <div class="text-right">
        <button class="btn btn-success" type="submit">Salvar</button>
    </div>
</form>

<div class="row">
    <div class="col-md-12">
        <?php include 'includes/rodape.php'; ?>
    </div>
</div>

     <script src="lib/bootstrap-4.2.1-dist/js/bootstrap.min.js"></script>
    </body>
  </html>
