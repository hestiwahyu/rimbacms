<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class commentController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/acomment/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('comment',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Comment' => $f3->get('BASE').'/acomment'
            ));
            echo View::instance()->render('/comment/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new CommentModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new CommentModel($this->db);
        $params['comment_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function formReply($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new CommentModel($this->db);
            $params = array('a.comment_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/comment/reply.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new CommentModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new CommentModel($this->db);
            $params = array('comment_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

}