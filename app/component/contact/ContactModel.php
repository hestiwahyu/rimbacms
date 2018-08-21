<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class ContactModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_contact');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/acontact';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_contact a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.contact_name like '%".$params['s_name']."%' OR a.contact_email like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.contact_id DESC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.contact_id AS id,
                a.contact_name AS name,
                a.contact_email AS email,
                a.contact_message AS message,
                a.update_user,
                a.update_timestamp 
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
                if(Selo::instance()->access('contact',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('contact',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('contact',4)==true){
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
        $table_name = $this->dbname. ".cms_contact a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.contact_id AS id,
            a.contact_name AS name,
            a.contact_email AS email,
            a.contact_message AS message,
            a.update_user,
            a.update_timestamp 
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        if($id!=0){
            $SQL = "UPDATE
                " .$this->dbname. ".cms_contact
                SET
                    contact_name = '" .strip_tags($params['contact_name']). "',
                    contact_email = '" .strip_tags($params['contact_email']). "',
                    contact_message = '" .strip_tags($params['contact_message']). "',
                    update_user = '" .Selo::instance()->getUser('user_name'). "',
                    update_timestamp = NOW()
                WHERE
                    contact_id = " .$id;
            if(@$params['contact_name'] && $params['contact_name']!=''){
                $result = $this->db->exec($SQL);
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_contact
                    (contact_name,contact_email,contact_message,update_user,update_timestamp)
                    VALUES
                    ('" .strip_tags($params['contact_name']). "','" .strip_tags($params['contact_email']). "','" .strip_tags($params['contact_message']). "','" .Selo::instance()->getUser('user_name'). "',NOW())
                    ";
                if(@$params['contact_name'] && $params['contact_name']!=''){
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
            FROM " .$this->dbname. ".cms_contact 
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
        $table_name = $this->dbname. ".cms_contact a";
        $sWhere = "";
        $sOrder = " ORDER BY a.contact_id DESC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.contact_id AS id,
                a.contact_name AS name,
                a.contact_message AS message,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function countAllData() {
        $SQL = "SELECT count(a.contact_id) AS jml FROM " .$this->dbname. ".cms_contact a";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

    public function getContact($params=null) {
        $table_name = $this->dbname. ".cms_contact a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.contact_id AS id,
                a.contact_name AS name,
                a.contact_message AS message,
                a.update_user,
                a.update_timestamp ";
        }
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.contact_id DESC";
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. "
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder . $sLimit;
        $result = $this->db->exec($SQL);
        return $result;
    }

}