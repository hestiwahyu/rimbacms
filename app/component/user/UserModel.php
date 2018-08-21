<?php

class UserModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_user');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/auser';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_user a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.user_real_name like '%".$params['s_name']."%' 
                        OR a.user_user_name like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.user_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.user_id AS id,
                a.user_real_name AS real_name,
                a.user_user_name AS user_name,
                a.user_email AS email,
                a.user_password AS pass,
                a.user_desc AS udesc,
                a.user_no_password AS no_pass,
                a.user_active AS active,
                a.user_force_logout AS force_logout,
                a.user_active_lang_code AS lang_code,
                a.user_last_logged_in AS logged_in,
                a.user_last_ip AS last_ip,
                a.insert_user,
                a.insert_timestamp,
                a.update_user,
                a.update_timestamp,
                a.user_skin AS skin
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
                    switch ($k) {
                        case 'logged_in':
                            $data['data'][$i][$k] = Selo::instance()->dateFormat($v,'H:i d M Y');
                            break;
                        default:
                            $data['data'][$i][$k] = $v;
                            break;
                    }
                }
                $data['data'][$i]['no'] = $no;
                $data['data'][$i]['pilihan'] = '<div class="btn-group">';
                if(Selo::instance()->access('user',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('user',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('user',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('user',5)==true){
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
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $SQL = "SELECT
                a.user_id AS id,
                a.user_real_name AS real_name,
                a.user_user_name AS user_name,
                a.user_email AS email,
                a.user_password AS pass,
                a.user_desc AS udesc,
                a.user_no_password AS no_pass,
                a.user_active AS active,
                a.user_force_logout AS force_logout,
                a.user_active_lang_code AS lang_code,
                a.user_last_logged_in AS logged_in,
                a.user_last_ip AS last_ip,
                a.insert_user,
                a.insert_timestamp,
                a.update_user,
                a.update_timestamp,
                a.user_skin AS skin,
                GROUP_CONCAT(b.usergroup_group_id) AS ugroup
            FROM " .$this->dbname. ".cms_user a
            LEFT JOIN " .$this->dbname. ".cms_user_group b ON a.user_id=b.usergroup_user_id
            " . $sWhere . " GROUP BY a.user_id";
        $return = $this->db->exec($SQL);
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        $user_group = $params['user_group'];
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_user
                    SET
                        user_active = '" .$params['user_active']. "'
                    WHERE
                        user_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                if(@$params['user_password'] && $params['user_password']=$params['user_cpassword']){
                    $SQL = "UPDATE
                            " .$this->dbname. ".cms_user
                            SET
                                user_real_name = '" .strip_tags($params['user_real_name']). "',
                                user_user_name = '" .strip_tags($params['user_user_name']). "',
                                user_email = '" .$params['user_email']. "',
                                user_password = '" .md5($params['user_password']). "',
                                user_desc = '" .strip_tags($params['user_desc']). "',
                                user_active = '" .$params['user_active']. "',
                                user_active_lang_code = '" .$params['user_active_lang_code']. "',
                                user_skin = '" .$params['user_skin']. "',
                                update_user = '" .Selo::instance()->getUser('user_name'). "',
                                update_timestamp = NOW()
                            WHERE
                                user_id = " . $id;
                    $result = $this->db->exec($SQL);
                }else{
                    $SQL = "UPDATE
                            " .$this->dbname. ".cms_user
                            SET
                                user_real_name = '" .strip_tags($params['user_real_name']). "',
                                user_user_name = '" .strip_tags($params['user_user_name']). "',
                                user_email = '" .$params['user_email']. "',
                                user_desc = '" .strip_tags($params['user_desc']). "',
                                user_active = '" .$params['user_active']. "',
                                user_active_lang_code = '" .$params['user_active_lang_code']. "',
                                user_skin = '" .$params['user_skin']. "',
                                update_user = '" .Selo::instance()->getUser('user_name'). "',
                                update_timestamp = NOW()
                            WHERE
                                user_id = " . $id;
                    $result = $this->db->exec($SQL);
                }
            }

            if(@$params['user_group'] && $params['user_group']!=null){
                $result = $this->db->exec("DELETE FROM " .$this->dbname. ".cms_user_group WHERE usergroup_user_id=" .$id);
            }
        }else{
            if(@$params['user_password'] && $params['user_password']=$params['user_cpassword']){
                $SQL = "INSERT INTO
                        " .$this->dbname. ".cms_user
                        (user_real_name,user_user_name,user_email,user_password,user_desc,user_active,user_active_lang_code,user_skin,insert_user,insert_timestamp)
                        VALUES
                        ('" .$params['user_real_name']. "','" .$params['user_user_name']. "','" .$params['user_email']. "','" .md5($params['user_password']). "','" .$params['user_desc']. "','" .$params['user_active']. "','" .$params['user_active_lang_code']. "','" .$params['user_skin']. "','" .Selo::instance()->getUser('user_name'). "',NOW())
                        ";
                $result = $this->db->exec($SQL);
                $id = $this->db->lastInsertId();
            }else{
                $data = array(
                    '_pesan'    => 'Konfirmasi password tidak sama!',
                    '_redirect' => false
                );
            }
        }
        if($user_group!=null && @ $id){
            foreach($user_group as $group){
                $SQL = "INSERT INTO
                        " .$this->dbname. ".cms_user_group
                        (usergroup_user_id,usergroup_group_id)
                        VALUES
                        ('" .$id. "','" .$group. "')
                        ";
                    $result = $this->db->exec($SQL);
            }
        }
        if(@$result){
            if($result){
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
        }
        return $data;
    }

    public function savePassword($id,$params) {
        if($id!=0){
            if(@$params['user_password'] && $params['user_password']=$params['user_cpassword']){
                $SQL = "UPDATE
                        " .$this->dbname. ".cms_user
                        SET
                            user_password = '" .md5($params['user_password']). "'
                        WHERE
                            user_id = " . $id;
            }
        }
        $result = $this->db->exec($SQL);
        if($result){
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
            FROM " .$this->dbname. ".cms_user 
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
        $SQL = "SELECT count(a.user_id) AS jml FROM " .$this->dbname. ".cms_user a WHERE a.user_active='Y'";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}