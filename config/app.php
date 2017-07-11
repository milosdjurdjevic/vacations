<?
    return $config = [

        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'db_name' => 'vacations',
        ]

    ];

    

    // var_dump($config); die;

    $db = new MysqliDb($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name']);

    $users = $db->get('users');

    var_dump($users);