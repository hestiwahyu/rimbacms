<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class CategoryModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_category');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/acategory';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_category a";
        $sWhere = " WHERE b.category_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.category_seotitle like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.category_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.category_id AS id,
                a.category_seotitle AS seotitle,
                a.category_picture AS picture,
                a.category_active AS active,
                b.category_title AS title,
                d.category_title AS parent,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_category_text b ON b.category_id=a.category_id
                LEFT JOIN " .$this->dbname. ".cms_category c ON c.category_id=a.category_parent_id
                LEFT JOIN " .$this->dbname. ".cms_category_text d ON c.category_id=d.category_id
                AND d.category_lang_code='" .$this->f3->get('LANG'). "'
                " . $sWhere;
        $SQL .= $sOrder . $sLimit;
        $result = $this->db->exec($SQL);
        
        $data['data'] = array();
        if($result!=null){
            $i  = 0;
            $no = (@$params['iDisplayStart']) ? $params['iDisplayStart'] + 1 : 1;
            foreach($result as $r){
                foreach($r as $k => $v){
                    switch ($k) {
                        case 'update_timestamp':
                            $data['data'][$i][$k] = Selo::instance()->dateFormat($v);
                            break;
                        default:
                            $data['data'][$i][$k] = $v;
                            break;
                    }
                }
                $data['data'][$i]['no'] = $no;
                $data['data'][$i]['pilihan'] = '<div class="btn-group">';
                if(Selo::instance()->access('category',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('category',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('category',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('category',4)==true){
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
        $table_name = $this->dbname. ".cms_category a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.category_id AS id,
            a.category_parent_id AS parent_id,
            a.category_seotitle AS seotitle,
            a.category_picture AS picture,
            a.category_active AS active,
            GROUP_CONCAT(CONCAT(b.category_lang_code,'::',b.category_title) SEPARATOR '|') AS title,
            a.update_user,
            a.update_timestamp 
            FROM " .$table_name. " 
            LEFT JOIN " .$this->dbname. ".cms_category_text b ON b.category_id=a.category_id 
            " .$sWhere. "
            GROUP BY a.category_id"
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        $category_lang_code = $params['category_lang_code'];
        $category_title = $params['category_title'];
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_category
                    SET
                        category_active = '" .$params['category_active']. "'
                    WHERE
                        category_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_category
                    SET
                        category_parent_id = '" .$params['category_parent_id']. "',
                        category_seotitle = '" .strip_tags($params['category_seotitle']). "',
                        category_picture = '" .$params['category_picture']. "',
                        category_active = '" .strip_tags($params['category_active']). "',
                        update_user = '" .Selo::instance()->getUser('user_name'). "',
                        update_timestamp = NOW()
                    WHERE
                        category_id = " .$id;
                if(@$params['category_seotitle'] && $params['category_seotitle']!=''){
                    $result = $this->db->exec($SQL);
                    $result = $this->db->exec("DELETE FROM " .$this->dbname. ".cms_category_text WHERE category_id=" .$id);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_category
                    (category_parent_id,category_seotitle,category_picture,category_active,update_user,update_timestamp)
                    VALUES
                    ('" .$params['category_parent_id']. "','" .strip_tags($params['category_seotitle']). "','" .$params['category_picture']. "','" .strip_tags($params['category_active']). "','" .Selo::instance()->getUser('user_name'). "',NOW())
                    ";
                if(@$params['category_seotitle'] && $params['category_seotitle']!=''){
                    $result = $this->db->exec($SQL);
                    $id = $this->db->lastInsertId();
                }
        }
        if($category_lang_code!=null){
            foreach($category_lang_code as $lang){
                $SQL = "INSERT INTO
                        " .$this->dbname. ".cms_category_text
                        (category_id,category_lang_code,category_title)
                        VALUES
                        ('" .$id. "','" .$lang. "','" .strip_tags($category_title[$lang]). "')
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
            FROM " .$this->dbname. ".cms_category 
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

    public function getDataAll() {
        $table_name = $this->dbname. ".cms_category a";
        $sWhere = " WHERE a.category_active='Y' AND b.category_lang_code='" .$this->f3->get('LANG'). "'";
        $sOrder = " ORDER BY a.category_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.category_id AS id,
                a.category_seotitle AS seotitle,
                a.category_picture AS picture,
                a.category_active AS active,
                b.category_title AS title,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_category_text b ON b.category_id=a.category_id 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function countAllData() {
        $SQL = "SELECT count(a.category_id) AS jml FROM " .$this->dbname. ".cms_category a WHERE a.category_active='Y'";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}