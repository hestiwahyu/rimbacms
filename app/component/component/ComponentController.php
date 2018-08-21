<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class ComponentController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/acomponent/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('component',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Component' => $f3->get('BASE').'/acomponent'
            ));
            echo View::instance()->render('/component/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new ComponentModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new ComponentModel($this->db);
            $params = array('a.component_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/component/view.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new ComponentModel($this->db);
            $params = array('a.component_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/component/add.html');
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new ComponentModel($this->db);
        $params['component_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function saveData($f3) {
        $params = $_POST;
        if(@$_FILES['file']['name'] && $_FILES['file']['name'] != ''){
            $filename = $this->uploadFile($f3,$_FILES);
            if($filename != null){
                $zip = new ZipArchive();
                $res = $zip->open('tmp/'.$filename);
                if($res === true){
                    $dir = 'tmp/component';
                    if(!is_dir($dir)){
                        if(false === @mkdir($dir, 0777, true)){
                            throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir));
                        }
                    }
                    $zip->extractTo($dir.'/');
                    $zip->close();
                    $file1 = 'tmp/'.$filename;
                    unlink($file1);
                    if(is_dir($dir)){
                        $dir2 = 'app/component/'.$params['component'];
                        if(is_dir($dir.'/component')){
                            if(!is_dir($dir2)){
                                if(false === @mkdir($dir2, 0777, true)){
                                    throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir2));
                                }
                            }
                            $this->copyFiles($dir.'/component',$dir2);
                        }
                        if(is_dir($dir.'/widget')){
                            $dir3 = 'app/widget/'.$params['component'];
                            if(!is_dir($dir3)){
                                if(false === @mkdir($dir3, 0777, true)){
                                    throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir3));
                                }
                            }
                            $this->copyFiles($dir.'/widget',$dir3);
                        }
                        $this->removeDir($dir);
                    }
                }
            }else{
                unset($params['file']);
            }
        }
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new ComponentModel($this->db);
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new ComponentModel($this->db);
            $params = array('component_id'=>$f3->get('PARAMS.id'));
            $_data   = $tmp->getById($params);
            if($_data != null){
                $dir = (@$_data['title']&&$_data['title']!=null)?'app/component/'.$_data['title']:null;
                if(is_dir($dir)){
                    $this->removeDir($dir);
                }
                $dir = (@$_data['title']&&$_data['title']!=null)?'app/widget/'.$_data['title']:null;
                if(is_dir($dir)){
                    $this->removeDir($dir);
                }
                $data = $tmp->delData($params);
            }
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function uploadFile($f3,$file) {
        $f3->set('UPLOADS','tmp/');
        $overwrite = true;
        $slug = false;
        $web = \Web::instance();
        $files = $web->receive(function($file,$formFieldName){
            preg_match('/\w+$/',$file['name'],$ext);
            $ext_file = explode(',',strtoupper(Rimba::instance()->setting('compress_ext')));
            if(!in_array(strtoupper($ext[0]),$ext_file)){
                return false;
            }
            if($file['size'] > (Rimba::instance()->setting('compress_size') * 1024)){
                return false;
            }
            },
            $overwrite,
            $slug
        );
        return (@$files['name'])?@$files['name']:null;
    }

    public function removeDir($src) {
        $dir = opendir($src);
        while(false !== ($file = readdir($dir))){
            if(($file!='.') && ($file != '..')){
                $full = $src .'/'. $file;
                if(is_dir($full)){
                    $this->removeDir($full);
                }else{
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    public function copyFiles($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ($file = readdir($dir))){
            if(($file != '.') && ($file != '..')){
                if(is_dir($src.'/'.$file)){
                    $this->copyFiles($src.'/'.$file,$dst.'/'.$file);
                }else{
                    copy($src.'/'.$file,$dst.'/'.$file);
                }
            }
        }
        closedir($dir);
    }

}