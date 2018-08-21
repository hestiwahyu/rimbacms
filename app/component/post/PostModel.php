<?php

class PostModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_post');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/apost';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_post a";
        $sWhere = " WHERE b.post_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.post_seotitle like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.post_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.post_id AS id,
                a.post_seotitle AS seotitle,
                a.post_tag AS tag,
                a.post_time AS ptime,
                a.post_date AS pdate,
                a.post_publishdate AS publishdate,
                a.post_editor AS editor,
                a.post_headline AS headline,
                a.post_comment AS comment,
                a.post_picture AS picture,
                a.post_picture_desc AS picture_desc,
                a.post_active AS active,
                a.post_hits AS hits,
                b.post_title AS title,
                b.post_content AS content,
                a.update_user,
                a.update_timestamp,
                COUNT(c.comment_id) AS ccomment 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_post_text b ON b.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_comment c ON c.comment_post_id=a.post_id
                " . $sWhere . " GROUP BY a.post_id";
        $SQL .= $sOrder . $sLimit;
        $result = $this->db->exec($SQL);
        
        $data['data'] = array();
        if($result!=null){
            $i  = 0;
            $no = (@$params['iDisplayStart']) ? $params['iDisplayStart'] + 1 : 1;
            foreach($result as $r){
                foreach($r as $k => $v){
                    switch ($k) {
                        case 'publishdate':
                            $data['data'][$i][$k] = Selo::instance()->dateFormat($v);
                            break;
                        default:
                            $data['data'][$i][$k] = $v;
                            break;
                    }
                }
                $data['data'][$i]['no'] = $no;
                $data['data'][$i]['pilihan'] = '<div class="btn-group">';
                if(Selo::instance()->access('post',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'),\'modal-lg\');" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('post',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('post',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadPage($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('post',4)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-danger no-label" href="#" onclick="LoadModalDel($(this).attr(\'url\'));" url="' .$this->url. '/delData/' .$r['id']. '">
                        <i class="fa fa-remove"></i>
                    </a>';
                }
                if(Selo::instance()->access('post',6)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formEmail/' .$r['id']. '">
                        <i class="fa fa-envelope"></i>
                    </a>';
                }
                $data['data'][$i]['pilihan'] .= '</div>';
                $i++;
                $no++;
            }
        }
        $SQL2 = "SELECT FOUND_ROWS() AS total";
        $data['iTotalRecords'] = 
        $data['iTotalDisplayRecords'] = $this->db->exec($SQL2)[0]['total'];
        return $data;
    }

    public function getById($params) {
        $table_name = $this->dbname. ".cms_post a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $SQL = "SELECT
            a.post_id AS id,
            a.post_seotitle AS seotitle,
            a.post_tag AS tag,
            a.post_time AS ptime,
            a.post_date AS pdate,
            a.post_publishdate AS publishdate,
            a.post_editor AS editor,
            a.post_headline AS headline,
            a.post_comment AS comment,
            a.post_picture AS picture,
            a.post_picture_desc AS picture_desc,
            a.post_active AS active,
            a.post_hits AS hits,
            -- GROUP_CONCAT(CONCAT(b.post_lang_code,'::',b.post_title) SEPARATOR '|') AS title,
            -- GROUP_CONCAT(CONCAT(b.post_lang_code,'::',b.post_content) SEPARATOR '|') AS content,
            a.category,
            a.update_user,
            a.update_timestamp,
            COUNT(c.comment_id) AS ccomment 
            FROM (
                SELECT 
                    a.*,
                    e.*,
                    GROUP_CONCAT(CONCAT(d.category_id,'::',d.category_seotitle,'::',e.category_title) SEPARATOR '|') AS category
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_post_category c ON c.post_id=a.post_id 
                LEFT JOIN " .$this->dbname. ".cms_category d ON d.category_id=c.category_id 
                LEFT JOIN " .$this->dbname. ".cms_category_text e ON e.category_id=d.category_id
                AND e.category_lang_code='" .$this->f3->get('LANG'). "' 
                " .$sWhere. "
                GROUP BY a.post_id
            ) a
            -- LEFT JOIN " .$this->dbname. ".cms_post_text b ON b.post_id=a.post_id 
            LEFT JOIN " .$this->dbname. ".cms_comment c ON c.comment_post_id=a.post_id
            GROUP BY a.post_id
            ";
        $return = $this->db->exec($SQL);
        $SQL2 = "SELECT b.* 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_post_text b ON b.post_id=a.post_id 
                " .$sWhere;
        $text = $this->db->exec($SQL2);
        $title = array();
        $content = array();
        if($text!=null){
            foreach($text as $r){
                $title[] = $r['post_lang_code'].'::'.$r['post_title'];
                $content[] = $r['post_lang_code'].'::'.$r['post_content'];
            }
        }
        $return[0]['title'] = implode('|',$title);
        $return[0]['content'] = implode('|',$content);
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        $post_lang_code = $params['post_lang_code'];
        $post_title = $params['post_title'];
        $post_content = $params['post_content'];
        $post_category = $params['post_category'];
        $ptag = str_replace('[','',str_replace(']','',str_replace('"','',$params['post_tag'])));
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_post
                    SET
                        post_active = '" .$params['post_active']. "'
                    WHERE
                        post_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_post
                    SET
                        post_seotitle = '" .strip_tags($params['post_seotitle']). "',
                        post_tag = '" .strip_tags($ptag). "',
                        post_time = '" .$params['post_time']. "',
                        post_date = '" .$params['post_date']. "',
                        post_publishdate = '" .$params['post_date']. " " .$params['post_time']. "',
                        post_headline = '" .$params['post_headline']. "',
                        post_comment = '" .$params['post_comment']. "',
                        post_picture = '" .$params['post_picture']. "',
                        post_picture_desc = '" .$params['post_picture_desc']. "',
                        post_active = '" .$params['post_active']. "',
                        update_user = '" .Selo::instance()->getUser('user_name'). "',
                        update_timestamp = NOW()
                    WHERE
                        post_id = " .$id;
                if($params['post_seotitle']!=null){
                    $result = $this->db->exec($SQL);
                    $result = $this->db->exec("DELETE FROM " .$this->dbname. ".cms_post_text WHERE post_id=" .$id);
                    $result = $this->db->exec("DELETE FROM " .$this->dbname. ".cms_post_category WHERE post_id=" .$id);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_post
                    (post_seotitle,post_tag,post_time,post_date,post_publishdate,post_headline,post_comment,post_picture,post_picture_desc,post_active,update_user,update_timestamp)
                    VALUES
                    ('" .strip_tags($params['post_seotitle']). "','" .strip_tags($ptag). "','" .$params['post_time']. "','" .$params['post_date']. "','" .$params['post_date']. " " .$params['post_time']. "','" .$params['post_headline']. "','" .$params['post_comment']. "','" .$params['post_picture']. "','" .$params['post_picture_desc']. "','" .$params['post_active']. "','" .Selo::instance()->getUser('user_name'). "',NOW())
                    ";
                if($params['post_seotitle']!=null){
                    $result = $this->db->exec($SQL);
                    $id = $this->db->lastInsertId();
                }
        }
        if($post_lang_code!=null){
            foreach($post_lang_code as $lang){
                $SQL = "INSERT INTO
                        " .$this->dbname. ".cms_post_text
                        (post_id,post_lang_code,post_title,post_content)
                        VALUES
                        ('" .$id. "','" .$lang. "','" .$post_title[$lang]. "','" .$post_content[$lang]. "')
                        ";
                    $result = $this->db->exec($SQL);
            }
        }
        if($post_category!=null){
            foreach($post_category as $cat){
                $SQL = "INSERT INTO
                        " .$this->dbname. ".cms_post_category
                        (post_id,category_id)
                        VALUES
                        ('" .$id. "','" .$cat. "')
                        ";
                    $result = $this->db->exec($SQL);
            }
        }
        if($id!=0){
            $_tag2 = (@$params['post_tag2'])?explode(',',$params['post_tag2']):null;
            if($_tag2!=null){
                foreach($_tag2 as $r2) {
                    $ptag = str_replace($r2,'',$ptag);
                }
            }
        }
        $_tag = (@$params['post_tag'])?explode(',',$ptag):null;
        if($_tag != null){
            foreach($_tag as $tag){
                if($tag!=null&&$tag!=''){
                    $SQL = "SELECT
                            *
                            FROM " .$this->dbname. ".cms_tag a
                            WHERE a.tag_seotitle='" .strtolower(str_replace(' ','-',trim($tag))). "'";
                    $result = $this->db->exec($SQL);
                    if(@$result && $result!=null){
                        $tag_id = $result[0]['tag_id'];
                        $SQL = "UPDATE
                                " .$this->dbname. ".cms_tag
                                SET tag_count=(tag_count+1)
                                WHERE
                                tag_id=" .$tag_id;
                            $result = $this->db->exec($SQL);
                    }else{
                        $SQL = "INSERT INTO
                                " .$this->dbname. ".cms_tag
                                (tag_title,tag_seotitle,tag_count,update_user,update_timestamp)
                                VALUES
                                ('" .trim($tag). "','" .strtolower(str_replace(' ','-',trim($tag))). "',1,'" .Selo::instance()->getUser('user_name'). "',NOW())
                                ";
                            $result = $this->db->exec($SQL);
                    }
                }
            }
        }
        if(@$result){
            $data = array(
                '_pesan'    => 'Data berhasil disimpan.',
                '_redirect' => true,
                '_page'     => $this->url
            );
        }else{
            $data = array(
                '_pesan'    => 'Gagal menyimpan data!',
                '_redirect' => false
            );
        }
        return $data;
    }

    public function delData($params) {
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $result = $this->db->exec(
            "DELETE
            FROM " .$this->dbname. ".cms_post 
            " . $sWhere
        );
        if($result){
            $data = array(
                '_pesan'    => 'Data berhasil dihapus.',
                '_redirect' => true,
                '_page'     => $this->url
            );
        }else{
            $data = array(
                '_pesan'    => 'Gagal menghapus data!',
                '_redirect' => false
            );
        }
        return $data;
    }

    public function countAllData() {
        $SQL = "SELECT count(a.post_id) AS jml FROM " .$this->dbname. ".cms_post a WHERE a.post_active='Y'";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}