<?php

class RimbaController {

    protected $f3;
    protected $db;
    protected $rimbaModel;
    protected $rimbaLib;
    protected $seloLib;
    protected $visitorLib;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
        $db = new DB\SQL(
            $f3->get('DEVDB'),
            $f3->get('DEVDBUSERNAME'),
            $f3->get('DEVDBPASSWORD'),
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
        );
        $this->db         = $db;
        $rimbaModel       = new RimbaModel($this->db);
        $this->rimbaModel = $rimbaModel;
        $rimbaLib         = Rimba::instance();
        $this->rimbaLib   = $rimbaLib;
        $seloLib          = Selo::instance();
        $this->seloLib    = $seloLib;
        $visitorLib       = Visitor::instance();
        $this->visitorLib = $visitorLib;
        $f3->set('DB',$db);
        $f3->set('UI','themes/');
        $f3->set('THEME',$rimbaModel->getTheme());
        $lang_default = Rimba::instance()->setting('lang_default');
        if(!@$f3->get('SESSION.lang')){
            $langd = 'app/lang/'.$lang_default.'.php';
            include_once $langd;
            $f3->set('SESSION._lang',$_lang);
            $f3->set('SESSION.lang',$lang_default);
            $f3->set('LANG',$lang_default);
        }
        $f3->set('keywords',$this->rimbaLib->setting('web_keyword'));
        $f3->set('description',$this->rimbaLib->setting('web_description'));
        $f3->set('url','');
        $this->visitorSave();
    }

    public function home($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE')
        ));
        $f3->set('title',$home);
        $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
        $f3->set('ishome',true);
        $f3->set('content','home.html');

        $tmp    = new TestimoniModel($this->db);
        $paramsT['limit'] = ' LIMIT 5 OFFSET 0';
        $paramsT['field'] = array('a.testimoni_active'=>'Y');
        $tes = $tmp->getTestimoni($paramsT);
        $f3->set('testimoni',$tes);

        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    public function blog($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            'Blog' => ''
        ));
        $f3->set('title',$home);
        $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
        $f3->set('ishome',true);
        $f3->set('content','blog.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    public function doc($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $doc = $this->seloLib->setLang('documentation');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            $doc => ''
        ));
        $f3->set('title',$doc);
        $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
        $f3->set('content','doc.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    // category
    public function category($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $cat = $this->seloLib->setLang('category');
        $breadcrumb = array(
            $home => $f3->get('BASE'),
            $cat => $f3->get('BASE').'/category'
        );
        $page = (@$_GET['page'] && $_GET['page']!=null && $_GET['page']!=1)?$_GET['page']:1;
        $f3->set('page',$page);
        if(@$f3->get('PARAMS.title')){
            $f3->set('detail',true);
            $_slug = $f3->get('PARAMS.title');
            $params['field'] = array('d.category_seotitle'=>$_slug,'a.post_active'=>'Y');
            $params_kat['field'] = array('a.category_seotitle'=>$_slug);
            $_tmp_kat            = $this->rimbaModel->getCategory($params_kat);
            $subtitle            = (@$_tmp_kat[0]['title'])?' ' .ucwords($_tmp_kat[0]['title']):'';
            $picture             = (@$_tmp_kat[0]['picture'])?$f3->get('BASE').'/images/thumb/'.$_tmp_kat[0]['picture']:$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon');
            $f3->set('title',$cat .$subtitle);
            $f3->set('picture',$picture);
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));
            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/category/'.$f3->get('PARAMS.title'),
                'jml_all' => $this->rimbaLib->getCountPost($params)
            );
            $f3->set('paging',$params_paging);
            $url = $f3->get('BASE').'/category/'.$f3->get('PARAMS.title');
            $f3->set('url',$url);
        }else{
            $f3->set('title',$cat);
            $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
            $f3->set('detail',false);
            $params['field'] = array('a.category_active'=>'Y');
            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/category',
                'jml_all' => $this->rimbaLib->getCountCategory()
            );
            $f3->set('paging',$params_paging);
            $url = $f3->get('BASE').'/category';
            $f3->set('url',$url);
        }
        $f3->set('slug',$params);
        $f3->set('breadcrumb', $breadcrumb);
        $f3->set('content','category.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    // tag
    public function tag($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $tag = $this->seloLib->setLang('tag');
        $breadcrumb = array(
            $home => $f3->get('BASE'),
            $tag => $f3->get('BASE').'/tag'
        );
        $page = (@$_GET['page'] && $_GET['page']!=null && $_GET['page']!=1)?$_GET['page']:1;
        $f3->set('page',$page);
        if(@$f3->get('PARAMS.title')){
            $f3->set('detail',true);
            $params['field'] = array('a.post_active'=>'Y');
            $params['like']  = " a.post_tag like '%" .str_replace('-',' ',$f3->get('PARAMS.title')). "%'";

            $params_tag['field'] = array('a.tag_seotitle'=>$f3->get('PARAMS.title'));
            $_tmp_tag            = $this->rimbaModel->getTag($params_tag);
            $subtitle            = (@$_tmp_tag[0]['title'])?' ' .ucwords($_tmp_tag[0]['title']):'';
            $f3->set('title',$cat .$subtitle);
            $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));

            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/tag/'.$f3->get('PARAMS.title'),
                'jml_all' => $this->rimbaLib->getCountPost($params)
            );
            $f3->set('paging',$params_paging);
            $url = $f3->get('BASE').'/tag/'.$f3->get('PARAMS.title');
            $f3->set('url',$url);
        }else{
            $f3->set('title',$tag);
            $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
            $f3->set('detail',false);
            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/tag',
                'jml_all' => $this->rimbaLib->getCountTag()
            );
            $f3->set('paging',$params_paging);
            $url = $f3->get('BASE').'/tag';
            $f3->set('url',$url);
        }
        $f3->set('slug',$params);
        $f3->set('breadcrumb', $breadcrumb);
        $f3->set('content','tag.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    // post
    public function post($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $post = $this->seloLib->setLang('post');
        $breadcrumb = array(
            $home => $f3->get('BASE'),
            $post => $f3->get('BASE').'/post'
        );
        $page = (@$_GET['page'] && $_GET['page']!=null && $_GET['page']!=1)?$_GET['page']:1;
        $f3->set('page',$page);
        if(@$f3->get('PARAMS.title')){
            $f3->set('detail',true);
            $params['field']  = $params_s['field'] = array('a.post_seotitle'=>$f3->get('PARAMS.title'));
            $params['select'] = "a.post_id AS id,b.post_title AS title,b.post_content AS content,a.post_picture AS picture,a.post_tag AS tag,a.post_seotitle AS seotitle";
            $_tmp             = $this->rimbaModel->getPostBy($params);
            $subtitle         = (@$_tmp['title'])?' ' .ucwords($_tmp['title']):'';
            $picture          = (@$_tmp['picture'])?$f3->get('BASE').'/images/thumb/'.$_tmp['picture']:$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon');
            $keywords = $this->rimbaLib->setting('web_keyword');
            $keywords .= (@$_tmp['tag'])?','.$_tmp['tag']:'';
            $keywords .= (@$_tmp['title'])?','.$_tmp['title']:'';
            $description = (@$_tmp['title'])?$_tmp['title']:'';
            $description .= (@$_tmp['content'])?' '.Selo::instance()->cutText($_tmp['content'],200):'';
            $url = (@$_tmp['seotitle'])?$f3->get('BASE').'/post/'.$_tmp['seotitle']:'';
            $tag = (@$_tmp['tag'])?','.$_tmp['tag']:'';
            $f3->set('slug',$params_s);
            $f3->set('title',$subtitle);
            $f3->set('picture',$picture);
            $f3->set('keywords',$keywords);
            $f3->set('description',$description);
            $f3->set('tag',$tag);
            $f3->set('url',$url);
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));
            $this->rimbaModel->hitsPost($f3->get('PARAMS.title'));
        }else{
            $like = false;
            $f3->set('detail',false);
            $f3->set('title',$post);
            $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
            $params['field'] = array('a.post_active'=>'Y');

            if(@$f3->get('PARAMS.year') && @$f3->get('PARAMS.month')){
                $archives = $f3->get('PARAMS.year').'-'.$f3->get('PARAMS.month');
                if($like==false){
                    $params['like'] = " DATE_FORMAT(a.post_publishdate,'%Y-%m')='" .$archives. "' ";
                }else{
                    $params['like'] .= " AND DATE_FORMAT(a.post_publishdate,'%Y-%m')='" .$archives. "' ";
                }
                $like = true;
            }

            if(@$_GET['search'] && $_GET['search']!=null){
                $keyword = strtolower($_GET['search']);
                if($like==false){
                    $params['like'] = " (b.post_title LIKE '%" .$keyword. "%' OR b.post_content LIKE '%" .$keyword. "%' OR e.category_title LIKE '%" .$keyword. "%') ";
                }else{
                    $params['like'] .= " AND (b.post_title LIKE '%" .$keyword. "%' OR b.post_content LIKE '%" .$keyword. "%' OR e.category_title LIKE '%" .$keyword. "%') ";
                }
                $like = true;
            }

            $f3->set('slug',$params);
            $url = $f3->get('BASE').'/post';
            $f3->set('url',$url);
            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/post',
                'jml_all' => $this->rimbaLib->getCountPost($params)
            );
            $f3->set('paging',$params_paging);
        }
        $f3->set('breadcrumb', $breadcrumb);
        $f3->set('content','post.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    // gallery
    public function gallery($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $gallery = $this->seloLib->setLang('gallery');
        $breadcrumb = array(
            $home => $f3->get('BASE'),
            $gallery => $f3->get('BASE').'/gallery'
        );
        $page = (@$_GET['page'] && $_GET['page']!=null && $_GET['page']!=1)?$_GET['page']:1;
        $f3->set('page',$page);
        if(@$f3->get('PARAMS.title')){
            $f3->set('detail',true);
            $_limit = $this->rimbaLib->setting('item_per_page');
            $params['field']  = $params_s['field'] = array('a.gallery_seotitle'=>$f3->get('PARAMS.title'));
            $params['select'] = "a.gallery_id AS id,a.gallery_title AS title,b.images_picture AS picture,a.gallery_seotitle AS seotitle,b.images_title AS imgtitle,b.images_content AS imgcontent";
            $_tmp             = $this->rimbaModel->getGalleryBy($params);
            $subtitle         = (@$_tmp['title'])?' ' .ucwords($_tmp['title']):'';
            $picture          = (@$_tmp['picture'])?$f3->get('BASE').'/images/thumb/'.$_tmp['picture']:$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon');
            $keywords = $this->rimbaLib->setting('web_keyword');
            $keywords .= (@$_tmp['title'])?','.$_tmp['title']:'';
            $description = (@$_tmp['title'])?$_tmp['title']:'';
            $description .= (@$_tmp['imgtitle'])?' '.Selo::instance()->cutText($_tmp['imgtitle'],100):'';
            $description .= (@$_tmp['imgcontent'])?' '.Selo::instance()->cutText($_tmp['imgcontent'],100):'';
            $url = (@$_tmp['seotitle'])?$f3->get('BASE').'/gallery/'.$_tmp['seotitle']:'';
            $f3->set('slug',$params_s);
            $f3->set('title',$subtitle);
            $f3->set('picture',$picture);
            $f3->set('keywords',$keywords);
            $f3->set('description',$description);
            $f3->set('url',$url);
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));
            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/gallery/'.$f3->get('PARAMS.title'),
                'jml_all' => $this->rimbaLib->getCountImagesGallery()
            );
            $f3->set('paging',$params_paging);
            $this->rimbaModel->hitsGallery($f3->get('PARAMS.title'));
        }else{
            $f3->set('detail',false);
            $f3->set('title',$gallery);
            $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
            $params['field'] = array('a.gallery_active'=>'Y');
            $f3->set('slug',$params);
            $url = $f3->get('BASE').'/gallery';
            $f3->set('url',$url);
            $params_paging = array(
                'page'    => $_GET['page'],
                'url'     => $f3->get('BASE').'/gallery',
                'jml_all' => $this->rimbaLib->getCountGallery()
            );
            $f3->set('paging',$params_paging);
        }
        $f3->set('breadcrumb', $breadcrumb);
        $f3->set('content','gallery.html');
        echo View::instance()->render('layout-front.html');
    }

    // pages
    public function pages($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $breadcrumb = array(
            $home => $f3->get('BASE')
        );
        if(@$f3->get('PARAMS.title')){
            $f3->set('detail',true);
            $params['field']  = $params_s['field'] = array('a.pages_seotitle'=>$f3->get('PARAMS.title'),'a.pages_active'=>'Y');
            $params['select'] = "b.pages_title AS title,b.pages_content AS content,a.pages_picture AS picture,a.pages_seotitle AS seotitle";
            $_tmp             = $this->rimbaModel->getPagesBy($params);
            $subtitle         = (@$_tmp['title'])?' ' .ucwords($_tmp['title']):'';
            $picture          = (@$_tmp['picture'])?$f3->get('BASE').'/images/thumb/'.$_tmp['picture']:$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon');
            $keywords = $this->rimbaLib->setting('web_keyword');
            $keywords .= (@$_tmp['title'])?','.$_tmp['title']:'';
            $description = (@$_tmp['title'])?$_tmp['title']:'';
            $description .= (@$_tmp['content'])?' '.Selo::instance()->cutText($_tmp['content'],200):'';
            $url = (@$_tmp['seotitle'])?$f3->get('BASE').'/'.$_tmp['seotitle']:'';
            $f3->set('slug',$params_s);
            $f3->set('title',$subtitle);
            $f3->set('picture',$picture);
            $f3->set('keywords',$keywords);
            $f3->set('description',$description);
            $f3->set('url',$url);
            $breadcrumb = array_merge($breadcrumb,array($subtitle => ''));
        }else{
            $f3->set('detail',false);
            $f3->set('picture',$f3->get('BASE').'/images/thumb/'.$this->rimbaLib->setting('favicon'));
        }
        $f3->set('breadcrumb', $breadcrumb);
        $f3->set('content','pages.html');
        $f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    // lang
    public function lang($f3) {
        if(@$f3->get('PARAMS.lang')){
            $_tmp = $f3->get('PARAMS.lang');
        }else{
            $lang_default = Rimba::instance()->setting('lang_default');
            $_tmp = $lang_default;
        }
        $lang = 'app/lang/' .$_tmp. '.php';
        $langd = 'app/lang/id.php';
        if(file_exists($lang)){
            include_once $lang;
            $f3->set('SESSION._lang',$_lang);
            $f3->set('SESSION.lang','id');
            $f3->set('LANG','id');
        }else{
            include_once $langd;
            $f3->set('SESSION._lang',$_lang);
            $f3->set('SESSION.lang','id');
            $f3->set('LANG','id');
        }
        $f3->reroute('/home');
    }

    // comment
    public function saveComment($f3) {
        $params = $_POST;
        $data   = $this->rimbaModel->saveComment($params);
        if(@$data['_redirect']==true && @$params['comment_url']){
            $f3->reroute('/post/' .$params['comment_url']);
        }else{
            $f3->reroute('/home');
        }
    }

    // component
    public function subscribe($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $subscribe = $this->seloLib->setLang('subscribe');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            $subscribe => ''
        ));
        $f3->set('title',$subscribe);
        $f3->set('content','subscribe.html');
        echo View::instance()->render('layout-front.html');
    }
    public function saveSubscribe($f3) {
        $params = $_POST;
        $data   = $this->rimbaModel->saveSubscribe($params);
        if(@$data['_redirect']==true){
            $f3->reroute('/home');
        }else{
            $f3->reroute('/subscribe');
        }
    }

    // login
    public function login($f3) {
        $user = $f3->get('SESSION.user');
        if($user!=null){
            $f3->reroute('/adashboard');
        }
        $home = $this->seloLib->setLang('home');
        $login = $this->seloLib->setLang('login');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            $login => ''
        ));
        $f3->set('title',$login);
        $f3->set('content','login.html');
        echo View::instance()->render('layout-front.html');
    }
    public function doLogin($f3) {
        $_post = $_POST;
        $params['field'] = array(
            'user_user_name' => (@$_post['user_name'])?$_post['user_name']:'',
            'user_password' => (@$_post['user_pass'])?md5($_post['user_pass']):'',
            'user_active' => 'Y'
        );
        $data = $this->rimbaModel->getUserBy($params);
        if(@$data!=null){
            $f3->set('SESSION.user',$data);
            if(@$data['ugroup'] && $data['ugroup']!=null){
                $_group = explode(',', $data['ugroup']);
                if($_group!=null){
                    $_groupMerge = array();
                    foreach($_group as $g){
                        $_groupMerge = $_groupMerge+Selo::instance()->userGroup($g)['access'];
                    }
                    $_groupAccess = array();
                    if($_groupMerge!=null){
                        foreach ($_groupMerge as $kgm => $vgm){
                            if($vgm!=null){
                                foreach($vgm as $kac => $vac){
                                    $_groupAccess[$kgm][$vac]=$vac;
                                }
                            }
                        }
                    }
                    $f3->set('SESSION.access',$_groupAccess);
                }
            }
            $this->rimbaModel->updateUser();
            $f3->reroute('/adashboard');
        }else{
            $f3->reroute('/home');
        }
    }
    public function logout($f3) {
        $data = array();
        $f3->set('SESSION.user',$data);
        $f3->set('SESSION.access',$data);
        $f3->reroute('/home');
    }

    // maintenance status
    public function maintenance() {
        $f3 = Base::instance();
        $maintenance = $this->seloLib->setLang('maintenance');
        $f3->set('title',$maintenance);
        if($this->rimbaLib->setting('maintenance')=='Y'){
            echo View::instance()->render($this->rimbaModel->getTheme().'maintenance.html');
            exit();
        }
    }

    // cron publish post
    public function publish() {
        $date1 = date('Y-m-d').' 00:00:00';
        $date2 = date('Y-m-d').' 23:59:59';
        $params['field'] = array('post_active'=>'N');
        $params['like']  = "post_publishdate BETWEEN '".$date1."' AND '".$date2."'";
        $data      = $this->rimbaModel->getListPostBy($params);
        $publish   = $this->rimbaModel->publishPost($params);
        $subscribe = $this->rimbaModel->subscribe();

        $_data = 'Berikut berita terbaru dari kami:<br><ol>';
        if($data!=null){
            foreach($data as $r){
                $_data .= '<li><a href="'.$this->f3->get('BASE').'/post/'.$r['seotitle'].'">'.$r['title'].'</a></li>';
            }
        }
        $_data .= '</ol>';

        if($data!=null){
            if($subscribe!=null){
                foreach($subscribe as $r){
                    $host   = $this->rimbaLib->setting('mail_hostname');
                    $port   = $this->rimbaLib->setting('mail_port');
                    $scheme = $this->rimbaLib->setting('mail_protocol');
                    $user   = $this->rimbaLib->setting('mail_username');
                    $pw     = $this->rimbaLib->setting('mail_password');
                    $smtp = new SMTP ($host,$port,$scheme,$user,$pw);
                    $smtp->set('Content-type', 'text/html; charset=UTF-8');
                    $smtp->set('From', '"Info Rimba Media" <'.$this->rimbaLib->setting('email').'>');
                    $smtp->set('To', $r['email']);
                    $smtp->set('Subject', $this->rimbaLib->setting('web_name').' - Berita Terbaru');
                    $smtp->set('Errors-to', '<'.$this->rimbaLib->setting('email').'>');
                    $body  = '<p>Hello '.$r['name'].',</p>
                        <p>Berikut berita terbaru dari kami:<br>
                            '.$_data.'
                        </p>';
                    $message  = $this->seloLib->templateEmail($body);
                    $sent     = $smtp->send($message, TRUE);
                    $mylog    = $smtp->log();
                    $sentText = 'not sent';
                    $headerText = 'does not exist';
                    if ($sent){
                        $sentText = 'was sent';
                    }
                    if ($smtp->exists('Date')){
                        $headerText = 'exists';
                    }
                    echo "email result: " . $sentText . ",mylog: " . $mylog . ", header: " . $headerText;
                }
            }
        }
    }

    // log visitor
    public function visitorSave() {
        $data = $this->rimbaModel->visitorSave();
    }

    // sitemap
    public function sitemap($f3) {
        $this->maintenance();
        $home = $this->seloLib->setLang('home');
        $sitemap = $this->seloLib->setLang('sitemap');
        $f3->set('breadcrumb', array(
            $home => $f3->get('BASE'),
            $sitemap => ''
        ));
        $f3->set('title',$sitemap);
        $f3->set('content','sitemap.html');

        // category
        $_cat = Rimba::instance()->category(null,'all');
        $_cat2 = array();
        if($_cat!=null){
            foreach($_cat as $r){
                $_cat2[$r['parent']][$r['id']] = $r;
            }
        }
        $cat = $this->subCategory($_cat2,0);
        $this->f3->set('cat',$cat);

        // tag
        $f3->set('tag',Rimba::instance()->tag(null,'all'));

        // pages
        $f3->set('pages',Rimba::instance()->pages('all'));

        // post
        $f3->set('post',Rimba::instance()->getPost());

        $this->f3->set('ESCAPE', false);
        echo View::instance()->render('layout-front.html');
    }

    public function xml($f3) {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
        $xml .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';

        // menu
        $title = $f3->get('LANG');
        $menu = $this->rimbaModel->getMenu(null,'xml',$title);
        if($menu!=null){
            foreach($menu as $r){
                $xml .= '<url><loc>https://rimbamedia.com'.$r['url'].'</loc></url>';
            }
        }

        // category
        $cat = Rimba::instance()->category(null,'all');
        if($cat!=null){
            foreach($cat as $r){
                $xml .= '<url><loc>https://rimbamedia.com/category/'.$r['seotitle'].'</loc></url>';
            }
        }

        // tag
        $tag = Rimba::instance()->tag(null,'all');
        if($tag!=null){
            foreach($tag as $r){
                $xml .= '<url><loc>https://rimbamedia.com/tag/'.$r['seotitle'].'</loc></url>';
            }
        }

        // pages
        $pages = Rimba::instance()->pages('all');
        if($pages!=null){
            foreach($pages as $r){
                $xml .= '<url><loc>https://rimbamedia.com/'.$r['seotitle'].'</loc></url>';
            }
        }

        // post
        $post = Rimba::instance()->getPost();
        if($post!=null){
            foreach($post as $r){
                $xml .= '<url><loc>https://rimbamedia.com/post/'.$r['seotitle'].'</loc></url>';
            }
        }

        $xml .= '</urlset>';

        $fp = fopen("sitemap.xml","wb");
        fwrite($fp,$xml);
        fclose($fp);
    }

    public function subCategory($_cat,$parent) {
        $html = '';
        if(@$_cat[$parent] && $_cat[$parent]!=null){
            $html .= '<ul>';
            foreach($_cat[$parent] as $r){
                if(@$_cat[$r['id']] && $_cat[$r['id']]!=null){
                    $html .= '<li><a href="'.$this->f3->get('BASE').'/category/'.$r['seotitle'].'" title="'.ucwords($r['title']).'">'.ucwords($r['title']).'</a>';
                    $html .= $this->subCategory($_cat,$r['id']);
                    $html .= '</li>';
                }else{
                    $html .= '<li><a href="'.$this->f3->get('BASE').'/category/'.$r['seotitle'].'" title="'.ucwords($r['title']).'">'.ucwords($r['title']).'</a></li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

}