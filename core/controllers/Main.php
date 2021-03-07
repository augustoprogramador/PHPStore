<?php

    namespace core\controllers;

    use core\classes\Database;
    use core\classes\EnviarEmail;
    use core\classes\Store;
    use core\models\Clientes;
    use core\models\Produtos;

    class Main {

        // ===========================================================
        public function index() {

            Store::layout([
                'layouts/html_header',
                'layouts/header',
                'inicio',
                'layouts/footer',
                'layouts/html_footer'
            ]);

        }

        // ===========================================================
        public function loja() {

            // apresenta a página da loja

            // buscar a lista de produtos dispoíveis
            $produtos = new Produtos();

            // analisa que categoria é para mostrar

            $c = 'todos';
            if (isset($_GET['c'])) {
                $c = $_GET['c'];
            }

            // buscar inforamção à base de dados
            $lista_produtos = $produtos->lista_produtos_disponiveis($c);
            $lista_categorias = $produtos->lista_categorias();

            $dados = [
                'produtos' => $lista_produtos,
                'categorias' => $lista_categorias,
            ];

            Store::layout([
                'layouts/html_header',
                'layouts/header',
                'loja',
                'layouts/footer',
                'layouts/html_footer'
            ], $dados);

        }

        // ===========================================================
        public function novo_cliente() {

            // verifica se já existe sessao aberta
            if (Store::clienteLogado()) {
                $this->index();
                return;
            }

            // apresenta o layout para criar um novo utilizador
            Store::layout([
                'layouts/html_header',
                'layouts/header',
                'criar_cliente',
                'layouts/footer',
                'layouts/html_footer'
            ]);

        }

        // ===========================================================
        public function criar_cliente() {

            // echo "<pre>";
            // print_r($_POST);

            // verifica se já existe sessao aberta
            if (Store::clienteLogado()) {
                $this->index();
                return;
            }

            // verifica se houve submissão de um formulário
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $this->index();
                return;
            }

            // criação do novo cliente

            //verifica se senha 1 == senha 2
            if ($_POST['text_senha_1'] != $_POST['text_senha_2']) {

                // as senhas são diferentes
                $_SESSION['erro'] = 'As senhas não estão iguais!';
                $this->novo_cliente();
                return;

            }
            
            // verifica na base de dados se existe cliente com o mesmo email
            
            $cliente = new Clientes();

            if ($cliente->verificar_email_registrado(trim(strtolower($_POST['text_email'])))) {

                $_SESSION['erro'] = 'Conta já registrada com esse email!';
                $this->novo_cliente();
                return;

            }

            // inserir novo cliente na base de dados e devolver o purl
            $purl = $cliente->registrar_cliente();
            $email_cliente = trim(strtolower($_POST['text_email']));

            // envio do email para o cliente
            $email = new EnviarEmail();
            $resultado = $email->enviar_email_conf_novo_cliente($email_cliente, $purl);

            if ($resultado) {
                
                // apresenta o layout para informar que o cliente foi cadastrado
                Store::layout([
                    'layouts/html_header',
                    'layouts/header',
                    'criar_cliente_sucesso',
                    'layouts/footer',
                    'layouts/html_footer'
                ]);
                return;
                
            } else {
                echo "Aconteceu um erro";
            }

        }

        // ===========================================================
        public function confirmar_email() {

            // verifica se já existe sessao aberta
            if (Store::clienteLogado()) {
                $this->index();
                return;
            }

            // verificar se existe na query string um purl
            if (!isset($_GET['purl'])) {
                $this->index();
                return;
            }

            $purl = $_GET['purl'];
            
            // verifica se o purl é válido
            if (strlen($purl) != 12) {
                $this->index();
                return;
            }

            $cliente = new Clientes();
            $resultado = $cliente->validar_email($purl);

            if ($resultado) {
                
                // apresenta o layout para informar que a conta foi confirmada
                Store::layout([
                    'layouts/html_header',
                    'layouts/header',
                    'conta_confirmada_sucesso',
                    'layouts/footer',
                    'layouts/html_footer'
                ]);
                return;

            } else {
                
                // redirecionar para a página inicial
                Store::redirect();

            }

        }

        // ===========================================================
        public function login() {

            // Verificar se já existe um utilizador logado
            if(Store::clienteLogado()) {
                Store::redirect();
                return;
            }

            // apresenta o formulário de login
            Store::layout([
                'layouts/html_header',
                'layouts/header',
                'login_frm',
                'layouts/footer',
                'layouts/html_footer'
            ]);

        }

        // ===========================================================
        public function login_submit() {

            // Verificar se o login é válido
            if(Store::clienteLogado()) {
                Store::redirect();
                return;
            }

            // verifica se foi efetuado o post do formulário de login
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                Store::redirect();
                return;
            }

            // validar se os campos vieram corretamente preenchidos
            if (
                !isset($_POST['text_usuario']) || 
                !isset($_POST['text_password']) || 
                !filter_var(trim($_POST['text_usuario']), FILTER_VALIDATE_EMAIL)
                ) {
                // erro de preenchimento do formulário
                $_SESSION['erro'] = 'Login inválido.';
                Store::redirect('login');
                return;
            }

            // prepara os dados para o model
            $usuario = trim(strtolower($_POST['text_usuario']));
            $senha = trim($_POST['text_password']);

            // carrega o model e verifica se login é válido
            $cliente = new Clientes();
            $resultado = $cliente->validar_login($usuario, $senha);

            // analisa o resultado
            if (is_bool($resultado)) {

                // login inválido
                $_SESSION['erro'] = 'Login inválido';
                Store::redirect('login');
                return;

            } else {

                // login válido. Coloca os dados na sessão
                $_SESSION['cliente'] = $resultado->id_cliente;
                $_SESSION['usuario'] = $resultado->email;
                $_SESSION['nome_cliente'] = $resultado->nome_completo;
                
                // redirecionar para o início da nossa loja
                if (isset($_SESSION['tmp_carrinho'])) {
                    // Store::printData($_SESSION);
                    // die('entrei na condição');
                    unset($_SESSION['tmp_carrinho']);
                    Store::redirect('carrinho');
                } else {
                    Store::redirect();
                }
            }

        }

        // ===========================================================
        public function logout() {

            // remove as variáveis da sessão
            unset($_SESSION['cliente']);
            unset($_SESSION['usuario']);
            unset($_SESSION['nome_cliente']);

            // redireciona para o início da loja
            Store::redirect();

        }

    }