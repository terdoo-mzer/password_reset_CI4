<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FormModel;
use Ramsey\Uuid\Uuid;

class FormController extends BaseController
{
    function signup()
    {

        $formModel = new FormModel();
        $data = [];
        $uuid = uniqid();
        if ($this->request->getPost()) {

            $rules = [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                $data['errors'] = $this->validator->getErrors();
            } else {
                $name = $this->request->getVar('name');
                $email = $this->request->getVar('email');
                $pwd = $this->request->getVar('password');


                // Insert data into db
                if ($formModel->insertUser($uuid,$name, $email, $pwd)) {
                    // Redirect user to login page
                    return redirect()->to('/login');
                };
            }
        }
        return view('templates/header')
            . view('signup', $data)
            . view('templates/footer');
    }

    function login()
    {
        return view('templates/header')
            . view('login')
            . view('templates/footer');
    }

    // function sendMail()
    // {

    //     ini_set('display_errors', 1);
    //     error_reporting(E_ALL);
    //     //Create an instance; passing `true` enables exceptions
    //     $mail = new PHPMailer(true);

    //     try {
    //         //Server settings
    //         $mail = new PHPMailer();
    //         $mail->Encoding = "base64";
    //         $mail->SMTPAuth = true;
    //         $mail->Host = "smtp.zeptomail.com";
    //         $mail->Port = 587;
    //         $mail->Username = "emailapikey";
    //         $mail->Password = 'wSsVR60gqEbwC6h/zWarI7hszwsGUVnwE0R62VekuSD6Fv7K9MdulELNAQL0SKdNGWdvRmMX8ukqy0gG22dd3NQknF4ICiiF9mqRe1U4J3x17qnvhDzNWW9YlxeLJYIBzgxtk2FlEMxu';
    //         $mail->SMTPSecure = 'TLS';
    //         $mail->isSMTP();
    //         $mail->IsHTML(true);
    //         $mail->CharSet = "UTF-8";

    //         // $mail->addAddress('admin@creditorcash.com.ng');
    //         // $mail->Body = "Test email sent successfully.";
    //         // $mail->Subject = "Test Email";
    //         $mail->SMTPDebug = 1;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //         //Recipients
    //         $mail->From = "noreply@cashbridge.africa";
    //         $mail->addAddress('mzerterdoo6@gmail.com', 'Joe User');     //Add a recipient
    //         $mail->addReplyTo('info@example.com', 'Information');
    //         $mail->addCC('cc@example.com');
    //         $mail->addBCC('bcc@example.com');

    //         //Attachments
    //         // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //         // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //         //Content
    //         $mail->isHTML(true);                                  //Set email format to HTML
    //         $mail->Subject = 'Here is the subject';
    //         $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    //         $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    //         $mail->send();
    //         echo 'Message has been sent';
    //     } catch (Exception $e) {
    //         echo "<pre>";
    //         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    //     }
    // }
}
