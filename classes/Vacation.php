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
        $data = [
            'userId' => $_SESSION['user']['id'],
            'startDate' => date('Y-m-d', strtotime($startDate)),
            'endDate' => date('Y-m-d', strtotime($endDate)),
            'status' => 'w',
            'isActive' => 1
        ];

        if ($this->validateRequestedVacation($startDate, $endDate)) {
            if ($this->db->insert('vacations', $data)) {
                $_SESSION['flashSuccess'] = 'Requested vacation sent to review';
                header("Location: overview.php");
                exit;
            }
        } else {
            $_SESSION['flashError'] = 'Requested dates overlap with existing or date is invalid.';
            header("Location: overview.php");
            exit;
        }
    }

    private function validateRequestedVacation($startDate, $endDate)
    {
        $this->db->where('userId', $_SESSION['user']['id']);
        $this->db->where('status', 'a');
        $this->db->orWhere('status', 'w');
        $res = $this->db->get('vacations');

        $vacationDuration = $this->calculateVacationDays($startDate, $endDate);
        $daysLeft = $this->db->where('id', $_SESSION['user']['id'])->get('users', null, 'daysLeft');
        
        if ($vacationDuration > $daysLeft[0]['daysLeft']) {
            $_SESSION['flashError'] = 'You have requested more days than you have left.';
            header("Location: request_vacation.php");
            exit;
        }

        if ($res) {
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
        } else {
            return true;
        }
    }

    public function getVacationsOnReview ()
    {
        $this->db->where('userId', $_SESSION['user']['id']);
        $this->db->where('status', 'w');
        $this->db->where('isActive', 1);
        $res = $this->db->get('vacations');

        if ($res)
            return $res;
    }

    public function getApprovedVacations ()
    {
        $this->db->where('userId', $_SESSION['user']['id']);
        $this->db->where('status', 'a');
        // $this->db->where('isActive', 1);
        $res = $this->db->get('vacations');

        if ($res)
            return $res;
    }

    public function getRejectedVacations ()
    {
        $this->db->where('userId', $_SESSION['user']['id']);
        $this->db->where('status', 'r');
        // $this->db->where('isActive', 1);
        $res = $this->db->get('vacations');

        if ($res)
            return $res;
    }

    public function getAllRequests () {
        $cols = ['v.id', 'u.firstName', 'u.lastName', 'v.startDate', 'v.endDate'];
        $this->db->join('users u', 'v.userId=u.id','INNER');
        $this->db->where('status', 'w');
        $res = $this->db->get('vacations v', null, $cols);

        if ($res)
            return $res;
    }

    public function rejectVacation ($id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update('vacations', [
            'status' => 'r',
        ]);

        if ($res)
            return $res;
    }

    public function approveVacation ($id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update('vacations', [
            'status' => 'a',
            'isActive' => 0
        ]);

        if ($res) {
            $this->db->where('id', $id);
            $data = $this->db->get('vacations');

            $this->db->where('id', $data[0]['userId']);
            $daysLeft = $this->db->get('users', null, ['daysLeft']);

            $vacationDays = $this->calculateVacationDays($data[0]['startDate'], $data[0]['endDate']);
            $newDaysLeft = $daysLeft[0]['daysLeft'] - $vacationDays;
            
            $this->db->where('id', $data[0]['userId']);
            $diff = $this->db->update('users', [
                'daysLeft' => $newDaysLeft
            ]);
            
            if ($diff) {
                return true;
            }
        }
    }

    private function calculateVacationDays ($startDate, $endDate)
    {
        $start = date_create($startDate);
        $end = date_create($endDate);
        
        $diff = date_diff($start, $end);
        
        return $diff->days + 1;
    }

    public function getRequestsHistory()
    {
        $res = $this->db->join('users u ', 'u.id=v.userId', 'INNER')->where('status', 'a')->orWhere('status', 'r')->get('vacations v');

        if ($res)
            return $res;
    }
}