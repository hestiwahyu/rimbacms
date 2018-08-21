<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

final class Post extends Prefab {

	protected $f3;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
    }

    public function index($data=null) {
    	$f3 = Base::instance();
        $_popular = Rimba::instance()->getPost('3','hits DESC','all');
        $_latest  = Rimba::instance()->getPost('3','publishdate DESC','all');
        $_command = Rimba::instance()->getComment(null,'3','id DESC','all');
        $title = (@$data['title'])?$data['title']:Selo::instance()->setLang('post');
        $f3->set('title',$title);
        $f3->set('popular',$_popular);
        $f3->set('latest',$_latest);
    	$f3->set('comment',$_command);
        $this->f3->set('ESCAPE', false);
        echo View::instance()->render('../app/widget/post/index.html');
    }

}

return Post::instance();