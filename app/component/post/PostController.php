<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class PostController extends Controller {

    public function index($f3) {
        $f3->set('urlHome', $f3->hive()['BASE'] . '/apost/showAll');
        echo View::instance()->render('layout.html');
    }

    public function showAll($f3) {
        if(Selo::instance()->access('post',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Post' => $f3->get('BASE').'/apost'
            ));
            echo View::instance()->render('/post/list.html');
        }else{
            echo 'Forbidden access!';
        }
    }

    public function getDataJson() {
        header("Content-type:application/json");
        $tmp    = new PostModel($this->db);
        $params = $_POST;
        $data   = $tmp->getDataJson($params);
        echo json_encode($data);
    }

    public function setActive($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new PostModel($this->db);
        $params['post_active'] = (@$f3->get('PARAMS.sts'))?$f3->get('PARAMS.sts'):'N';
        $params['status'] = true;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function viewData($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PostModel($this->db);
            $params = array('a.post_id'=>$f3->get('PARAMS.id'));
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
            if(@$data['content']){
                $_content = explode('|',$data['content']);
                $data['content'] = array();
                if($_content!=null){
                    foreach($_content as $v){
                        $_content2 = explode('::',$v);
                        $data['content'][$_content2[0]] = $_content2[1];
                    }
                }
            }
            if(@$data['category']){
                $_category = explode('|',$data['category']);
                $data['category'] = array();
                if($_category!=null){
                    foreach($_category as $v){
                        $_category2 = explode('::',$v);
                        $data['category'][$_category2[0]] = $_category2[2];
                    }
                }
            }
            $f3->set('data', $data);
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        $this->f3->set('ESCAPE', false);
        echo View::instance()->render('/post/view.html');
    }

    public function formAdd($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PostModel($this->db);
            $params = array('a.post_id'=>$f3->get('PARAMS.id'));
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
            if(@$data['content']){
                $_content = explode('|',$data['content']);
                $data['content'] = array();
                if($_content!=null){
                    foreach($_content as $v){
                        $_content2 = explode('::',$v);
                        $data['content'][$_content2[0]] = $_content2[1];
                    }
                }
            }
            if(@$data['category']){
                $_category = explode('|',$data['category']);
                $data['category'] = array();
                if($_category!=null){
                    foreach($_category as $v){
                        $_category2 = explode('::',$v);
                        $data['category'][$_category2[0]] = $_category2[1];
                    }
                }
            }
            $f3->set('data', $data);
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Post' => $f3->get('BASE').'/apost',
                'Update Post' => ''
            ));
            $f3->set('subtitle', 'Update Post');
        }else{
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Post' => $f3->get('BASE').'/apost',
                'New Post' => ''
            ));
            $f3->set('subtitle', 'Add New Post');
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        $tmp_cat = new CategoryModel($this->db);
        $cat     = $tmp_cat->getDataAll();
        $f3->set('cat', $cat);
        $tmp_tag = new TagModel($this->db);
        $tag = $tmp_tag->getDataAll();
        $_tag = array();
        if($tag!=null){
            $i = 1;
            foreach($tag as $r){
                $_tag[$i] = $r['title'];
                $i++;
            }
        }
        $f3->set('tag', implode(',',$_tag));
        echo View::instance()->render('/post/add.html');
    }

    public function formEmail($f3) {
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PostModel($this->db);
            $params = array('a.post_id'=>$f3->get('PARAMS.id'));
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
            if(@$data['content']){
                $_content = explode('|',$data['content']);
                $data['content'] = array();
                if($_content!=null){
                    foreach($_content as $v){
                        $_content2 = explode('::',$v);
                        $data['content'][$_content2[0]] = $_content2[1];
                    }
                }
            }
            if(@$data['category']){
                $_category = explode('|',$data['category']);
                $data['category'] = array();
                if($_category!=null){
                    foreach($_category as $v){
                        $_category2 = explode('::',$v);
                        $data['category'][$_category2[0]] = $_category2[2];
                    }
                }
            }
            $f3->set('data', $data);
        }
        $tmp_lang = new LanguageModel($this->db);
        $lang     = $tmp_lang->getDataAll();
        $f3->set('lang', $lang);
        $this->f3->set('ESCAPE', false);

        $tmp_subscribe = new RimbaModel($this->db);
        $f3->set('subscribe', $tmp_subscribe->subscribe());
        echo View::instance()->render('/post/formEmail.html');
    }

    public function saveData($f3) {
        $id =(@$f3->get('PARAMS.id'))?$f3->get('PARAMS.id'):0;
        $tmp    = new PostModel($this->db);
        $params = $_POST;
        $data   = $tmp->saveData($id,$params);
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function sendEmail($f3) {
        $params      = $_POST;
        $rimbaLib    = Rimba::instance();
        $rimbaModel  = new RimbaModel($this->db);
        if(@$f3->get('PARAMS.id') && $f3->get('PARAMS.id')!=0){
            $params_post = array('a.post_id'=>$f3->get('PARAMS.id'));
            $d_post      = $rimbaModel->getPostBy($params_post);
            $title       = $d_post['title'];
            $html        = '<ol>
                                <li>
                                    <a href="'.$rimbaLib->setting('web_url').'/post/'.$d_post['seotitle'].'">'.$title.'</a>
                                </li>
                            </ol>';
        }else{
            $params_post = array('a.post_active'=>'Y');
            $d_post      = $rimbaModel->getListPostBy($params_post);
            $html        = '<ol>';
            if($d_post!=null){
                foreach($d_post as $r){
                    $html .= '<li><a href="'.$rimbaLib->setting('web_url').'/post/'.$r['seotitle'].'">'.$r['title'].'</a></li>';
                }
            }
            $html .= '</ol>';
        }
        $seloLib     = Selo::instance();
        if(@$params['email'] && $params['email']!=null){
            foreach($params['email'] as $e){
                $_e = explode('|',$e);
                $host   = $rimbaLib->setting('mail_hostname');
                $port   = $rimbaLib->setting('mail_port');
                $scheme = $rimbaLib->setting('mail_protocol');
                $user   = $rimbaLib->setting('mail_username');
                $pw     = $rimbaLib->setting('mail_password');
                $smtp = new SMTP ($host,$port,$scheme,$user,$pw);
                $smtp->set('Content-type', 'text/html; charset=UTF-8');
                $smtp->set('From', '"Info Rimba Media" <'.$rimbaLib->setting('email').'>');
                $smtp->set('To', $_e[0]);
                $smtp->set('Subject', $rimbaLib->setting('web_name').' - Berita Terbaru');
                $smtp->set('Errors-to', '<'.$rimbaLib->setting('email').'>');
                $body  = '<p>Hello '.$_e[1].',</p>
                        <p>Berikut berita terbaru dari kami:<br>
                            '.$html.'
                        </p>';
                $message  = $seloLib->templateEmail($body);
                $sent     = $smtp->send($message.'.', TRUE);
                $mylog    = $smtp->log();
                $sentText = 'not sent';
                $headerText = 'does not exist';
                if ($sent){
                    $sentText = 'was sent';
                }
                if ($smtp->exists('Date')){
                    $headerText = 'exists';
                }
            }
            $data = array(
                '_pesan'    => $sentText,
                '_redirect' => true,
                '_page'     => $f3->hive()['BASE'] . '/apost'
            );
        }else{
            $data = array(
                '_pesan'    => 'Kirim emal gagal!',
                '_redirect' => false
            );
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

    public function delData($f3) {
        $data = array();
        if(@$f3->get('PARAMS.id')){
            $tmp    = new PostModel($this->db);
            $params = array('post_id'=>$f3->get('PARAMS.id'));
            $data   = $tmp->delData($params);
        }
        header("Content-type:application/json");
        echo json_encode($data);
    }

}