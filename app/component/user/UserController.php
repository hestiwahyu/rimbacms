<?php

class UserController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/auser/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('user',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'User' => $f3->get('BASE').'/auser'
            ));
            echo View::instance()->render('/user/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new UserModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new UserModel($this->db);
        $params['user_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new UserModel($this->db);
            $params = array('user_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/user/view.html');
    }

    public function viewGroup($f3) {
        if(@$f3->get('PARAMS.id')){
            $data   = Selo::instance()->userGroup($f3->get('PARAMS.id'));
            $f3->set('data', $data);
        }
        echo View::instance()->render('/user/viewGroup.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new UserModel($this->db);
            $params = array('user_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        $skins = array('blue','black','purple','green','red','yellow','blue-light','black-light','purple-light','green-light','red-light','yellow-light');
        $f3->set('skins', $skins);
        $f3->set('groups', Selo::instance()->userGroup());
        echo View::instance()->render('/user/add.html');
    }

    public function formChangePassword($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new UserModel($this->db);
            $params = array('user_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/user/changePass.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new UserModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function savePassword($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new UserModel($this->db);
        $params = $_POST;
        $data   = $tmp->savePassword($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new UserModel($this->db);
            $params = array('user_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

}