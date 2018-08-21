<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class ContactPublicController {

    protected $f3;
    protected $db;
    protected $rimbaModel;
    protected $rimbaLib;
    protected $seloLib;
    protected $visitorLib;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
        $db = new DB\SQL(
            $f3->get('DEVDB'),
            $f3->get('DEVDBUSERNAME'),
            $f3->get('DEVDBPASSWORD'),
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
        );
        $this->db         = $db;
        $rimbaModel       = new RimbaModel($this->db);
        $this->rimbaModel = $rimbaModel;
        $rimbaLib         = Rimba::instance();
        $this->rimbaLib   = $rimbaLib;
        $seloLib          = Selo::instance();
        $this->seloLib    = $seloLib;
        $visitorLib       = Visitor::instance();
        $this->visitorLib = $visitorLib;
        $f3->set('DB',$db);
        $f3->set('UI','themes/');
        $f3->set('THEME',$rimbaModel->getTheme());
        $lang_default = Rimba::instance()->setting('lang_default');
        if(!@$f3->get('SESSION.lang')){
            $langd = 'app/lang/'.$lang_default.'.php';
            include_once $langd;
            $f3->set('SESSION._lang',$_lang);
            $f3->set('SESSION.lang',$lang_default);
            $f3->set('LANG',$lang_default);
        }
        $f3->set('keywords',$this->rimbaLib->setting('web_keyword'));
        $f3->set('description',$this->rimbaLib->setting('web_description'));
        $f3->set('url','');
    }

    public function vpublic($f3) {
        $rimbaModel       = new RimbaModel($this->db);
        $this->rimbaModel = $rimbaModel;
        $rimbaLib         = Rimba::instance();
        $this->rimbaLib   = $rimbaLib;
        $seloLib          = Selo::instance();
        $this->seloLib    = $seloLib;

        $f3->set('UI','themes/');
        $f3->set('THEME',$rimbaModel->getTheme());

        $home = $this->seloLib->setLang('home');

        $params['field']  = array('a.pages_seotitle'=>'contacts','a.pages_active'=>'Y');
        $params['select'] = "b.pages_title AS title,b.pages_content AS content,a.pages_picture AS picture,a.pages_seotitle AS seotitle";
        $_data            = $this->rimbaModel->getPagesBy($params);
        $f3->set('data',$_data);

        $kon = (@$_data['title'])?$_data['title']:$this->seloLib->setLang('contact');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            $kon => ''
        ));

        $f3->set('title',$kon);
        $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));

        $f3->set('content','/../../app/component/contact/viewPublic.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    public function saveContact($f3) {
        $params = $_POST;
        $contactModel = new ContactModel($this->db);
        $data = $contactModel->saveData(null,$params);
        if(@$data['_redirect']==true){
            $f3->reroute('/home');
        }else{
            $f3->reroute('/contacts');
        }
    }
    
}