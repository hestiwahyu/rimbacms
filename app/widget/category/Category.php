<?php

final class Category extends Prefab {

	protected $f3;

    function __construct() {
        $f3 = Base::instance();
        $this->f3 = $f3;
    }

    public function index($data=null) {
    	$_data = array();
    	$_data = Rimba::instance()->category(null,'all');
        $title = (@$data['title'])?$data['title']:Selo::instance()->setLang('category');
        $this->f3->set('title',$title);
        $_cat = array();
        if($_data!=null){
            foreach($_data as $r){
                $_cat[$r['parent']][$r['id']] = $r;
            }
        }
        $cat = $this->subCategory($_cat,0);
    	$this->f3->set('data',$cat);
        $this->f3->set('ESCAPE', false);
        echo View::instance()->render('../app/widget/category/index.html');
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

return Category::instance();