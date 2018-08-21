<?php

class TestimoniPublicController{

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

        $tmp    = new TestimoniModel($this->db);

        $f3->set('UI','themes/');
        $f3->set('THEME',$rimbaModel->getTheme());

        $home = $this->seloLib->setLang('home');
        $tes = $this->seloLib->setLang('testimoni');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            $tes => ''
        ));
        $page = (@$_GET['page'] && $_GET['page']!=null && $_GET['page']!=1)?$_GET['page']:1;
        $f3->set('page',$page);
        $limit = $this->rimbaLib->setting('item_per_page');
        $this->f3->set('limit',$limit);
        $offset = $this->f3->get('page');
        $offset = ($offset<=0)?1:$offset;
        $offset = ($offset-1) * $limit;
        $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        $params['field'] = array('a.testimoni_active'=>'Y');
        $params_paging = array(
            'page'    => $_GET['page'],
            'url'     => $f3->get('BASE').'/testimoni',
            'jml_all' => $tmp->countAllData()
        );
        $f3->set('paging',$params_paging);
        $url = $f3->get('BASE').'/testimoni';
        $f3->set('url',$url);

        $f3->set('title',$tes);
        $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));

        $data = $tmp->getTestimoni($params);
        $f3->set('data',$data);

        $f3->set('content','/../../app/component/testimoni/viewPublic.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }
    
}