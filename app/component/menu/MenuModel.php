<?php

// Autor : Hesti Wahyu Nugroho
// Web : https://rimbamedia.com

class MenuModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_menu_group');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/amenu';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_menu_group a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.menu_group_title like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.menu_group_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.menu_group_id AS id,
                a.menu_group_title AS title
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
                if(Selo::instance()->access('menu',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadPage($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('menu',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('menu',4)==true){
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

    public function getDataJsonMenu($params) {
        $table_name = $this->dbname. ".cms_menu a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.menu_title like '%".$params['s_name']."%') ";
        }
        if(@$params['s_id'] && $params['s_id'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " a.menu_group_id=".$params['s_id']." ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.menu_parent_id ASC, a.menu_position ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.menu_id AS id,
                a.menu_parent_id AS parent_id,
                b.menu_title AS parent,
                a.menu_title AS title,
                a.menu_url AS url,
                a.menu_class AS mclass,
                a.menu_position AS position,
                a.menu_group_id AS group_id,
                a.menu_active AS active,
                a.menu_target AS target
                FROM " .$table_name. " 
                LEFT JOIN ".$this->dbname.".cms_menu b ON b.menu_id=a.menu_parent_id
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
                if(Selo::instance()->access('menu',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewDataMenu/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('menu',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadPage($(this).attr(\'url\'));" url="' .$this->url. '/setActiveMenu/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('menu',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAddMenu/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('menu',4)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-danger no-label" href="#" onclick="LoadModalDel($(this).attr(\'url\'));" url="' .$this->url. '/delDataMenu/' .$r['id']. '">
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
        $table_name = $this->dbname. ".cms_menu_group a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.menu_group_id AS id,
            a.menu_group_title AS title
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function getByIdMenu($params) {
        $table_name = $this->dbname. ".cms_menu a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.menu_id AS id,
            a.menu_parent_id AS parent_id,
            b.menu_title AS parent,
            a.menu_title AS title,
            a.menu_url AS url,
            a.menu_class AS mclass,
            a.menu_position AS position,
            a.menu_group_id AS group_id,
            a.menu_active AS active,
            a.menu_target AS target
            FROM " .$table_name. " 
            LEFT JOIN ".$this->dbname.".cms_menu b ON b.menu_id=a.menu_parent_id
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        if($id!=0){
            $SQL = "UPDATE
                    " .$this->dbname. ".cms_menu_group
                    SET
                        menu_group_title = '" .strip_tags($params['menu_group_title']). "'
                    WHERE
                        menu_group_id = " .$id;
                $result = $this->db->exec($SQL);
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_menu_group
                    (menu_group_title)
                    VALUES
                    ('" .strip_tags($params['menu_group_title']). "')
                    ";
                $result = $this->db->exec($SQL);
        }
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

    public function saveDataMenu($id,$params) {
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $params2['a.menu_id'] = $id;
                $data2 = $this->getByIdMenu($params2);
                if(@$data2['group_id'] && $data2['group_id']!=null){
                    $params['menu_group_id'] = $data2['group_id'];
                }
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_menu
                    SET
                        menu_active = '" .$params['menu_active']. "'
                    WHERE
                        menu_id = " .$id;
                $result = $this->db->exec($SQL);
                echo '<script type="text/javascript">
                    LoadPage("'.$this->url.'/viewData/'.$params['menu_group_id'].'");
                </script>';exit();
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_menu
                    SET
                        menu_parent_id = " .$params['menu_parent_id']. ",
                        menu_title = '" .strip_tags($params['menu_title']). "',
                        menu_url = '" .$params['menu_url']. "',
                        menu_class = '" .strip_tags($params['menu_class']). "',
                        menu_position = " .$params['menu_position']. ",
                        menu_group_id = " .$params['menu_group_id']. ",
                        menu_active = '" .$params['menu_active']. "',
                        menu_target = '" .$params['menu_target']. "'
                    WHERE
                        menu_id = " .$id;
                $result = $this->db->exec($SQL);
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_menu
                    (menu_parent_id,menu_title,menu_url,menu_class,menu_position,menu_group_id,menu_active,menu_target)
                    VALUES
                    ('" .$params['menu_parent_id']. "','" .strip_tags($params['menu_title']). "','" .$params['menu_url']. "','" .strip_tags($params['menu_class']). "','" .$params['menu_position']. "','" .$params['menu_group_id']. "','" .$params['menu_active']. "','" .$params['menu_target']. "')
                    ";
                $result = $this->db->exec($SQL);
        }
        if($result){
            $data = array(
                '_pesan'    => 'Data berhasil disimpan.',
                '_redirect' => true,
                '_page'     => $this->url.'/viewData/'.$params['menu_group_id']
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
            FROM " .$this->dbname. ".cms_menu_group 
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

    public function delDataMenu($params) {
        $data = $this->getByIdMenu($params);
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $result = $this->db->exec(
            "DELETE
            FROM " .$this->dbname. ".cms_menu a
            " . $sWhere
        );
        if($result){
            $id = (@$data['id'])?$data['id']:1;
            $data = array(
                '_pesan'    => 'Data berhasil dihapus.',
                '_redirect' => true,
                '_page'     => $this->url.'/viewData/'.$id
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
        $table_name = $this->dbname. ".cms_menu_group a";
        $sWhere = "";
        $sOrder = " ORDER BY a.menu_group_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.menu_group_id AS id,
                a.menu_group_title AS title
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function getDataAllMenu() {
        $table_name = $this->dbname. ".cms_menu a";
        $sWhere = " WHERE a.menu_group_id=".$this->f3->get('SESSION.menu');
        $sOrder = " ORDER BY a.menu_parent_id ASC, a.menu_position ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.menu_id AS id,
                a.menu_parent_id AS parent_id,
                a.menu_title AS title,
                a.menu_url AS url,
                a.menu_class AS mclass,
                a.menu_position AS position,
                a.menu_group_id AS group_id,
                a.menu_active AS active,
                a.menu_target AS target
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        $data = array();
        if($result!=null){
            foreach($result as $r){
                $data[$r['parent_id']][$r['id']] = $r;
            }
        }
        return $data;
    }

}