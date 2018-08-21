<?php

final class Archives extends Prefab {

	protected $f3;
    protected $db;
    protected $model;

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
        require('app/widget/archives/ArchivesModel.php');
        $model = new ArchivesModel($this->db);
        $this->model = $model;
    }

    public function index($data=null) {
    	$f3 = Base::instance();
    	$_data = array();
    	$_tmp = $this->model->getArchives();
    	if($_tmp!=null){
    		$i = 0;
    		foreach($_tmp as $r){
    			$_r = explode('-',$r['archives']);
    			$_data[$i]['year'] = $_r[0];
    			$_data[$i]['month'] = $_r[1];
    			$_data[$i]['label'] = date('F Y',strtotime($r['archives'].'-01'));
    			$i++;
    		}
    	}
        $title = (@$data['title'])?$data['title']:Selo::instance()->setLang('archives');
        $f3->set('title',$title);
    	$f3->set('data',$_data);
        echo View::instance()->render('../app/widget/archives/index.html');
    }

}

return Archives::instance();