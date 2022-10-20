<?php
    //https://www.espai.es/blog/2022/06/phpmailer-ya-no-envia-correos-a-traves-de-gmail/
    //https://codigosdeprogramacion.com/2022/04/05/enviar-correo-electronico-con-phpmailer/

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';

    class Mailer{
        static function sendVerificationMail($destination, $user){
            try {
                $mail = new PHPMailer();
                //Configuración del servidor
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;             //Habilitar los mensajes de depuración
                $mail->isSMTP();                                   //Enviar usando SMTP
                $mail->Host       = 'smtp.gmail.com';            //Configurar el servidor SMTP
                $mail->SMTPAuth   = true;                          //Habilitar autenticación SMTP
                $mail->Username   = 'minesweeper2077@gmail.com';            //Nombre de usuario SMTP
                $mail->Password   = 'dbvkdidporwicehh';                      //Contraseña SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   //Habilitar el cifrado TLS
                $mail->Port       = 465;                           //Puerto TCP al que conectarse; use 587 si configuró `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Emisor
                $mail->setFrom('minesweeper2077@gmail.com', 'The Glorious Master of Minesweeper');
            
                //Destinatarios
                $mail->addAddress($destination, $user);     //Añadir un destinatario, el nombre es opcional
            
                //Destinatarios opcionales
                // $mail->addReplyTo('info@example.com', 'Information');  //Responder a
                // $mail->addCC('cc@example.com');                        //Copia pública
                // $mail->addBCC('bcc@example.com');                      //Copia oculta
            
                //Archivos adjuntos
                // $mail->addAttachment('files/comunicado.pdf', 'Comunicado');         //Agregar archivos adjuntos, nombre opcional
            
                //Nombre opcional
                $mail->isHTML(true);                         //Establecer el formato de correo electrónico en HTMl
                $mail->Subject = 'Email verification';             
                $mail->Body    = 'Dear '.$user.', please verify your email: <a href="http://127.0.0.1:9090/verification/?email='.$destination.'">VERIFY HERE</a>';
                $mail->AltBody = '';
            
                $mail->send();    //Enviar correo eletrónico
                // echo 'El mensaje ha sido enviado';
            } catch (Exception $e) {
                // echo "No se pudo enviar el mensaje. Error de correo: {$mail->ErrorInfo}";
            }
        }
    }