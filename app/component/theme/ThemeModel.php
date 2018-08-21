<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class ThemeModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_theme');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/atheme';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_theme a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.theme_title like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.theme_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.theme_id AS id,
                a.theme_title AS title,
                a.theme_author AS author,
                a.theme_folder AS folder, 
                a.theme_active AS active 
                FROM " .$table_name. " 
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
                if(Selo::instance()->access('theme',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('theme',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('theme',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('theme',4)==true){
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
        $table_name = $this->dbname. ".cms_theme a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.theme_id AS id,
            a.theme_title AS title,
            a.theme_author AS author,
            a.theme_folder AS folder, 
            a.theme_active AS active 
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_theme
                    SET
                        theme_active = '" .$params['theme_active']. "'
                    WHERE
                        theme_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_theme
                    SET
                        theme_title = '" .strip_tags($params['theme_title']). "',
                        theme_author = '" .strip_tags($params['theme_author']). "',
                        theme_folder = '" .$params['theme_folder']. "',
                        theme_active = '" .$params['theme_active']. "'
                    WHERE
                        theme_id = " .$id;
                if(@$params['theme_title'] && $params['theme_title']!=''){
                    $result = $this->db->exec($SQL);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_theme
                    (theme_title,theme_author,theme_folder,theme_active)
                    VALUES
                    ('" .strip_tags($params['theme_title']). "','" .strip_tags($params['theme_author']). "','" .$params['theme_folder']. "','" .$params['theme_active']. "')
                    ";
                if(@$params['theme_title'] && $params['theme_title']!=''){
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
        $SQL = "DELETE
            FROM " .$this->dbname. ".cms_theme 
            " . $sWhere;
        $result = $this->db->exec($SQL);
        if(@$result){
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
        $table_name = $this->dbname. ".cms_theme a";
        $sWhere = "";
        $sOrder = " ORDER BY a.theme_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.theme_id AS id,
                a.theme_title AS title,
                a.theme_author AS author,
                a.theme_folder AS folder, 
                a.theme_active AS active 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

}