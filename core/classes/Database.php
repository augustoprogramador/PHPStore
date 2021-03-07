<?php

    namespace core\classes;

    use Exception;
    use PDO;
    use PDOException;

    class Database {

        private $ligacao;

        // ==================================================
        private function ligar() {
            /*
            1 - Ligar
            2 - Comunicar
            3 - Desligar
            */

            $this->ligacao = new PDO(
                'mysql:'.
                'host='.MYSQL_SERVER.';'.
                'dbname='.MYSQL_DATABASE.';'.
                'charset='.MYSQL_CHARSET,
                MYSQL_USER,
                MYSQL_PASS,
                array(PDO::ATTR_PERSISTENT => true)
            );

            // debug
            $this->ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        }
        
        // ==================================================
        private function desligar() {
            
            // desliga-se a base de dados
            $this->ligacao = null;

        }

        // ==================================================
        // CRUD
        // ==================================================

        // ==================================================
        // SELECT
        // ==================================================
        public function select($sql, $parametros = null){

            $sql = trim($sql);
            // verifica se é uma instrução SELECT
            // preg_match - Permite comparar uma string com uma expressão regular
            if (!preg_match("/^SELECT/i", $sql)) {
                throw new Exception('Base de dados - Não é uma instrução select.');
                // die('Base de dados - Não é uma instrução select.');
            }

            // liga
            $this->ligar();

            $resultados = null;

            // comunicar
            try {

                // comunicação com a BD
                if (!empty($parametros)) { // Se existirem parâmetros
                    $executar = $this->ligacao->prepare($sql); // constrói a query
                    $executar->execute($parametros); // executa com os parâmetros recebidos
                    $resultados = $executar->fetchAll(PDO::FETCH_CLASS); // devolve os resultados como objeto
                } else {
                    $executar = $this->ligacao->prepare($sql);
                    $executar->execute();
                    $resultados = $executar->fetchAll(PDO::FETCH_CLASS);
                }

            } catch (PDOException $e){

                // caso exista erro
                return false;

            }

            // desliga da BD
            $this->desligar();

            // devolver os resultados obtidos
            return $resultados;

        }

        // ==================================================
        // INSERT
        // ==================================================
        public function insert($sql, $parametros = null){

            $sql = trim($sql);
            // verifica se é uma instrução INSERT
            // preg_match - Permite comparar uma string com uma expressão regular
            if (!preg_match("/^INSERT/i", $sql)) {
                throw new Exception('Base de dados - Não é uma instrução insert.');
                // die('Base de dados - Não é uma instrução select.');
            }

            // liga
            $this->ligar();

            // comunicar
            try {

                // comunicação com a BD
                if (!empty($parametros)) { // Se existirem parâmetros
                    $executar = $this->ligacao->prepare($sql); // constrói a query
                    $executar->execute($parametros); // executa com os parâmetros recebidos
                } else {
                    $executar = $this->ligacao->prepare($sql);
                    $executar->execute();
                }

            } catch (PDOException $e){

                // caso exista erro
                return false;

            }

            // desliga da BD
            $this->desligar();

        }
        
        // ==================================================
        // UPDATE
        // ==================================================
        public function update($sql, $parametros = null){

            $sql = trim($sql);
            // verifica se é uma instrução UPDATE
            // preg_match - Permite comparar uma string com uma expressão regular
            if (!preg_match("/^UPDATE/i", $sql)) {
                throw new Exception('Base de dados - Não é uma instrução update.');
                // die('Base de dados - Não é uma instrução select.');
            }

            // liga
            $this->ligar();

            // comunicar
            try {

                // comunicação com a BD
                if (!empty($parametros)) { // Se existirem parâmetros
                    $executar = $this->ligacao->prepare($sql); // constrói a query
                    $executar->execute($parametros); // executa com os parâmetros recebidos
                } else {
                    $executar = $this->ligacao->prepare($sql);
                    $executar->execute();
                }

            } catch (PDOException $e){

                // caso exista erro
                return false;

            }

            // desliga da BD
            $this->desligar();

        }
        
        // ==================================================
        // DELETE
        // ==================================================
        public function delete($sql, $parametros = null){

            $sql = trim($sql);
            // verifica se é uma instrução DELETE
            // preg_match - Permite comparar uma string com uma expressão regular
            if (!preg_match("/^DELETE/i", $sql)) {
                throw new Exception('Base de dados - Não é uma instrução delete.');
                // die('Base de dados - Não é uma instrução select.');
            }

            // liga
            $this->ligar();

            // comunicar
            try {

                // comunicação com a BD
                if (!empty($parametros)) { // Se existirem parâmetros
                    $executar = $this->ligacao->prepare($sql); // constrói a query
                    $executar->execute($parametros); // executa com os parâmetros recebidos
                } else {
                    $executar = $this->ligacao->prepare($sql);
                    $executar->execute();
                }

            } catch (PDOException $e){

                // caso exista erro
                return false;

            }

            // desliga da BD
            $this->desligar();

        }
        
        // ==================================================
        // GENÉRICA
        // ==================================================
        public function statement($sql, $parametros = null){

            $sql = trim($sql);
            // verifica se é uma instrução diferente das anteriores
            // preg_match - Permite comparar uma string com uma expressão regular
            if (preg_match("/^SELECT|INSERT|UPDATE|DELETE/i", $sql)) {
                throw new Exception('Base de dados - Instrução inválida.');
                // die('Base de dados - Não é uma instrução select.');
            }

            // liga
            $this->ligar();

            // comunicar
            try {

                // comunicação com a BD
                if (!empty($parametros)) { // Se existirem parâmetros
                    $executar = $this->ligacao->prepare($sql); // constrói a query
                    $executar->execute($parametros); // executa com os parâmetros recebidos
                } else {
                    $executar = $this->ligacao->prepare($sql);
                    $executar->execute();
                }

            } catch (PDOException $e){

                // caso exista erro
                return false;

            }

            // desliga da BD
            $this->desligar();

        }
        
    }

?>