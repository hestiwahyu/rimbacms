<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class TestimoniController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/atestimoni/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('testimoni',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Testimoni' => $f3->get('BASE').'/atestimoni'
            ));
            echo View::instance()->render('/testimoni/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new TestimoniModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new TestimoniModel($this->db);
            $params = array('a.testimoni_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/testimoni/view.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new TestimoniModel($this->db);
            $params = array('a.testimoni_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/testimoni/add.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new TestimoniModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new TestimoniModel($this->db);
            $params = array('testimoni_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function getAllJson($f3) {
        $tmp    = new TestimoniModel($this->db);
        $data   = $tmp->getDataAll();
        // header("Content-type:application/json");
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new TestimoniModel($this->db);
        $params['testimoni_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

}