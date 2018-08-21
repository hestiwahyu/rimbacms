<?php

class GalleryController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/agallery/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('gallery',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Gallery' => $f3->get('BASE').'/agallery'
            ));
            echo View::instance()->render('/gallery/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new GalleryModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function getDataJsonImage() {
        header("Content-type:application/json");
        $tmp    = new GalleryModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJsonImage($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new GalleryModel($this->db);
        $params['gallery_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        $breadcrumb = array(
            '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
            'Gallery' => $f3->get('BASE').'/agallery'
        );
        if(@$f3->get('PARAMS.id')){
            $tmp    = new GalleryModel($this->db);
            $params = array('a.gallery_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
            $f3->set('SESSION.gallery',$f3->get('PARAMS.id'));
            $subtitle = (@$data['title'])?' ' .ucwords($data['title']):'';
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));
        }
        $f3->set('breadcrumb', $breadcrumb);
        echo View::instance()->render('/gallery/view.html');
    }

    public function viewDataImage($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new GalleryModel($this->db);
            $params = array('a.images_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getByIdImage($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/gallery/viewImage.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new GalleryModel($this->db);
            $params = array('a.gallery_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/gallery/add.html');
    }

    public function formAddImage($f3) {
        $tmp = new GalleryModel($this->db);
        if(@$f3->get('PARAMS.id')){
            $params = array('a.images_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getByIdImage($params);
            $f3->set('data', $data);
        }
        $gallery_id = $f3->get('SESSION.gallery');
        $f3->set('gallery_id', $gallery_id);
        echo View::instance()->render('/gallery/addImage.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new GalleryModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function saveDataImage($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new GalleryModel($this->db);
        $params = $_POST;
        if(@$_FILES['images_picture']['name'] && $_FILES['images_picture']['name'] != ''){
            $filename = $this->uploadImage($f3,$_FILES);
            if($filename != null){
                $params['images_picture'] = $filename;
                if(@$params['images_picture_old'] && $params['images_picture_old']!=''){
                    $file1 = 'images/gallery/'.$params['images_picture_old'];
                    $file2 = 'images/thumb/gallery/'.$params['images_picture_old'];
                    unlink($file1);
                    unlink($file2);
                }
            }else{
                unset($params['images_picture']);
            }
            unset($params['images_picture_old']);
        }else{
            if(@$params['images_picture']){
                unset($params['images_picture']);
                unset($params['images_picture_old']);
            }
        }
        $data   = $tmp->saveDataImage($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new GalleryModel($this->db);
            $params = array('gallery_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delDataImage($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new GalleryModel($this->db);
            $params = array('images_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delDataImage($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }


    public function uploadImage($f3,$file) {
        $f3->set('UPLOADS','images/gallery/');
        $overwrite = true;
        $slug = false;
        $web = \Web::instance();
        $files = $web->receive(function($file,$formFieldName){
            preg_match('/\w+$/',$file['name'],$ext);
            $ext_img = explode(',',strtoupper(Rimba::instance()->setting('img_ext')));
            if(!in_array(strtoupper($ext[0]),$ext_img)){
                return false;
            }
            if($file['size'] > (Rimba::instance()->setting('img_size') * 1024)){
                return false;
            }
            },
            $overwrite,
            $slug
        );
        preg_match('/\w+$/',$files['name'],$ext);
        $img = new \Image($files['name'],true,'images/gallery/');
        $img->resize(238,206,TRUE,TRUE);
        $_ext = strtolower($ext[0]);
        $_ext = str_replace('jpg','jpeg',$_ext);
        file_put_contents('images/thumb/gallery/'.$files['name'],$img->dump($_ext));
        return (@$files['name'])?@$files['name']:null;
    }

}