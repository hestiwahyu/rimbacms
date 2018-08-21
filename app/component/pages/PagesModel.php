<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class PagesModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_pages');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/apages';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_pages a";
        $sWhere = " WHERE b.pages_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.pages_seotitle like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.pages_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.pages_id AS id,
                a.pages_seotitle AS seotitle,
                a.pages_picture AS picture,
                a.pages_active AS active,
                b.pages_title AS title,
                b.pages_content AS content,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_pages_text b ON b.pages_id=a.pages_id
                " . $sWhere;
        $SQL .= $sOrder . $sLimit;
        $result = $this->db->exec($SQL);
        
        $data['data'] = array();
        if($result!=null){
            $i  = 0;
            $no = (@$params['iDisplayStart']) ? $params['iDisplayStart'] + 1 : 1;
            foreach($result as $r){
                foreach($r as $k => $v){
                    $data['data'][$i][$k] = $v;
                }
                $data['data'][$i]['no'] = $no;
                $data['data'][$i]['pilihan'] = '<div class="btn-group">';
                if(Selo::instance()->access('pages',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'),\'modal-lg\');" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('pages',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('pages',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadPage($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('pages',4)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-danger no-label" href="#" onclick="LoadModalDel($(this).attr(\'url\'));" url="' .$this->url. '/delData/' .$r['id']. '">
                        <i class="fa fa-remove"></i>
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
        $table_name = $this->dbname. ".cms_pages a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.pages_id AS id,
            a.pages_seotitle AS seotitle,
            a.pages_picture AS picture,
            a.pages_active AS active,
            -- GROUP_CONCAT(CONCAT(b.pages_lang_code,'::',b.pages_title) SEPARATOR '|') AS title,
            -- GROUP_CONCAT(CONCAT(b.pages_lang_code,'::',b.pages_content) SEPARATOR '|') AS content,
            a.update_user,
            a.update_timestamp 
            FROM " .$table_name. " 
            -- LEFT JOIN " .$this->dbname. ".cms_pages_text b ON b.pages_id=a.pages_id 
            " .$sWhere. "
            -- GROUP BY a.pages_id"
        );
        $SQL2 = "SELECT b.* 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_pages_text b ON b.pages_id=a.pages_id 
                " .$sWhere;
        $text = $this->db->exec($SQL2);
        $title = array();
        $content = array();
        if($text!=null){
            foreach($text as $r){
                $title[] = $r['pages_lang_code'].'::'.$r['pages_title'];
                $content[] = $r['pages_lang_code'].'::'.$r['pages_content'];
            }
        }
        $return[0]['title'] = implode('|',$title);
        $return[0]['content'] = implode('|',$content);
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        $pages_lang_code = $params['pages_lang_code'];
        $pages_title = $params['pages_title'];
        $pages_content = $params['pages_content'];
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_pages
                    SET
                        pages_active = '" .$params['pages_active']. "'
                    WHERE
                        pages_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_pages
                    SET
                        pages_seotitle = '" .strip_tags($params['pages_seotitle']). "',
                        pages_picture = '" .$params['pages_picture']. "',
                        pages_active = '" .$params['pages_active']. "',
                        update_user = '" .Selo::instance()->getUser('user_name'). "',
                        update_timestamp = NOW()
                    WHERE
                        pages_id = " .$id;
                if($params['pages_seotitle']!=null){
                    $result = $this->db->exec($SQL);
                    $result = $this->db->exec("DELETE FROM " .$this->dbname. ".cms_pages_text WHERE pages_id=" .$id);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_pages
                    (pages_seotitle,pages_picture,pages_active,update_user,update_timestamp)
                    VALUES
                    ('" .strip_tags($params['pages_seotitle']). "','" .$params['pages_picture']. "','" .$params['pages_active']. "','" .Selo::instance()->getUser('user_name'). "',NOW())
                    ";
                if($params['pages_seotitle']!=null){
                    $result = $this->db->exec($SQL);
                    $id = $this->db->lastInsertId();
                }
        }
        if($pages_lang_code!=null){
            foreach($pages_lang_code as $lang){
                $SQL = "INSERT INTO
                        " .$this->dbname. ".cms_pages_text
                        (pages_id,pages_lang_code,pages_title,pages_content)
                        VALUES
                        ('" .$id. "','" .$lang. "','" .$pages_title[$lang]. "','" .$pages_content[$lang]. "')
                        ";
                    $result = $this->db->exec($SQL);
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
            FROM " .$this->dbname. ".cms_pages 
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
        $SQL = "SELECT count(a.pages_id) AS jml FROM " .$this->dbname. ".cms_pages a WHERE a.pages_active='Y'";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}