<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FormModel;
use App\Models\ResetPasswordModel;
// use App\Models\getUser;
use Ramsey\Uuid\Uuid;
use Config\Services;

class FormController extends BaseController
{

    protected $helpers = ['url'];
    function signup()
    {

        $formModel = new FormModel();
        $data = [];
        $uuid = uniqid();
        if ($this->request->getPost()) {

            $rules = [
                'name' => 'required',
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                $data['errors'] = $this->validator->getErrors();
            } else {
                $name = $this->request->getVar('name');
                $email = $this->request->getVar('email');
                $pwd = $this->request->getVar('password');


                // Insert data into db
                if ($formModel->insertUser($uuid, $name, $email, $pwd)) {
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
        $formModel = new FormModel();
        $data = [];
        $session = session();

        if ($this->request->getPost()) {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            if (!$this->validate($rules)) {
                $data['errors'] = $this->validator->getErrors();
            } else {
                $email = $this->request->getVar('email');
                $pwd = $this->request->getVar('password');

                if ($formModel->getUser($email, $pwd)) {
                    // echo "It worked!";
                    $user = $formModel->getUser($email, $pwd);
                    // print_r($user);
                    // $this->setUserSession($user);
                    $data = [
                        "id" => $user['user_id'],
                        'name' => $user['name'],
                        'email' => $user['email']
                    ];

                    // print_r($data);
                    // die();

                    session()->set('data', $data);

                    return redirect()->to('/profile');
                } else {
                    $data['errors'] = ["Email or password is incorrect"];
                }
            }
        }

        return view('templates/header')
            . view('login', $data)
            . view('templates/footer');
    }

    // Define function to set session with user data
    private function setUserSession($user)
    {

        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];

        // $session = session();

        session()->set($data);
        return true;
    }


    function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    function forgotPwd()
    {
        $formModel = new FormModel();
        $resetModel = new ResetPasswordModel();
        $data = [];
        if ($this->request->getPost()) {
            $rules =  [
                'email' => 'required|valid_email',
            ];

            if (!$this->validate($rules)) {
                $data['errors'] = $this->validator->getError('email');
            } else {
                $email = $this->request->getVar('email');
                $userData = $formModel->findUser($email);
                if ($userData) {

                    // Email exists

                    // 1. Get reset password table model instance
                    // 2. Get user id from registration table
                    $userID = $userData['id'];
                    $user_uuid = $userData['user_id'];
                    // 3. Create timestamp to be attahed to url
                    $expiryTime = date('Y-m-d H:i:s', strtotime('+1 minute'));
                    $token = bin2hex(random_bytes(32));
                    $is_used = false;
                    // 4. save user id, timestamp
                    $insertData = $resetModel->inserData($user_uuid, $token, $is_used, $expiryTime);
                    // 5. Compose url and send as email to user 
                    echo $insertData;
                    if ($insertData) {
                        // Note: In this case, I am redirecting the user to the url

                        // $baseURL = config('App')->baseURL;
                        $params = [
                            'id' => $insertData,
                            'token' => $token,
                            'user_key' => $user_uuid
                        ];
                        $url = site_url('reset') . '?' . http_build_query($params);
                        return redirect()->to($url);
                    }
                } else {
                    // echo "Does not exist";
                    // Email does not exist, throw error
                    $data['errors'] = "This email does not exist";
                }

                // echo $email;
            }
        }
        return view('templates/header')
            . view('reset', $data)
            . view('templates/footer');
    }

    function reset()
    {
        $formModel = new FormModel();
        $resetModel = new ResetPasswordModel();
        // 1. Get the url query parameters (id, toke, user_key)
        $id = $this->request->getVar('id');
        $token = $this->request->getVar('token');
        $user_key = $this->request->getVar('user_key');
        $data = [];

        if ($this->request->getPost()) {
            $rules = [
                'password' => 'required',
                'confirm_password' => "required|matches[password]"
            ];

            if (!$this->validate($rules)) {
                $data['errors'] = $this->validator->getErrors();
            } else {
                $password = $this->request->getVar('password');

                // 1b Get table details for the request
                $returned_user = $resetModel->findUser($id);

                if ($returned_user) {
                    $returned_user_token = $returned_user['token'];
                    $returned_user_id = $returned_user['user_id'];
                    $returned_token_status = $returned_user['is_used'];
                    $data_expiry_time = $returned_user['expiry'];

                    $calculated_time = strtotime($data_expiry_time) < time();

                    // 2. Perform sanitary checks using the Reset Password table data to ensure there is a match for all params
                    // 3. Confirm if expiry time and current time are tallied and take appropriate action
                    if ($token == $returned_user_token && $user_key == $returned_user_id && $returned_token_status == false && !$calculated_time) {
                        // echo "Toke is very valid!";
                       if( $formModel->updatePassword($returned_user_id, $password)) {
                            $resetModel->update_is_used($id);

                            return redirect()->to('login');
                       }
                    } else {
                        // Token , user key and time do not match. Throw an error
                        // echo "Toke is very invalid!";
                        return redirect()->to('reset/error');
                    }


                    // 3a. If all goes well, get the user table and update password for this user, then redirect to login page
                    // 3b. If not, show errror message appropriately and advice to go back to login page
                } else {
                    // id/user does not exist so return error
                }
            }
        }
        return view('templates/header')
            . view('password_form', $data)
            . view('templates/footer');
    }

    function resetError() {
        return view('templates/header')
            . view('error')
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
