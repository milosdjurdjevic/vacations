<?
require_once('classes/database/MysqliDb.php');

class User
{
    var $db;

    public function __construct ()
    {
        $this->db = new MysqliDb('localhost', 'root', '', 'vacations');
    }
    public function login ($e_mail, $password)
    {
        session_start();
        $error = false;
        $email = trim(strip_tags(htmlspecialchars($e_mail)));
        $pass = trim(strip_tags(htmlspecialchars($password)));

        // Check for errors on email
        if (empty($email)) {
            $error = true;
            $emailError = "Please enter your email address.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $emailError = "Please enter valid email address.";
        }

        // Check if pass is set
        if(empty($pass)){
            $error = true;
            $passError = "Please enter your password.";
        }

        if (!$error) {
            $this->db->where('email', $email);
            $res = $this->db->get('users');

            if ($res) {
                $passCheck = password_verify($pass, $res[0]['password']);
                if ($passCheck) {
                    $_SESSION['user'] = $res[0];

                    header("Location: index.php");
                }
            } else {
                $errMsg = "Incorrect Credentials, Try again...";
                return $errMsg;
            }
        }
    }

    public function logout ()
    {
        unset($_SESSION['user']);
        session_unset();
        session_destroy();
        header("Location: login.php");
    }
}