<?
require_once('database/MysqliDb.php');

class Vacation
{
    private $db;
    public $config;

    public function __construct ()
    {
        $this->config = parse_ini_file('config/app.ini');
        $this->db = new MysqliDb($this->config['host'], $this->config['user'], $this->config['password'], $this->config['db_name']);
    }

    public function vacation($startDate, $endDate)
    {
        session_start();
        $data = [
            'userId' => $_SESSION['user']['id'],
            'startDate' => date('Y-m-d', strtotime($startDate)),
            'endDate' => date('Y-m-d', strtotime($endDate)),
            'status' => 'w',
            'isActive' => 1
        ];

        if ($this->validateRequestedVacation($startDate, $endDate)) {
            $this->db->insert('vacations', $data);
        } else {
            $_SESSION['flashMessage'] = "Requested dates overlap with existing";
            header("Location: request_vacation.php");
        }
    }

    private function validateRequestedVacation($startDate, $endDate)
    {
        $this->db->where('userId', $_SESSION['user']['id']);
        $this->db->where('isActive', 1);
        $res = $this->db->get('vacations');

        foreach ($res as $vacation) {
            if( strtotime($vacation['startDate']) <= strtotime($endDate) && strtotime($vacation['startDate']) >= strtotime($startDate)
                || strtotime($vacation['endDate']) <= strtotime($endDate) && strtotime($vacation['endDate']) >= strtotime($startDate)
                || in_array(strtotime($startDate), range(strtotime($vacation['startDate']), strtotime($vacation['endDate']))) // If requested start date is in range of existing date
                || in_array(strtotime($endDate), range(strtotime($vacation['startDate']), strtotime($vacation['endDate']))) // If requested end date is in range of existing date
            ) { //If the dates overlap
                return false;
            }

            return true; //Return true if there is no overlap
        }
    }
}