<?php

class FilemanagerController extends Controller {

    public function viewData($f3) {
    	$tmp    = new FilemanagerModel($this->db);
        $params['limit'] = ' LIMIT 0,12';
        $data   = $tmp->getDataAll($params);
        $f3->set('data', $data);
        echo View::instance()->render('/filemanager/view.html');
    }

    public function viewMore($f3) {
    	$tmp    = new FilemanagerModel($this->db);
    	$params['limit'] = ' LIMIT '.$_POST['limit'].' OFFSET '.$_POST['offset'];
    	$data   = $tmp->getDataAll($params);
    	$f3->set('data', $data);
    	echo View::instance()->render('/filemanager/viewMore.html');
    }

    public function formAdd($f3) {
        echo View::instance()->render('/filemanager/add.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new FilemanagerModel($this->db);
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
        $data = $tmp->saveData($id,$params);
        $new = array('new'=>'<div class="col-md-3">
            <div class="img-thumbnail" title="'.$params['images_title'].'">
                <img src="'.$this->f3->hive()['BASE'].'/images/thumb/gallery/'.$params['images_picture'].'" alt="'.$params['images_title'].'" onclick="setField(\'gallery/'.$params['images_picture'].'\')" data-dismiss="modal" aria-label="Close">
                <div class="caption">
                    <div class="input-group">
                        <input class="form-control" value="'.$this->f3->hive()['BASE'].'/images/gallery/'.$params['images_picture'].'">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-link"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>');
        $data = array_merge($data,$new);
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