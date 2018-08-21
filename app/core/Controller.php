<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class Controller {

    protected $f3;
    protected $db;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
        $db = new DB\SQL(
            $f3->get('DEVDB'),
            $f3->get('DEVDBUSERNAME'),
            $f3->get('DEVDBPASSWORD'),
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
        );
        $this->db = $db;
        $f3->set('DB',$db);
        $user = $f3->get('SESSION.user');
        if($user==null){
            $f3->reroute('/home');
        }
    }

    // function is called before every single routing!
    function beforeroute() {
    
    }

    // function is called after every single routing!
    function afterroute() {

    }
}