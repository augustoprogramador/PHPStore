<?php

    namespace core\classes;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    

    class EnviarEmail{

        // =========================================================================
        public function enviar_email_conf_novo_cliente($email_cliente, $purl) {

            // envia um email para o novo cliente no sentido de confirmar o email
            $mail = new PHPMailer(true);

            // constrói o purl (link para validação do email)
            $link = BASE_URL . '?a=confirmar_email&purl=' . $purl;

            try {
                // Configurações do servidor
                // $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = EMAIL_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = EMAIL_FROM;
                $mail->Password   = EMAIL_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = EMAIL_PORT;
                $mail->CharSet = 'UTF-8';

                // Emissor e receptor
                $mail->setFrom(EMAIL_FROM, APP_NAME);
                $mail->addAddress($email_cliente);

                // Assunto
                $mail->isHTML(true);
                $mail->Subject = APP_NAME . ' - Confirmação de email';
                
                // mensagem
                $html = '<p>Seja bem-vindo à nossa loja' . APP_NAME . '!!!</p>';
                $html .= '<p>Para poder entrar na nossa loja, precisa confirmar o seu email.</p>';
                $html .= '<p>Para confirmar o email, clique no link abaixo:.</p>';
                $html .= '<p><a href="' . $link . '">Confirmar Email</a></p>';
                $html .= '<p><em><small>' . APP_NAME . '</small></em></p>';
                $mail->Body    = $html;

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }

        }

    }