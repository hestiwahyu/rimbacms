<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class MenuController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/amenu/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('menu',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Menu' => $f3->get('BASE').'/amenu'
            ));
            echo View::instance()->render('/menu/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new MenuModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function getDataJsonMenu() {
        header("Content-type:application/json");
        $tmp    = new MenuModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJsonMenu($params);
        echo json_encode($data);
    }

    public function setActiveMenu($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new MenuModel($this->db);
        $params['menu_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveDataMenu($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        $breadcrumb = array(
            '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
            'Menu' => $f3->get('BASE').'/amenu'
        );
        if(@$f3->get('PARAMS.id')){
            $tmp    = new MenuModel($this->db);
            $params = array('a.menu_group_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
            $f3->set('SESSION.menu',$f3->get('PARAMS.id'));
            $subtitle = (@$data['title'])?' ' .ucwords($data['title']):'';
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));
        }
        $f3->set('breadcrumb', $breadcrumb);
        echo View::instance()->render('/menu/view.html');
    }

    public function viewDataMenu($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new MenuModel($this->db);
            $params = array('a.menu_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getByIdMenu($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/menu/viewMenu.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new MenuModel($this->db);
            $params = array('a.menu_group_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/menu/add.html');
    }

    public function formAddMenu($f3) {
        $tmp = new MenuModel($this->db);
        if(@$f3->get('PARAMS.id')){
            $params = array('a.menu_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getByIdMenu($params);
            $f3->set('data', $data);
        }
        $_menu = $tmp->getDataAllMenu();
        $parent = $this->selectMenu($_menu,0,0);
        $f3->set('parent', $parent);
        $group = $tmp->getDataAll();
        $f3->set('group', $group);
        echo View::instance()->render('/menu/addMenu.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new MenuModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function saveDataMenu($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new MenuModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveDataMenu($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new MenuModel($this->db);
            $params = array('menu_group_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delDataMenu($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new MenuModel($this->db);
            $params = array('a.menu_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delDataMenu($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    private function selectMenu($_menu,$parent,$is){
        $selectMenu = array();
        if(@$_menu[$parent]&&$_menu[$parent]!=null){
            $space = "";
            for($j=1;$j<=$is;$j++){
                $space .= "--";
            }
            $is++;
            $no = 1;
            foreach($_menu[$parent] as $r){
                $selectMenu[$parent.'-'.$r['id']] = array('id'=>$r['id'],'title'=>$space.$no.'. '.$r['title']);
                if(@$_menu[$r['id']]&&$_menu[$r['id']]!=null){
                    $newSelectMenu = $this->selectMenu($_menu,$r['id'],$is);
                    $selectMenu = array_merge($selectMenu,$newSelectMenu);
                }
                $no++;
            }
        }
        return $selectMenu;
    }

}