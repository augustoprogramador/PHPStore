<?php

    namespace core\classes;

use Exception;

class Store {
        
        // ===========================================================
        public static function layout($estruturas, $dados = null) {

            // verifica se estruturas é um array
            if (!is_array($estruturas)) {
                throw new Exception("Coleção de estruturas inválida.");
            }

            // variáveis
            if (!empty($dados) && is_array($dados)) {
                extract($dados); // transforma a chave do valor de um array em uma variável que armazena o respectivo valor.
            }

            // Apresentar as views da aplicação
            foreach ($estruturas as $estrutura) {
                include("../core/views/$estrutura.php");
            }

        }

        // ===========================================================
        public static function clienteLogado() {

            // verifica se existe um cliente com sessao
            return isset($_SESSION['cliente']);

        }

        // ===========================================================
        public static function criarHash($num_caracteres = 12) {

            // criar hashs
            $chars = '01234567890123456789abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ';            
            return substr(str_shuffle($chars), 0, $num_caracteres);

        }

        // ===========================================================
        public static function redirect($rota = '') {

            // faz o redirecionamento para a URL (rota) desejada
            header("Location: " . BASE_URL . "?a=$rota");

        }

        // ===========================================================
        public static function printData($data) {

            if (is_array($data) || is_object($data)) {

                echo "<pre>";
                print_r($data);

            } else {

                echo "<pre>";
                echo $data;

            }

            die('<br>Terminado.');

        }

    }

?>