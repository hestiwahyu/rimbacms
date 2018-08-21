<?php
final class Rimba extends Prefab {

    protected $f3;
    protected $db;
    protected $rimba;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
        $db = new DB\SQL(
            $f3->get('DEVDB'),
            $f3->get('DEVDBUSERNAME'),
            $f3->get('DEVDBPASSWORD'),
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
        );
        $this->db = $db;
        $rimba = new RimbaModel($this->db);
        $this->model = $rimba;
        $f3->set('UI','themes/');
        $f3->set('THEME',$rimba->getTheme());
    }

    function assets($file) {
        $theme = $this->f3->BASE.'/themes/'.$this->f3->THEME.$file;
        return $theme;
    }

    function insert($html='home.html') {
        echo View::instance()->render($this->f3->get('THEME').$html);
    }

    // category
    function category($limit=null,$filter=null) {
        if($filter=='all'){
            $params['field'] = array('category_active'=>'Y');
        }else{
            $offset = $this->f3->get('page');
            $params = $this->f3->get('slug');
            if(@$params['field']){
                array_push($params['field'], array('category_active'=>'Y'));
            }else{
                $params['field'] = array('category_active'=>'Y');
            }
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        return $this->model->getCategory($params);
    }

    function getCategoryBy($id_kat=1) {
        if($id_kat!=null){
            $params['like'] = " (c.category_id='".$id_kat."' OR d.category_seotitle='".$id_kat."') ";
        }else{
            $params = array();
        }
        $result = $this->model->getCategory($params);
        return (@$result[0])?$result[0]:null;
    }

    function getCountCategory($filter=null) {
        if($filter=='all'){
            $params['field'] = array('category_active'=>'Y');
        }else{
            $params = $this->f3->get('slug');
        }
        $_tmp = $this->model->getCountCategory($params);
        $jml = ($_tmp!=null)?$_tmp:0;
        return $jml;
    }

    // tag
    function tag($limit=null,$filter=null) {
        if($filter=='all'){
            $params = array();
            if(@$this->f3->get('tag') && $this->f3->get('tag')!=null){
                $tag = explode(',',$this->f3->get('tag'));
                $_tag = array();
                if($tag!=null){
                    foreach($tag as $r){
                        if($r!=null &&$r!=''){
                            $_tag[] = " (LOWER(a.tag_title)='".strtolower($r)."') ";
                        }
                    }
                }
                if($_tag!=null){
                    $s_tag = implode('OR',$_tag);
                    $params['like'] = " (".$s_tag.") ";
                }
            }
        }else{
            $offset = $this->f3->get('page');
            $params = $this->f3->get('slug');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        return $this->model->getTag($params);
    }

    function getCountTag($filter=null) {
        if($filter=='all'){
            $params = array();
        }else{
            $params = $this->f3->get('slug');
        }
        $_tmp = $this->model->getCountTag($params);
        $jml = ($_tmp!=null)?$_tmp:0;
        return $jml;
    }

    // post
    function getHeadline($limit=null,$sort='publishdate DESC') {
        $offset = ($this->f3->get('page'))?$this->f3->get('page'):1;
        $params = array(
            'field'=>array(
                'post_headline'=>'Y',
                'post_active'=>'Y'
            ),
            'order'=>$sort
        );
        if($limit==null){
            $limit = $this->setting('item_per_page');
        }
        $this->f3->set('limit',$limit);
        $offset = ($offset<=0)?1:$offset;
        $offset = ($offset-1) * $limit;
        $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        return $this->model->getListPostBy($params);
    }

    function getPost($limit=null,$sort='publishdate DESC',$filter=null) {
        $offset = ($this->f3->get('page'))?$this->f3->get('page'):1;
        $params = array(
            'field'=>array(
                'post_active'=>'Y'
            ),
            'order'=>$sort
        );
        if($limit==null){
            $limit = $this->setting('item_per_page');
        }
        $this->f3->set('limit',$limit);
        $offset = ($offset<=0)?1:$offset;
        $offset = ($offset-1) * $limit;
        if($filter=='all'){
            $offset = 0;
        }
        $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        return $this->model->getListPostBy($params);
    }

    function getListPostBy($limit=null,$filter=null) {
        if($filter=='all'){
            $params = array();
        }else{
            $offset = $this->f3->get('page');
            $params = $this->f3->get('slug');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        return $this->model->getListPostBy($params);
    }

    function getPostBy($limit=null,$filter=null) {
        if($filter=='all'){
            $params['field'] = array('post_active'=>'Y');
        }else{
            $offset = $this->f3->get('page');
            $params = $this->f3->get('slug');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        return $this->model->getPostBy($params);
    }

    function getPostByPrev($id_post=1) {
        if($id_post!=null){
            $params['like'] = " a.post_id<" .$id_post;
        }
        $params['prev'] = true;
        return $this->model->getPostBy($params);
    }

    function getPostByNext($id_post=1) {
        if($id_post!=null){
            $params['like'] = " a.post_id>" .$id_post;
        }
        $params['next'] = true;
        return $this->model->getPostBy($params);
    }

    function getPostByCategory($id_kat=1,$limit=null,$sort='publishdate DESC') {
        $params = array(
            'field'=>array(
                'post_active'=>'Y'
            ),
            'order'=>$sort,
            'like'=>" (c.category_id='".$id_kat."' OR d.category_seotitle='".$id_kat."') "
        );
        $offset = $this->f3->get('page');
        if($limit==null){
            $limit = $this->setting('item_per_page');
        }
        $this->f3->set('limit',$limit);
        $offset = ($offset<=0)?1:$offset;
        $offset = ($offset-1) * $limit;
        $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        return $this->model->getListPostBy($params);
    }

    function getOneCategory($id_kat=1) {
        $params = array(
            'field'=>array(
                'post_active'=>'Y'
            ),
            'order'=>'publishdate ' .$sort,
            'like'=>" (c.category_id='".$id_kat."' OR d.category_seotitle='".$id_kat."') "
        );
        return $this->model->getPostBy($params);
    }

    function getCategoryByPost($id_post=1) {
        $params = array(
            'field'=>array(
                'category_active'=>'Y',
                'c.post_id'=>$id_post
            )
        );
        return $this->model->getCategory($params);
    }

    function getRelatedPost($id_post=1,$tag=null,$limit=null,$sort='publishdate DESC') {
        $_tag = explode(',',$tag);
        $_like = '';
        if($_tag != null && $_tag != '' && count($_tag) != 0) {
            foreach($_tag as $r) {
                $_like .= ($_like=='')?'':' OR ';
                $_like .= 'a.post_tag LIKE "%' .trim($r). '%"';
            }
        }
        if($id_post != null){
            $_like = ($_like=='')?' a.post_id!='.$id_post:'(' .$_like. ') AND a.post_id!='.$id_post;
        }
        $params = array(
            'field'=>array(
                'post_active'=>'Y'
            ),
            'like'=>$_like,
            'order'=>$sort
        );
        $offset = $this->f3->get('page');
        if($limit==null){
            $limit = $this->setting('item_per_page');
        }
        $this->f3->set('limit',$limit);
        $offset = ($offset<=0)?1:$offset;
        $offset = ($offset-1) * $limit;
        $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        return $this->model->getListPostBy($params);
    }

    function getCountPost($filter=null) {
        if($filter=='all'){
            $params['field'] = array('post_active'=>'Y');
        }else{
            $params = $filter;
        }
        $_tmp = $this->model->getCountPost($params);
        $jml = ($_tmp!=null)?$_tmp:0;
        return $jml;
    }

    // pages
    function getPagesBy($seotitle=null) {
        if($seotitle==null){
            $params = $this->f3->get('slug');
        }else{
            $params['field'] = array('a.pages_seotitle'=>$seotitle,'a.pages_active'=>'Y');
        }
        return $this->model->getPagesBy($params);
    }

    function pages($limit=null) {
        $params = array();
        if($limit=='all'){
            $params = array();
        }else{
            $offset = $this->f3->get('page');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        return $this->model->getPages($params);
    }

    // gallery
    function getListGalleryBy($limit=null,$filter=null,$mode='list') {
        if($filter=='all'){
            $params = array();
        }else{
            $offset = $this->f3->get('page');
            $params = $this->f3->get('slug');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        if($mode=='list'){
            return $this->model->getListGalleryBy($params);
        }else{
            return $this->model->getImgGalleryBy($params);
        }
    }

    function getGalleryBy($limit=null,$filter=null) {
        if($filter=='all'){
            $params['field'] = array('gallery_active'=>'Y');
        }else{
            $offset = $this->f3->get('page');
            $params = $this->f3->get('slug');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        return $this->model->getGalleryBy($params);
    }

    function getCountGallery($filter=null) {
        if($filter=='all'){
            $params['field'] = array('gallery_active'=>'Y');
        }else{
            $params = $this->f3->get('slug');
        }
        $_tmp = $this->model->getCountGallery($params);
        $jml = ($_tmp!=null)?$_tmp:0;
        return $jml;
    }

    function getCountImagesGallery($filter=null) {
        if($filter=='all'){
            $params['field'] = array('gallery_active'=>'Y');
        }else{
            $params = $this->f3->get('slug');
        }
        $_tmp = $this->model->getCountImagesGallery($params);
        $jml = ($_tmp!=null)?$_tmp:0;
        return $jml;
    }

    // comment
    function getCountComment($id_post=null) {
        if($id_post!=null){
            $params = array(
                'field'=>array(
                    'comment_active'=>'Y',
                    'comment_post_id'=>$id_post
                )
            );
        }else{
            $params = array(
                'field'=>array(
                    'comment_active'=>'Y'
                )
            );
        }
        $_tmp = $this->model->getCountComment($params);
        $jml = ($_tmp!=null)?$_tmp:0;
        return $jml;
    }

    function getComment($id_post=null,$limit=null,$sort='id ASC',$filter=null) {
        if($id_post!=null){
            $params = array(
                'field'=>array(
                    'comment_active'=>'Y',
                    'comment_post_id'=>$id_post
                ),
                'id_post'=>$id_post
            );
        }else{
            $params = array(
                'field'=>array(
                    'comment_active'=>'Y'
                )
            );
        }
        if($limit!=null){
            $offset = $this->f3->get('page');
            if($limit==null){
                $limit = $this->setting('item_per_page');
            }
            $this->f3->set('limit',$limit);
            $offset = ($offset<=0)?1:$offset;
            $offset = ($offset-1) * $limit;
            if($filter=='all'){
                $offset = 0;
            }
            $params['limit'] = ' LIMIT '.$limit.' OFFSET '.$offset;
        }
        $params['order'] = $sort;
        return $this->model->getComment($params);
    }

    // sidebar
    function sidebar($position='L') {
        $params['field'] = array(
            'a.widget_position'=>$position,
            'a.widget_active'=>'Y'
        );
        $widget = $this->model->getWidget($params);
        if($widget != null){
            foreach($widget as $r){
                $file = 'app/widget/'.$r['component'].'/'.ucfirst($r['component']).'.php';
                if(file_exists($file) && $r['type']=='widget'){
                    $class = require($file);
                    $class->index($r);
                }else if($r['component']=='textarea'){
                    $_html = '<div class="p-3">';
                    if($r['title']!=null){
                        $_html .= '<h1>' .$r['title']. '</h1>';
                    }
                    $_html .= $r['value'];
                    $_html .= '</div>';
                    echo $_html;
                }else if($r['type']=='component'){
                    $_html = '<div class="p-3">';
                    if($r['title']!=null){
                        $_html .= '<h1>' .$r['title']. '</h1>';
                    }
                    $myvar = $r['component'];
                    $_html .= $this->$myvar();
                    $_html .= '</div>';
                    echo $_html;
                }
            }
        }
    }

    // setting
    function setting($option='favicon') {
        $params = array(
            'field'=>array(
                'setting_options'=>$option
            )
        );
        return $this->model->getSetting($params);
    }

    // menu
    function getMenu($class,$type='dropdown',$title=null) {
        $title = ($title==null)?$this->f3->get('LANG'):$title;
        $_menu = $this->model->getMenu($class,$type,$title);
        return $_menu;
    }

    // breadcrumb
    function getBreadcrumb($tag='ol',$class='breadcrumb') {
        $_html = '';
        $_b = $this->f3->get('breadcrumb');
        $_i = 1;
        $_j = count($_b);
        if($_b != null) {
            $_html .= '<'.$tag.' class="'.$class.'">';
            foreach($_b as $k => $v) {
                $_act = ($_i==1)?'active':'';
                $_label = ($_i!=$_j)?'<a href="' .$v. '">' .$k. '</a>':'<a href="#">'.$k.'</a>';
                $_html .= '<li class="breadcrumb-item ' .$_act. '">' .$_label. '</li>';
                $_i++;
            }
            $_html .= '</'.$tag.'>';
        }
        return $_html;
    }

    // pagging
    function getPaging() {
        extract($this->f3->get('paging'));
        $limit = $this->f3->get('limit');
        if($jml_all>$limit){
            $_html = '<ul class="pagination">';
            $page = ($page == null || $page == 0)?1:$page;
            if($page == 1) {
                $_html .= '<li class="disabled"><a href="#">'.Selo::instance()->setLang('first').'</a></li>
                        <li class="disabled"><a href="#">&laquo;</a></li>';
            } else {
                $link_prev = ($page > 1)? $page - 1 : 1;
                $_html .= '<li><a href="' .$url. '?page=1">'.Selo::instance()->setLang('first').'</a></li>
                        <li><a href="' .$url. '?page=' .$link_prev. '">&laquo;</a></li>';
            }

            $jml_page = ceil($jml_all / $limit);
            $jml_number = 2;
            $start_number = ($page > $jml_number)? $page - $jml_number : 1;
            $end_number = ($page < ($jml_page - $jml_number))? $page + $jml_number : $jml_page;

            for($i = $start_number; $i <= $end_number; $i++){
                $link_active = ($page == $i)? ' class="active"' : '';
                $_html .= '<li ' .$link_active. '>
                            <a href="' .$url. '?page=' .$i. '">' .$i. '</a>
                            </li>';
            }

            if($page == $jml_page) {
                $_html .= '<li class="disabled"><a href="#">&raquo;</a></li>
                        <li class="disabled"><a href="#">'.Selo::instance()->setLang('last').'</a></li>';
            } else {
                $link_next = ($page < $jml_page)? $page + 1 : $jml_page;
                $_html .= '<li><a href="' .$url. '?page=' .$link_next. '">&raquo;</a></li>
                        <li><a href="' .$url. '?page=' .$jml_page. '">'.Selo::instance()->setLang('last').'</a></li>';
            }
            $_html .= '</ul>';
        }else{
            $_html = '';
        }
        return $_html;
    }

    // component
    function search($show=true) {
        $_html = '';
        $keyword = (isset($_GET['search']))?$_GET['search']:'';
        $form = '<div class="blog-search">
                    <form method="get" action="'.$this->f3->BASE.'/post" id="form-search">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" value="'.$keyword.'" placeholder="'.Selo::instance()->setLang('search').'" required>
                            <div class="input-group-prepend">
                                <button class="input-group-text" type="submit">
                                    <span class="fa fa-search"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>';
        if($show==true){
            $_html = $form;
        }
        if(isset($_GET['search']) && $show==false) {
            $_html = $form;
        }
        return $_html;
    }

    function subscribe() {
        $form = '<div class="blog-subscribe">
                <div id="form-default">
                    <form method="post" action="'.$this->f3->BASE.'/saveSubscribe" id="form-subscribe">
                        <div class="form-group">
                            <label>'.Selo::instance()->setLang('name').'</label>
                            <input type="text" name="subscribe_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>'.Selo::instance()->setLang('email').'</label>
                            <input type="email" name="subscribe_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-outline-secondary" type="submit">'.Selo::instance()->setLang('save').'</button>
                        </div>
                    </form>
                </div>
            </div>';
        $_html = $form;
        return $_html;
    }

    function login() {
        $form = '<div class="blog-login">
                <div id="form-default">
                    <form method="post" action="'.$this->f3->BASE.'/doLogin" id="form-subscribe">
                        <div class="form-group">
                            <label>'.Selo::instance()->setLang('username').'</label>
                            <input type="text" name="user_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>'.Selo::instance()->setLang('password').'</label>
                            <input type="password" name="user_pass" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-outline-secondary" type="submit">'.Selo::instance()->setLang('login').'</button>
                        </div>
                    </form>
                </div>
            </div>';
        $_html = $form;
        return $_html;
    }

    function meta() {
        echo View::instance()->render('meta.html');
    }

}