<?php

class CategoryController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/acategory/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('category',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Category' => $f3->get('BASE').'/acategory'
            ));
            echo View::instance()->render('/category/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new CategoryModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new CategoryModel($this->db);
        $params['category_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new CategoryModel($this->db);
            $params = array('a.category_id'=>$f3->get('PARAMS.id'));
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
            $f3->set('data', $data);
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        echo View::instance()->render('/category/view.html');
    }

    public function formAdd($f3) {
        $tmp    = new CategoryModel($this->db);
        if(@$f3->get('PARAMS.id')){
            $params = array('a.category_id'=>$f3->get('PARAMS.id'));
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
            $f3->set('data', $data);
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $cat      = $tmp->getDataAll();
        $f3->set('cat', $cat);
        $f3->set('lang', $lang);
        echo View::instance()->render('/category/add.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new CategoryModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new CategoryModel($this->db);
            $params = array('category_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

}