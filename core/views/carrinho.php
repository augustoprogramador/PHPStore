<div class="container">
    <div class="row">
        <div class="col">
            <h3 class="my-3">A sua encomenda!</h3>
            <hr>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <?php if ($carrinho == null):?>
                <p class="text-center">Não existem itens no carrinho</p>
                <div class="mt-4 text-center">
                    <a href="?a=loja" class="btn btn-primary">Ir para a loja</a>
                </div>
            <?php else: ?>
                <div class="espaco-fundo">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Produto</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-end">Valor Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $index = 0;
                                $total_rows = count($carrinho);
                            ?>
                            <?php foreach($carrinho as $produto): ?>
                                <?php if ($total_rows - $index > 1): ?>
                                    <!-- Lista de produtos -->
                                    <tr>
                                        <td><img src="assets/images/produtos/<?= $produto['imagem'] ?>" class="img-fluid" width="80"></td>
                                        <td class="align-middle"><h5><?= $produto['titulo'] ?></h5></td>
                                        <td class="align-middle text-center"><?= $produto['quantidade'] ?></td>
                                        <td class="text-end align-middle">
                                            <!-- <h4>R$ < ?= str_replace('.', ',', $produto['preco']) ?></h4> -->
                                            <h4>R$<?= number_format($produto['preco'], 2, ',', '.') ?></h4>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a 
                                                href="?a=remover_item_carrinho&id_produto=<?= $produto['id_produto'] ?>" 
                                                class="btn btn-danger btn-sm"
                                            >
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $index++; ?>
                                <?php else: ?>
                                    <!-- Total -->
                                    <td></td>
                                    <td></td>
                                    <td class="text-end"><h3>Total:</h3></td>
                                    <td class="text-end">
                                        <!-- <h3>R$< ?= str_replace('.', ',', $produto) ?></h3> -->
                                        <h3>R$<?= number_format($produto, 2, ',', '.') ?></h3>
                                    </td>
                                    <td></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col">
                            <!-- <a href="?a=limpar_carrinho" class="btn btn-primary btn-sm">Limpar carrinho</a> -->
                            <button class="btn btn-primary btn-sm" onclick="limpar_carrinho()">Limpar carrinho</button>
                            <span class="ms-3" id="confirmar_limpar_carrinho">
                                Tem certeza?
                                <button class="btn btn-primary btn-sm" onclick="limpar_carrinho_off()">Não</button>
                                <a href="?a=limpar_carrinho" class="btn btn-danger btn-sm">Sim</a>
                            </span>
                        </div>
                        <div class="col text-end">
                            <a href="?a=loja" class="btn btn-primary btn-sm">Continuar a comprar</a>
                            <a href="?a=finalizar_encomenda" class="btn btn-primary btn-sm">Finalizar encomenda</a>
                        </div>
                    </div>
                    
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>