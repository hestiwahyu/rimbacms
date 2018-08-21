<?php

class DashboardController extends Controller {

    public function index($f3) {
        $user = $f3->get('SESSION.user');
        if($user==null){
            $f3->reroute('/home');
        }
        $f3->set('urlHome', $f3->hive()['BASE'] . '/adashboard/viewData');
        echo View::instance()->render('layout.html');
    }

    public function viewData($f3) {
        if(Selo::instance()->access('dashboard',1)==true){
            $f3->set('breadcrumb', array(
                '<i class="fa fa-home"></i>' => $f3->get('BASE').'/adashboard',
                'Dashboard' => $f3->get('BASE').'/adashboard'
            ));

            $tmp_post = new PostModel($this->db);
            $cpost = $tmp_post->countAllData();
            $f3->set('cpost', $cpost);

            $tmp_pages = new PagesModel($this->db);
            $cpages = $tmp_pages->countAllData();
            $f3->set('cpages', $cpages);

            $tmp_category = new CategoryModel($this->db);
            $ccategory = $tmp_category->countAllData();
            $f3->set('ccategory', $ccategory);

            $tmp_tag = new TagModel($this->db);
            $ctag = $tmp_tag->countAllData();
            $f3->set('ctag', $ctag);

            $tmp_comment = new CommentModel($this->db);
            $ccomment = $tmp_comment->countAllData();
            $f3->set('ccomment', $ccomment);

            $tmp_subscribe = new SubscribeModel($this->db);
            $csubscribe = $tmp_subscribe->countAllData();
            $f3->set('csubscribe', $csubscribe);

            $tmp_gallery = new GalleryModel($this->db);
            $cgallery = $tmp_gallery->countAllData();
            $f3->set('cgallery', $cgallery);

            $tmp_user = new UserModel($this->db);
            $cuser = $tmp_user->countAllData();
            $f3->set('cuser', $cuser);

            $params_visitor['type'] = 'B';
            $tmp_visitor = new DashboardModel($this->db);
            $cvisitor = $tmp_visitor->getVisitor($params_visitor);
            $label = 'Label';
            $visitor = '1';
            $hits = '1';
            $i = 1;
            if($cvisitor!=null){
                foreach($cvisitor as $r){
                    if($i==1){
                        $label = $r['label'];
                        $visitor = $r['visitor'];
                        $hits = $r['hits'];
                    }else{
                        $label .= ','.$r['label'];
                        $visitor .= ','.$r['visitor'];
                        $hits .= ','.$r['hits'];
                    }
                    $i++;
                }
            }
            $f3->set('label', $label);
            $f3->set('visitor', $visitor);
            $f3->set('hits', $hits);

            $_popular = Rimba::instance()->getPost('5','hits DESC','all');
            $f3->set('popular',$_popular);
            $_latest = Rimba::instance()->getPost('5','publishdate DESC','all');
            $f3->set('latest',$_latest);
            $_comment = Rimba::instance()->getComment(null,'5','id DESC','all');
            $f3->set('comment',$_comment);

            $f3->set('UI','app/component/');
            $f3->set('ESCAPE', false);
            echo View::instance()->render('/dashboard/view.html');
        }else{
            echo 'Forbidden access!';
        }
    }

}