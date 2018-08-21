<?php

final class Tag extends Prefab {

	protected $f3;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
    }

    public function index($data=null) {
    	$f3 = Base::instance();
    	$_data = array();
    	$_data = Rimba::instance()->tag(null,'all');
        $title = (@$data['title'])?$data['title']:Selo::instance()->setLang('tag');
        $f3->set('title',$title);
    	$f3->set('data',$_data);
        echo View::instance()->render('../app/widget/tag/index.html');
    }

}

return Tag::instance();