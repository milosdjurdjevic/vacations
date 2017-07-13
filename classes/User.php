<?
require_once('database/MysqliDb.php');

class User
{
    private $db;
    public $config;

    public function __construct()
    {
        $this->config = parse_ini_file('config/app.ini');
        $this->db = new MysqliDb($this->config['host'], $this->config['user'], $this->config['password'], $this->config['db_name']);
    }

    /**
     * Login user
     */
    public function login($e_mail, $password)
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
        if (empty($pass)) {
            $error = true;
            return $passError = "Please enter your password.";
        }

        if (!$error) {
            $res = $this->db->where('email', $email)
                ->get('users');

            if ($res) {
                $passCheck = password_verify($pass, $res[0]['password']);
                if ($passCheck) {
                    $_SESSION['user'] = $res[0];

                    $userRole = $this->db->where('userId', $res[0]['id'])
                        ->get('user_roles');

                    if ($userRole[0]['roleId'] == 1) {
                        $_SESSION['user']['roleId'] = $userRole[0]['roleId'];

                        header("Location: index.php");
                        exit;
                    } else if ($userRole[0]['roleId'] == 2) {
                        $_SESSION['user']['roleId'] = $userRole[0]['roleId'];

                        header("Location: overview.php");
                        exit;
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

    /**
     * Logout user
     */
    public function logout()
    {
        unset($_SESSION['user']);
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }

    /**
     * Get all employees
     */
    public function getEmployees()
    {
        $res = $this->db->join('user_roles ur', 'u.id=ur.userId', 'INNER')
            ->where('ur.roleId', 2)
            ->get('users u', null, [
                'u.id',
                'u.firstName',
                'u.lastName',
                'u.email',
                'u.daysLeft',
            ]);

        if ($res)
            return $res;
    }

    /**
     * Get employee
     */
    public function getEmployee($id)
    {

        $this->db->insert('users', [
            'firstName' => 'Admin',
            'lastName' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT)
        ]);

        $res = $this->db->where('id', $id)
            ->get('users u');

        if ($res)
            return $res;
    }

    /**
     * Edit employee
     */
    public function editEmployee($data)
    {
        $employeeData = [
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
        ];

        $this->db->where('id', $data['employeeId']);

        if ($this->db->update('users', $employeeData)) {
            $_SESSION['flashSuccess'] = "Employee updated";
            header("Location: employees.php");
            exit;
        } else {
            return false;
        }
    }

    /**
     * Change employee password
     */
    public function changeEmployeePassword($data)
    {
        $newPass = $_POST['newPassword'];
        $passConfirm = $_POST['passConfirm'];

        if (empty($newPass) || empty($passConfirm)) {
            return 'Must fill both fileds!!!';
        }

        if ($newPass == $passConfirm) {
            $this->db->where('id', $data['employeeId'])
                ->update('users', [
                    'password' => password_hash($newPass, PASSWORD_DEFAULT)
                ]);

            $_SESSION['flashSuccess'] = "Password changed";
            header("Location: employees.php");
            exit;
        }

    }

    /**
     * Delete employee
     */
    public function deleteEmployee($id)
    {
        if ($this->db->where('id', $id)->delete('users')) {
            $this->db->where('userId', $id)
                ->delete('user_roles');

            $_SESSION['flashSuccess'] = "Successfully deleted";
            header("Location: employees.php");
            exit;
        } else {
            return false;
        }
    }

    /**
     * Create employee
     */
    public function createEmployee($data)
    {
        $employeeData = [
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        if ($id = $this->db->insert('users', $employeeData)) {
            $this->db->insert('user_roles', [
                'userId' => $id,
                'roleId' => 2
            ]);

            $_SESSION['flashSuccess'] = 'Employee created successfully';
            header("Location: employees.php");
            exit;
        } else {
            $_SESSION['flashError'] = 'Employee not created successfully';
            header("Location: employees.php");
            exit;
        }
    }
}