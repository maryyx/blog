<!DOCTYPE html>
<html>
<head>
    <title>Página inicial | Projeto para Web com PHP</title>
    <link rel="stylesheet" href="lib/bootstrap-4.2.1-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <!-- Topo -->
        <div class="row">
            <div class="col-md-12">
                <?php include 'includes/topo.php'; ?>
            </div>
        </div>

        <!-- Menu -->
        <div class="row" style="min-height: 500px;">
            <div class="col-md-12">
                <?php include 'includes/menu.php'; ?>
            </div>

            <!-- Conteúdo -->
            <div class="col-md-10" style="padding-top: 50px;">
                <h2>Página Inicial</h2>
                <?php include 'includes/busca.php'; ?>
                <?php
                // Inclusão dos arquivos PHP essenciais
                require_once 'includes/funcoes.php';
                require_once 'core/conexao_mysql.php';
                require_once 'core/sql.php';
                require_once 'core/mysql.php';

                // Limpa os dados do $_GET
                foreach ($_GET as $indice => $dado) {
                    $$indice = limparDados($dado);
                }

                // Define data atual para o filtro
                $data_atual = date('Y-m-d H:i:s');

                // Critério base
                $criterio = [
                    ['data_postagem', '<=', $data_atual]
                ];

                // Adiciona filtro por busca, se houver
                if (!empty($busca)) {
                    $criterio[] = [
                        'AND',
                        'texto',
                        'like',
                        "%{$busca}%"
                    ];
                }

                // Busca os posts
                $posts = buscar(
                    'post',
                    [
                        'titulo',
                        'data_postagem',
                        'id',
                        '(SELECT nome FROM usuario WHERE usuario.id = post.usuario_id) AS nome'
                    ],
                    $criterio,
                    'data_postagem DESC'
                );
                ?>

                <!-- Exibe os posts -->
                <div>
                    <div class="list-group">
                        <?php foreach ($posts as $post): 
                            $data = date_create($post['data_postagem']);
                            $data = date_format($data, "d/m/Y H:i:s");
                        ?>
                            <a class="list-group-item list-group-item-action"
                               href="post_detalhe.php?post=<?php echo $post['id']; ?>">
                                <strong><?php echo $post['titulo']; ?></strong><br>
                                <?php echo $post['nome'] ?? ''; ?>
                                <span class="badge badge-dark"><?php echo $data; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="row">
            <div class="col-md-12">
                <?php include 'includes/rodape.php'; ?>
            </div>
        </div>
    </div>

    <script src="lib/bootstrap-4.2.1-dist/js/bootstrap.min.js"></script>
</body>
</html>
