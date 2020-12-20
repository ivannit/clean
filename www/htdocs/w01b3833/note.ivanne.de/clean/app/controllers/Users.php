<?php

class Users extends Controller {

    private $password, $confirmPassword, $passwordError, $confirmPasswordError;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->password = '';
        $this->confirmPassword = '';
        $this->passwordError = '';
        $this->confirmPasswordError = '';
    }

    public function register() {

        $data = [
            'title' => 'Registrierung',
            'username' => '',
            'email' => '',
            'password' => '',
            'confirmPassword' => '',
            'usernameError' => '',
            'emailError' => '',
            'passwordError' => '',
            'confirmPasswordError' => '',
            'consentError' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // block harmful input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => 'Registrierung',
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirmPassword' => trim($_POST['confirmPassword']),
                'usernameError' => '',
                'emailError' => '',
                'passwordError' => '',
                'confirmPasswordError' => '',
                'consent' => $_POST['consent'],
                'consentError' => ''
            ];

            if (empty($data['username'])) {
                $data['usernameError'] = 'Benutzername fehlt.';
            } elseif (!preg_match("/^[a-z0-9]+$/i", $data['username'])) {
                $data['usernameError'] = 'Benutzername darf nur Buchstaben und Zahlen enthalten.';
            }
            if (empty($data['email'])) {
                $data['emailError'] = 'E-Mail-Adresse fehlt.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['emailError'] = 'E-Mail-Adresse ungültig.';
            } elseif ($this->userModel->userExists($data['email'])) {
                $data['emailError'] = 'E-Mail-Adresse existiert bereits.';
            }
            if ($data['consent'] != 'yes') {
                $data['consentError'] = 'Bitte Datenschutzerklärung zustimmen.';
            }

            $this->password = $data['password'];
            $this->confirmPassword = $data['confirmPassword'];
            $this->validatePassword();
            
            $data['passwordError'] = $this->passwordError;
            $data['confirmPasswordError'] = $this->confirmPasswordError;

            if (empty($data['usernameError']) && empty($data['emailError']) && empty($data['passwordError']) && empty($data['confirmPasswordError']) && empty($data['consentError'])) {

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // register user from model function
                if ($this->userModel->register($data)) {
                    // redirect to the login page
                    header('location:' . URLROOT . '/users/login');
                } else {
                    die('Unerwarteter Fehler');
                }
            }
        }
        $this->view('users/register', $data);
    }

    public function validatePassword() {
        if (empty($this->password)) {
            $this->passwordError = 'Passwort fehlt.';
        } elseif (strlen($this->password) < 7) {
            $this->passwordError = 'Passwort muss aus mindestens 7 Zeichen bestehen.';
        } elseif (!preg_match("/\d+/", $this->password)) {
            $this->passwordError = 'Passwort muss mindestens eine Ziffer enthalten.';
        }
        if (empty($this->confirmPassword)) {
            $this->confirmPasswordError = 'Passwortbestätigung fehlt.';
        } elseif ($this->password != $this->confirmPassword) {
            $this->confirmPasswordError = 'Passwörter stimmen nicht überein.';
        }
    }

    public function login() {

        $data = [
            'title' => 'Login',
            'email' => '',
            'password' => '',
            'emailError' => '',
            'passwordError' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => 'Login',
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'usernameError' => '',
                'passwordError' => '',
            ];

            if (empty($data['email'])) {
                $data['emailError'] = 'E-Mail-Adresse fehlt.';
            }
            if (empty($data['password'])) {
                $data['passwordError'] = 'Passwort fehlt.';
            }

            if (empty($data['emailError']) && empty($data['passwordError'])) {
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['passwordError'] = 'Passwort oder E-Mail inkorrekt.';
                    $this->view('users/login', $data);
                }
            }
        }
        $this->view('users/login', $data);
    }

    public function sendlink() {

        $data = [
            'linkSent' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $email = trim($_POST['linkemail']);
            if ($this->sendResetLinkMail($email)) {
                $data = [
                    'linkSent' => 'Reset-Link wurde gesendet an: <b>' . $email . '</b>'
                ];
            } else {
                die('Unerwarteter Fehler');
            }
        }
        $this->view('users/sendlink', $data);
    }

    public function createUserSession($user) {
        $_SESSION['userid'] = $user->userid;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        header('location:' . URLROOT . '/pages/index');
    }

    public function logout() {
        unset($_SESSION['userid']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        // javascript window.screen.width
        unset($_SESSION['screenwidth']);
        // note search filters
        unset($_SESSION['searcht']);
        unset($_SESSION['datemin']);
        unset($_SESSION['datemax']);
        unset($_SESSION['sortcol']);
        unset($_SESSION['ascdesc']);
        header('location:' . URLROOT . '/users/login');
    }

    public function sendResetLinkMail($email = '') {
        if (strlen($email) > 0) {
            $linkSent = false;
            require APPROOT . '/helpers/mail.php';
            $tempCode = $this->userModel->setTempCode($email);
            if (strlen($tempCode) > 0) {
                $msg = '<a href="' . URLROOT . '/users/reset/' . $tempCode . '/' . $email . '">'
                        . 'Passwort zur&uuml;cksetzen</a>';
                if (!smtpMail($email, 'note@ivanne.de', 'Note User', 'Reset', $msg)) {
                    smtpMail('mail@ivanne.de', 'note@ivanne.de', 'ivanne',
                            'send-reset-link-fail', 'error ' . $email);
                } else {
                    $linkSent = true;
                }
            }
            return $linkSent;
        } else {
            header('location:' . URLROOT . '/pages/index');
        }
    }

    public function reset($code = '', $email = '') {

        $data = [
            'code' => $code,
            'email' => $email,
            'newPasswordError' => '',
            'newConfirmPasswordError' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $code = trim($_POST['resetCode']);
            $email = trim($_POST['resetEmail']);
            $password = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['newconfirm']);

            $this->password = $password;
            $this->confirmPassword = $confirmPassword;
            $this->validatePassword();

            $data = [
                'code' => $code,
                'email' => $email,
                'newPasswordError' => $this->passwordError,
                'newConfirmPasswordError' => $this->confirmPasswordError
            ];

            if (empty($data['newPasswordError']) && empty($data['newConfirmPasswordError'])) {

                $hash = password_hash($password, PASSWORD_DEFAULT);
                if ($this->userModel->resetPassword($hash, $email, $code)) {
                    header('location:' . URLROOT . '/users/login');
                } else {
                    header('location:' . URLROOT . '/users/sendlink');
                }
            }
        }
        $this->view('users/reset', $data);
    }

}
