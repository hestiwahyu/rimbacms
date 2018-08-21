<?php

class PagesController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/apages/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('pages',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Pages' => $f3->get('BASE').'/apages'
            ));
            echo View::instance()->render('/pages/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new PagesModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new PagesModel($this->db);
        $params['pages_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PagesModel($this->db);
            $params = array('a.pages_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            if(@$data['title']){
                $_title = explode('|',$data['title']);
                $data['title'] = array();
                if($_title!=null){
                    foreach($_title as $v){
                        $_title2 = explode('::',$v);
                        $data['title'][$_title2[0]] = $_title2[1];
                    }
                }
            }
            if(@$data['content']){
                $_content = explode('|',$data['content']);
                $data['content'] = array();
                if($_content!=null){
                    foreach($_content as $v){
                        $_content2 = explode('::',$v);
                        $data['content'][$_content2[0]] = $_content2[1];
                    }
                }
            }
            $f3->set('data', $data);
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        echo View::instance()->render('/pages/view.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PagesModel($this->db);
            $params = array('a.pages_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            if(@$data['title']){
                $_title = explode('|',$data['title']);
                $data['title'] = array();
                if($_title!=null){
                    foreach($_title as $v){
                        $_title2 = explode('::',$v);
                        $data['title'][$_title2[0]] = $_title2[1];
                    }
                }
            }
            if(@$data['content']){
                $_content = explode('|',$data['content']);
                $data['content'] = array();
                if($_content!=null){
                    foreach($_content as $v){
                        $_content2 = explode('::',$v);
                        $data['content'][$_content2[0]] = $_content2[1];
                    }
                }
            }
            $f3->set('data', $data);
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Pages' => $f3->get('BASE').'/apages',
                'Update Pages' => ''
            ));
            $f3->set('subtitle', 'Update Pages');
        }else{
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Pages' => $f3->get('BASE').'/apages',
                'New Pages' => ''
            ));
            $f3->set('subtitle', 'Add New Pages');
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        echo View::instance()->render('/pages/add.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new PagesModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PagesModel($this->db);
            $params = array('pages_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

}