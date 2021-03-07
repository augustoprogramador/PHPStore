<?php

    namespace core\controllers;

    use core\classes\Database;
    use core\classes\EnviarEmail;
    use core\classes\Store;
    use core\models\Clientes;
    use core\models\Produtos;

    class Carrinho {
        
        // ==========================================================================
        public function adicionar_carrinho() {
            
            // vai buscar o id_produto à query string
            if (!isset($_GET['id_produto'])) {

                echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '';
                return;

            }

            // define o id do produto
            $id_produto = $_GET['id_produto'];

            $produtos = new Produtos();
            $resultados = $produtos->verificar_estoque($id_produto);

            if (!$resultados) {
                
                echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '';
                return;

            }

            // adiciona/gestão da variável de SESSÃO do carrinho
            $carrinho = [];

            if (isset($_SESSION['carrinho'])) {
                $carrinho = $_SESSION['carrinho'];
            }

            // adicionar o produto ao carrinho
            if (key_exists($id_produto, $carrinho)) {

                // já existe um produto. Acrescenta mais uma unidade
                $carrinho[$id_produto]++;

            } else {

                // adicionar novo produto ao carrinho
                // array_push($carrinho, [$id_produto => 1]);
                $carrinho[$id_produto] = +1;

            }

            // atualiza os dados do carrinho na sessão
            $_SESSION['carrinho'] = $carrinho;

            // devolve a responsta (número de produtos do carrinho)
            $total_produtos = 0;
            foreach ($carrinho as $quantidade) {
                $total_produtos += $quantidade;
            }

            echo $total_produtos;
        }
        
        // ==========================================================================
        public function remover_item_carrinho() {
            
            // vai buscar o id_produto na query string
            $id_produto = $_GET['id_produto'];
            
            // buscar o carrinho à sessão
            $carrinho = $_SESSION['carrinho'];

            // remover o produto do carrinho
            unset($carrinho[$id_produto]);

            // atualizar o carrinho na sessão
            $_SESSION['carrinho'] = $carrinho;
            
            // apresentar novamente a página do carrinho
            $this->carrinho();

        }

        // ==========================================================================
        public function limpar_carrinho() {

            // limpa o carrinho de todos os produtos
            unset($_SESSION['carrinho']);

            // refrescar a página do carrinho
            $this->carrinho();

        }

        // ==========================================================================
        public function carrinho() {

            // verificar se existe carrinho
            if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
                $dados = [
                    'carrinho' => null
                ];
            } else {

                // id busar à bd os dados dos produtos que existem no carrinho
                // criar um ciclo que constrói a estrutura dos dados para o carrinho

                $ids = [];
                // Store::printData($_SESSION['carrinho']);
                // $key corresponde a chave e value a quantidade dos produtos
                foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {
                    
                    array_push($ids, $id_produto);

                }

                $ids = implode(",", $ids);
                $produtos = new Produtos();
                $resultados = $produtos->buscar_produtos_por_ids($ids);

                // fazer um ciclo por cada produto no carrinho
                    // - identificar o id e usar os dados da bd para criar uma coleção de dados para a página do carrinho

                    // imagem | titulo | quantidade | preço | xxx
                
                $dados_temp = [];

                foreach ($_SESSION['carrinho'] as $id_produto => $quantidade_carrinho) {

                    // imagem do produto
                    foreach ($resultados as $produto) {
                        if ($produto->id_produto == $id_produto) {
                            $id_produto = $produto->id_produto;
                            $imagem = $produto->imagem;
                            $titulo = $produto->nome_produto;
                            $quantidade = $quantidade_carrinho;
                            $preco = $quantidade * $produto->preco;

                            // colocar o produto na coleção
                            array_push($dados_temp, [
                                'id_produto' => $id_produto,
                                'imagem' => $imagem,
                                'titulo' => $titulo,
                                'quantidade' => $quantidade,
                                'preco' => $preco,
                            ]);

                            break; // vai dar merda
                        }
                    }

                }

                // calcular o total
                $total_da_compra = 0;
                foreach ($dados_temp as $item) {
                    $total_da_compra += $item['preco'];
                }

                array_push($dados_temp, $total_da_compra);

                $dados = [
                    'carrinho' => $dados_temp
                ];

                // Store::printData($dados);

            }

            // apresenta a página do carrinho
            Store::layout([
                'layouts/html_header',
                'layouts/header',
                'carrinho',
                'layouts/footer',
                'layouts/html_footer'
            ], $dados);

        }

        // ==========================================================================
        public function finalizar_encomenda() {

            // verifica se existe cliente logado
            if (!isset($_SESSION['cliente'])) {

                // coloca na sessão um referrer temporário
                $_SESSION['tmp_carrinho'] = true;

                // redirecionar para o quadro de login
                Store::redirect('login');

            }

            // Store::printData($_SESSION);

            // verificar se existe cliente logado
                // não existe?
                    // - colocar um "referrer" na sessão 
                    // - abrir o quadro login
                    // - após o login com sucesso, regressar á loja
                    // - remover o "referrer" da sessão
                // existe
                    // passo 2 (confirmar compra)

        }
    }