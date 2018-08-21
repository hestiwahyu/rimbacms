<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class ConfigController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/aconfig/viewData');
        echo View::instance()->render('layout.html');
    }

    public function viewData($f3) {
        $f3->set('breadcrumb', array(
            '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
            'Config and Route' => $f3->get('BASE').'/aconfig'
        ));
        
        $filenameConfig = 'app/config/config.ini';
        $fConfig = fopen($filenameConfig,'r') or die('Unable to open file config!');
        $fConfigContent = fread($fConfig,filesize($filenameConfig));
        fclose($fConfig);

        $filenameRoute = 'app/config/routes.ini';
        $fRoute = fopen($filenameRoute,'r') or die('Unable to open file routes!');
        $fRouteContent = fread($fRoute,filesize($filenameRoute));
        fclose($fRoute);

        $f3->set('config', $fConfigContent);
        $f3->set('route', $fRouteContent);
        echo View::instance()->render('/config/view.html');
    }

    public function saveDataConfig($f3) {
        $data = array();
        $params = $_POST;
        
        if(@$params['config'] && $params['config']!=null){
            $filenameConfig = 'app/config/config.ini';
            $fConfig = fopen($filenameConfig,'w') or die('Unable to open file config!');
            fwrite($fConfig,$params['config']);
            fclose($fConfig);
            $data = array(
                '_pesan'    => 'Data berhasil disimpan.',
                '_redirect' => true,
                '_page'     => $f3->hive()['BASE'] . '/aconfig'
            );
        }else{
            $data = array(
                '_pesan'    => 'Gagal menyimpan data!',
                '_redirect' => false
            );
        }

        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function saveDataRoute($f3) {
        $data = array();
        $params = $_POST;
        
        if(@$params['route'] && $params['route']!=null){
            $filenameRoute = 'app/config/routes.ini';
            $fRoute = fopen($filenameRoute,'w') or die('Unable to open file routes!');
            fwrite($fRoute,$params['route']);
            fclose($fRoute);
            $data = array(
                '_pesan'    => 'Data berhasil disimpan.',
                '_redirect' => true,
                '_page'     => $f3->hive()['BASE'] . '/aconfig'
            );
        }else{
            $data = array(
                '_pesan'    => 'Gagal menyimpan data!',
                '_redirect' => false
            );
        }

        header("Content-type:application/json");
        echo json_encode($data);
    }

}