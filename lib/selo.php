<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

final class Selo extends Prefab {

    function cutText($text, $length, $mode = 2) {
        if (strlen($text) > $length){
            if ($mode != 1) {
                $char = $text{$length - 1};
                switch($mode) {
                    case 2: 
                        while($char != ' ') {
                            $char = $text{--$length};
                        }
                    case 3:
                        while($char != ' ') {
                            $char = $text{++$num_char};
                        }
                }
            }
            $dot = (strlen($text) > $length)?'...':'';
            return substr($text, 0, $length) . $dot;
        }else{
            return $text;
        }
    }

    function setLang($index=null) {
        $f3 = Base::instance();
        $_lang = $f3->get('SESSION._lang');
        return (@$_lang[$index])?$_lang[$index]:ucwords(str_replace('_',' ',$index));
    }

    function getUser($index=null) {
        $f3 = Base::instance();
        $_lang = $f3->get('SESSION.user');
        return (@$_lang[$index])?$_lang[$index]:ucwords(str_replace('_',' ',$index));
    }

    function dateFormat($date='2018-04-01 00:00:00',$format='F d, Y') {
        return date($format,strtotime($date));
        // date('F d, Y',strtotime('2018-04-01 00:00:00'));
    }

    function userGroup($id=null) {
        $data = array(
            1 => array(
                'id' => 1,
                'name' => 'Administrator',
                'access' => array(
                    'dashboard' => array(1),
                    'category' => array(1,2,3,4,5),
                    'tag' => array(1,2,3,4),
                    'post' => array(1,2,3,4,5,6),
                    'pages' => array(1,2,3,4,5),
                    'comment' => array(1,3,4,5),
                    'subscribe' => array(1,2,3,4,5),
                    'gallery' => array(1,2,3,4,5),
                    'menu' => array(1,2,3,4,5),
                    'user' => array(1,2,3,4,5),
                    'theme' => array(1,2,3,4,5),
                    'component' => array(1,2,4,5),
                    'widget' => array(1,2,3,4,5),
                    'config' => array(1),
                    'setting' => array(1,2,3,4,5),
                    'testimoni' => array(1,2,3,4,5)
                )
            ),
            2 => array(
                'id' => 2,
                'name' => 'Editor',
                'access' => array(
                    'dashboard' => array(1),
                    'category' => array(1,2,3,4,5),
                    'tag' => array(1,2,3,4),
                    'post' => array(1,2,3,4,5),
                    'pages' => array(1,2,3,4,5),
                    'comment' => array(1,3,4,5),
                    'subscribe' => array(1,4,5),
                    'gallery' => array(1,2,3,4,5),
                    'menu' => array(1,2,3),
                    'theme' => array(1,2,3),
                    'component' => array(1,2),
                    'widget' => array(1,2,3),
                    'setting' => array(1,3),
                    'testimoni' => array(1,2,3,4,5)
                )
            ),
            3 => array(
                'id' => 3,
                'name' => 'Writter',
                'access' => array(
                    'dashboard' => array(1),
                    'category' => array(1,2,3),
                    'tag' => array(1,2,3),
                    'post' => array(1,2,3),
                    'pages' => array(1,2,3),
                    'comment' => array(1,3),
                    'subscribe' => array(1),
                    'gallery' => array(1,2,3)
                )
            )
        );
        return ($id!=null && @$data[$id])?$data[$id]:$data;
    }

    function action($id=null) {
        $data = array(
            1 => 'View',
            2 => 'Add',
            3 => 'Edit',
            4 => 'Delete',
            5 => 'Approval'
        );
        return ($id!=null && @$data[$id])?$data[$id]:$data;
    }

    function access($controller,$action) {
        $f3 = Base::instance();
        $user_access = $f3->get('SESSION.access');
        if(@$user_access[$controller][$action]){
            return true;
        }else{
            return false;
        }
    }

    function templateEmail($body=''){
        $html = '<html>
                    <head></head>
                    <body>
                        <div style="width: 80%;margin:20px auto;">
                            <div style="width: 25%;">
                                <img src="'.Rimba::instance()->setting('web_url').'/images/'.Rimba::instance()->setting('img_header').'" alt="Rimba Media" style="width: 100%;">
                            </div>
                            <div style="border: 1px solid #ddd;padding: 10px 15px; font-size: 12px;line-height: 1;">';
        $html .= $body;
                            
        $html .= '          </div>
                            <div style="font-size: 10px;line-height: 0.5;">
                                <p>© '.date('Y').' Rimba Media</p>
                                <p>'.$this->setLang('address').': '.Rimba::instance()->setting('address').', '.$this->setLang('email').': '.Rimba::instance()->setting('email').', '.$this->setLang('telephone').': '.Rimba::instance()->setting('telephone').'</p>
                            </div>
                        </div>
                    </body>
                </html>';
        return $html;
    }

}