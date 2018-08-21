<?php

class SubscribeModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_subscribe');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/asubscribe';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_subscribe a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.subscribe_name like '%".$params['s_name']."%' OR a.subscribe_email like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.subscribe_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.subscribe_id AS id,
                a.subscribe_name AS title,
                a.subscribe_email AS email,
                a.subscribe_active AS active,
                a.subscribe_instansi AS instansi
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
                if(Selo::instance()->access('subscribe',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('subscribe',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Publish"></i>':'<i class="fa fa-check" title="Unpublish"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('subscribe',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('subscribe',4)==true){
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
        $table_name = $this->dbname. ".cms_subscribe a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.subscribe_id AS id,
            a.subscribe_name AS title,
            a.subscribe_email AS email,
            a.subscribe_active AS active,
            a.subscribe_instansi AS instansi 
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        $user = $this->f3->get('SESSION.user');
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                        " .$this->dbname. ".cms_subscribe
                        SET
                            subscribe_active = '" .$params['subscribe_active']. "'
                        WHERE
                            subscribe_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_subscribe
                    SET
                        subscribe_name = '" .strip_tags($params['subscribe_name']). "',
                        subscribe_email = '" .strip_tags($params['subscribe_email']). "',
                        subscribe_active = '" .strip_tags($params['subscribe_active']). "',
                        subscribe_instansi = '" .strip_tags($params['subscribe_instansi']). "'
                    WHERE
                        subscribe_id = " .$id;
                if(@$params['subscribe_name'] && $params['subscribe_name']!=''){
                    $result = $this->db->exec($SQL);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_subscribe
                    (subscribe_name,subscribe_email,subscribe_active,subscribe_instansi)
                    VALUES
                    ('" .strip_tags($params['subscribe_name']). "','" .strip_tags($params['subscribe_email']). "','" .strip_tags($params['subscribe_active']). "','" .strip_tags($params['subscribe_instansi']). "')
                    ";
            if(@$params['subscribe_name'] && $params['subscribe_name']!=''){
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
            FROM " .$this->dbname. ".cms_subscribe 
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
        $SQL = "SELECT count(a.subscribe_id) AS jml FROM " .$this->dbname. ".cms_subscribe a";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}