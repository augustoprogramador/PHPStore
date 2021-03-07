<?php

    namespace core\models;
    use core\classes\Database;
    use core\classes\Store;

    class Clientes{

        // ===========================================================
        public function verificar_email_registrado($email) {

            // verifica se já existe outra conta com o mesmo email
            $bd = new Database();
            $parametros = [
                ':email' => trim(strtolower($email))
            ];

            // :campo_do_bd -> Identificação usada pelo PDO (PHP Data Object)
            $resultados = $bd->select("
                SELECT email FROM clientes WHERE email = :email
            ", $parametros);

            // se o cliente já existe
            if (count($resultados)) {
                return true;
            } else {
                return false;
            }

        }

        // ===========================================================
        public function registrar_cliente() {

            // registra um novo cliente na base de dados
            $bd = new Database();

            // cria uma hash para o registro do cliente
            $purl = Store::criarHash();
            
            // parâmetros
            $parametros = [
                ':email' => trim(strtolower($_POST['text_email'])),
                ':senha' => password_hash(trim($_POST['text_senha_1']), PASSWORD_DEFAULT),
                ':nome_completo' => trim($_POST['text_nome_completo']),
                ':endereco' => trim($_POST['text_endereco']),
                ':cidade' => trim($_POST['text_cidade']),
                ':telefone' => trim($_POST['text_telefone']),
                ':purl' => $purl,
                ':ativo' => 0
            ];
            $bd->insert("
                INSERT INTO clientes VALUES (
                    0,
                    :email,
                    :senha,
                    :nome_completo,
                    :endereco,
                    :cidade,
                    :telefone,
                    :purl,
                    :ativo,
                    NOW(),
                    NOW(),
                    NULL
                )
            ", $parametros);
            
            // retorna o purl criado
            return $purl;
        }

        // ===========================================================
        public function validar_email($purl) {

            // validar o email do novo cliente
            $bd = new Database();
            $parametros = [
                ':purl' => $purl
            ];
            $resultados = $bd->select("
                SELECT * FROM clientes 
                WHERE purl = :purl
            ", $parametros);

            if (count($resultados) != 1) {
                return false;
            }

            // foi encontrado este cliente com o purl indicado
            $id_cliente = $resultados[0]->id_cliente;

            $parametros = [
                ':id_cliente' => $id_cliente
            ];

            $bd->update("
                UPDATE clientes SET
                purl = NULL,
                ativo = 1,
                updated_at = NOW()
                WHERE id_cliente = :id_cliente
            ", $parametros);

            return true;
            
        }

        // ===========================================================
        public function validar_login($usuario, $senha) {

            // verificar se o login é válido
            $parametros = [
                ':usuario' => $usuario,
            ];

            $bd = new Database();
            $resultado = $bd->select("
                SELECT * FROM clientes 
                WHERE email = :usuario 
                AND ativo = 1 
                AND deleted_at IS NULL
            ", $parametros);
            
            if(count($resultado) != 1) {

                // não existe usuário
                return false;

            } else {

                // temos usuário. Vamos ver a sua password
                $usuario = $resultado[0];

                // verificar a password
                if(!password_verify($senha, $usuario->senha)) {
                    
                    // password inválida
                    return false;

                } else {

                    // login válido
                    return $usuario;

                }

            }

        }

    }