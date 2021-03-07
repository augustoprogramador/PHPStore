<div class="container espaco-fundo">

    <!-- título da página -->
    <div class="row">
        <div class="col-12 text-center my-4">
            <a href="?a=loja&c=todos" class="btn btn-primary">Todos</a>

            <?php foreach ($categorias as $categoria): ?>
                <a href="?a=loja&c=<?= $categoria ?>" class="btn btn-primary">
                    <?= ucfirst(preg_replace('/\_/', ' ', $categoria)); ?>
                </a>
            <?php endforeach; ?>

            <!-- <a href="?a=loja&c=homem" class="btn btn-primary">Homem</a>
            <a href="?a=loja&c=mulher" class="btn btn-primary">Mulher</a> -->
        </div>
    </div>
    
    <!-- produtos -->
    <div class="row">

        <?php if (count($produtos) == 0): ?>
            <div class="text-center my-5">
                <h3>Não existem produtos disponíveis.</h3>
            </div>
        <?php else: ?>

            <!-- ciclo de apresentação dos produtos -->
            <?php foreach($produtos as $produto): ?>
                <div class="col-sm-4 col-6 p-2">
                    <!-- <div class="text-center p-3 card"> -->
                    <div class="text-center p-3 box-produto">
                        <img class="img-fluid" src="./assets/images/produtos/<?= $produto->imagem ?>">
                        <h3><?= $produto->nome_produto ?></h3>
                        <h2><?= preg_replace("/\./", ",", $produto->preco . "$") ?></h2>
                        <div>
                            <?php if ($produto->stock > 0): ?>
                                <button 
                                    class="btn btn-info btn-sm"
                                    onclick="adicionar_carrinho(<?= $produto->id_produto ?>)"
                                >
                                        Adicionar ao carrinho
                                    <i class="fas fa-shopping-cart me-2"></i>
                                </button>
                                <?php else: ?>
                                    <button 
                                        class="btn btn-danger btn-sm"
                                    >
                                            Sem estoque
                                        <i class="fas fa-shopping-cart me-2"></i>
                                    </button>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</div>

<!-- 
    
[id_produto] => 1
[categoria] => homem
[nome_produto] => Tshirt Vermelha
[descricao] => Lorem ipsum dolor sit amet consectetur adipisicing
[imagem] => tshirt_vermelha.png
[preco] => 45.70
[stock] => 100
[visivel] => 1
[created_at] => 2021-03-06 09:58:58
[updated_at] => 2021-03-06 09:58:58
[deleted_at] => 2021-03-06 09:58:58

 -->