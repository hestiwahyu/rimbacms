<?php

class ThemeController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/atheme/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('theme',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Theme' => $f3->get('BASE').'/atheme'
            ));
            echo View::instance()->render('/theme/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new ThemeModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new ThemeModel($this->db);
        $params['theme_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new ThemeModel($this->db);
            $params = array('a.theme_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/theme/view.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new ThemeModel($this->db);
            $params = array('a.theme_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->getById($params);
            $f3->set('data', $data);
        }
        echo View::instance()->render('/theme/add.html');
    }

    public function saveData($f3) {
        $params = $_POST;
        if(@$_FILES['file']['name'] && $_FILES['file']['name'] != ''){
            $filename = $this->uploadFile($f3,$_FILES);
            if($filename != null){
                if(@$params['theme_folder_old'] && $params['theme_folder_old']!=''){
                    $file1 = 'themes/'.$params['theme_folder_old'];
                    $this->removeDir($file1);
                }
                $zip = new ZipArchive();
                $res = $zip->open('themes/'.$filename);
                if($res === true){
                    $dir = 'themes/'.$params['theme_folder'];
                    if(!is_dir($dir)){
                        if(false === @mkdir($dir, 0777, true)){
                            throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir));
                        }
                    }
                    $zip->extractTo($dir.'/');
                    $zip->close();
                    $file1 = 'themes/'.$filename;
                    unlink($file1);
                }
            }else{
                unset($params['file']);
            }
            unset($params['theme_folder_old']);
        }else{
            $dir = 'themes/'.$params['theme_folder_old'];
            if(is_dir($dir) && ($params['theme_folder_old'] != $params['theme_folder'])){
                $dir2 = 'themes/'.$params['theme_folder'];
                if(!is_dir($dir2)){
                    if(false === @mkdir($dir2, 0777, true)){
                        throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir2));
                    }
                }
                $this->copyFiles($dir,$dir2);
                $this->removeDir($dir);
            }
        }
        $id = (@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new ThemeModel($this->db);
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new ThemeModel($this->db);
            $params = array('theme_id'=>$f3->get('PARAMS.id'));
            $_data   = $tmp->getById($params);
            if($_data != null){
                $dir = (@$_data['folder']&&$_data['folder']!=null)?'themes/'.$_data['folder']:null;
                if(is_dir($dir)){
                    $this->removeDir($dir);
                    $data = $tmp->delData($params);
                }
            }
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function uploadFile($f3,$file) {
        $f3->set('UPLOADS','themes/');
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