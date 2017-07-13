<?
require_once('database/MysqliDb.php');

class Vacation
{
    private $db;
    public $config;

    public function __construct()
    {
        $this->config = parse_ini_file('config/app.ini');
        $this->db = new MysqliDb($this->config['host'], $this->config['user'], $this->config['password'], $this->config['db_name']);
    }

    /**
     * Save a requested vacation
     */
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
            header("Location: request_vacation.php");
            exit;
        }
    }

    /**
     * Check if requested date is valid or do not overlap with existing or if employee has enough days for requested vacation
     */
    private function validateRequestedVacation($startDate, $endDate)
    {
        $res = $this->db->where('userId', $_SESSION['user']['id'])
            ->where('status', 'a')
            ->orWhere('status', 'w')
            ->get('vacations');

//        $onReview = $this->db->where('userId', $_SESSION['user']['id'])
//            ->where('status', 'w')
//            ->get('vacations');
//
//        $res = array_merge($approved, $onReview);

        $vacationDuration = $this->calculateVacationDays($startDate, $endDate);
        $daysLeft = $this->db->where('id', $_SESSION['user']['id'])->get('users', null, 'daysLeft');

        if ($vacationDuration > $daysLeft[0]['daysLeft']) {
            $_SESSION['flashError'] = 'You have requested more days than you have left.';
            header("Location: request_vacation.php");
            exit;
        }

        if ($res) {
            foreach ($res as $vacation) {
                // Check if days overlap
                if (strtotime($vacation['startDate']) <= strtotime($endDate) && strtotime($vacation['startDate']) >= strtotime($startDate)
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

    /**
     * Get vacations for user that is on review
     */
    public function getVacationsOnReview()
    {
        $res = $this->db->where('userId', $_SESSION['user']['id'])
            ->where('status', 'w')
            ->where('isActive', 1)
            ->get('vacations');

        if ($res)
            return $res;
    }

    /**
     * Get vacations for user that are approved
     */
    public function getApprovedVacations()
    {
        $res = $this->db->where('userId', $_SESSION['user']['id'])
            ->where('status', 'a')
            ->get('vacations');

        if ($res)
            return $res;
    }

    /**
     * Get vacations for user that are rejected
     */
    public function getRejectedVacations()
    {
        $res = $this->db->where('userId', $_SESSION['user']['id'])
            ->where('status', 'r')
            ->get('vacations');

        if ($res)
            return $res;
    }

    /**
     * Get all requested vacations for all users
     */
    public function getAllRequests()
    {
        $cols = ['v.id', 'u.firstName', 'u.lastName', 'v.startDate', 'v.endDate'];
        $res = $this->db->join('users u', 'v.userId=u.id', 'INNER')
            ->where('status', 'w')
            ->where('isActive', 1)
            ->get('vacations v', null, $cols);

        if ($res)
            return $res;
    }

    /**
     * Reject a vacation for user
     */
    public function rejectVacation($id)
    {
        $res = $this->db->where('id', $id)
            ->update('vacations', [
                'status' => 'r',
            ]);

        if ($res)
            return $res;
    }

    /**
     * Approve a vacation for user
     */
    public function approveVacation($id)
    {
        $res = $this->db->where('id', $id)
            ->update('vacations', [
                'status' => 'a',
                'isActive' => 0
            ]);

        if ($res) {
            $data = $this->db->where('id', $id)
                ->get('vacations');

            $daysLeft = $this->db->where('id', $data[0]['userId'])
                ->get('users', null, ['daysLeft']);

            $vacationDays = $this->calculateVacationDays($data[0]['startDate'], $data[0]['endDate']);
            $newDaysLeft = $daysLeft[0]['daysLeft'] - $vacationDays;

            $diff = $this->db->where('id', $data[0]['userId'])
                ->update('users', [
                    'daysLeft' => $newDaysLeft
                ]);

            if ($diff) {
                return true;
            }
        }
    }

    /**
     * Get number of days for vacation
     */
    private function calculateVacationDays($startDate, $endDate)
    {
        $start = date_create($startDate);
        $end = date_create($endDate);

        $diff = date_diff($start, $end);

        return $diff->days + 1;
    }

    /**
     * History of vacation requests
     */
    public function getRequestsHistory()
    {
        $res = $this->db->join('users u ', 'u.id=v.userId', 'INNER')->where('status', 'a')->orWhere('status', 'r')->get('vacations v');

        if ($res)
            return $res;
    }

    public function cancelRequest($id)
    {
        $res = $this->db->where('id', $id)
            ->update('vacations', [
                'isActive' => 0
            ]);

        if ($res)
            return $res;
    }
}