<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class TestimoniModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_testimoni');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/atestimoni';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_testimoni a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.testimoni_name like '%".$params['s_name']."%' OR a.testimoni_name like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.testimoni_id DESC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.testimoni_id AS id,
                a.testimoni_name AS name,
                a.testimoni_text AS ttext,
                a.testimoni_job AS job,
                a.testimoni_picture AS picture,
                a.testimoni_active AS active,
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
                if(Selo::instance()->access('testimoni',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('testimoni',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('testimoni',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('testimoni',4)==true){
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
        $table_name = $this->dbname. ".cms_testimoni a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.testimoni_id AS id,
            a.testimoni_name AS name,
            a.testimoni_text AS ttext,
            a.testimoni_job AS job,
            a.testimoni_picture AS picture,
            a.testimoni_active AS active,
            a.update_user,
            a.update_timestamp 
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_testimoni
                    SET
                        testimoni_active = '" .$params['testimoni_active']. "'
                    WHERE
                        testimoni_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_testimoni
                    SET
                        testimoni_name = '" .strip_tags($params['testimoni_name']). "',
                        testimoni_text = '" .strip_tags($params['testimoni_text']). "',
                        testimoni_job = '" .strip_tags($params['testimoni_job']). "',
                        testimoni_picture = '" .strip_tags($params['testimoni_picture']). "',
                        testimoni_active = '" .strip_tags($params['testimoni_active']). "',
                        update_user = '" .Selo::instance()->getUser('user_name'). "',
                        update_timestamp = NOW()
                    WHERE
                        testimoni_id = " .$id;
                if(@$params['testimoni_name'] && $params['testimoni_name']!=''){
                    $result = $this->db->exec($SQL);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_testimoni
                    (testimoni_name,testimoni_text,testimoni_job,testimoni_picture,testimoni_active,update_user,update_timestamp)
                    VALUES
                    ('" .strip_tags($params['testimoni_name']). "','" .strip_tags($params['testimoni_text']). "','" .strip_tags($params['testimoni_job']). "','" .strip_tags($params['testimoni_picture']). "','" .strip_tags($params['testimoni_active']). "','" .Selo::instance()->getUser('user_name'). "',NOW())
                    ";
                if(@$params['testimoni_name'] && $params['testimoni_name']!=''){
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
            FROM " .$this->dbname. ".cms_testimoni 
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
        $table_name = $this->dbname. ".cms_testimoni a";
        $sWhere = "";
        $sOrder = " ORDER BY a.testimoni_id DESC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.testimoni_id AS id,
                a.testimoni_name AS name,
                a.testimoni_text AS ttext,
                a.testimoni_job AS job,
                a.testimoni_picture AS picture,
                a.testimoni_active AS active,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function countAllData() {
        $SQL = "SELECT count(a.testimoni_id) AS jml FROM " .$this->dbname. ".cms_testimoni a";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

    public function getTestimoni($params=null) {
        $table_name = $this->dbname. ".cms_testimoni a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.testimoni_id AS id,
                a.testimoni_name AS name,
                a.testimoni_text AS ttext,
                a.testimoni_job AS job,
                a.testimoni_picture AS picture,
                a.testimoni_active AS active,
                a.update_user,
                a.update_timestamp ";
        }
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.testimoni_id DESC";
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