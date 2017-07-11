<?
require_once('database/MysqliDb.php');

class User
{
    private $db;
    public $config;

    public function __construct ()
    {
        $this->config = parse_ini_file('config/app.ini');
        $this->db = new MysqliDb($this->config['host'], $this->config['user'], $this->config['password'], $this->config['db_name']);
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
            return $emailError = "Please enter your email address.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            return $emailError = "Please enter valid email address.";
        }

        // Check if pass is set
        if(empty($pass)){
            $error = true;
            return $passError = "Please enter your password.";
        }

        if (!$error) {
            $this->db->where('email', $email);
            $res = $this->db->get('users');

            if ($res) {
                $passCheck = password_verify($pass, $res[0]['password']);
                if ($passCheck) {
                    $_SESSION['user'] = $res[0];

                    $this->db->where('userId', $res[0]['id']);
                    $userRole = $this->db->get('user_roles');

                    if ($userRole[0]['roleId'] == 1) {
                        $_SESSION['user']['roleId'] = $userRole[0]['roleId'];

                        header("Location: index.php");
                    } else if ($userRole[0]['roleId'] == 2) {
                        $_SESSION['user']['roleId'] = $userRole[0]['roleId'];

                        header("Location: overview.php");
                    }
                } else {
                    return 'Incorrect password';
                }
            } else {
                $errMsg = "Incorrect Credentials, Try again...";
                return $errMsg;
            }
        }
    }

    public function logout ()
    {
//        $data = [
//            'firstName' => 'Marko',
//            'lastName' => 'Djurdjevic',
//            'email' => 'djudja@gmail.com',
//            'password' => password_hash('marko123', PASSWORD_DEFAULT)
//        ];
//        $this->db->insert('users', $data);

        unset($_SESSION['user']);
        session_unset();
        session_destroy();
        header("Location: login.php");
    }

    public function getEmployees ()
    {
        $this->db->join('user_roles ur', 'u.id=ur.userId','INNER');
        $this->db->where('ur.roleId', 2);
        $res = $this->db->get('users u');

        if ($res)
            return $res;
    }
}