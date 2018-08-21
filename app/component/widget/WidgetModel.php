<?php

class WidgetModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_widget');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/awidget';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_widget a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.widget_title like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.widget_id ASC, a.widget_sort ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.widget_id AS id,
                a.widget_component_id AS component_id,
                a.widget_position AS position,
                a.widget_title AS title,
                a.widget_text AS wtext,
                a.widget_sort AS wsort,
                a.widget_active AS active,
                b.component AS component
                FROM " .$table_name. " 
                LEFT JOIN ".$this->dbname.".cms_component b ON b.component_id=a.widget_component_id
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
                if(Selo::instance()->access('widget',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('widget',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('widget',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('widget',4)==true){
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
        $table_name = $this->dbname. ".cms_widget a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.widget_id AS id,
            a.widget_component_id AS component_id,
            a.widget_position AS position,
            a.widget_title AS title,
            a.widget_text AS wtext,
            a.widget_sort AS wsort,
            a.widget_active AS active,
            b.component AS component 
            FROM " .$table_name. " 
            LEFT JOIN ".$this->dbname.".cms_component b ON b.component_id=a.widget_component_id
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_widget
                    SET
                        widget_active = '" .$params['widget_active']. "'
                    WHERE
                        widget_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_widget
                    SET
                        widget_component_id = '" .$params['widget_component_id']. "',
                        widget_position = '" .$params['widget_position']. "',
                        widget_title = '" .strip_tags($params['widget_title']). "',
                        widget_text = '" .strip_tags($params['widget_text']). "',
                        widget_sort = '" .$params['widget_sort']. "',
                        widget_active = '" .$params['widget_active']. "'
                    WHERE
                        widget_id = " .$id;
                if(@$params['widget_title'] && $params['widget_title']!=''){
                    $result = $this->db->exec($SQL);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_widget
                    (widget_component_id,widget_position,widget_title,widget_text,widget_sort,widget_active)
                    VALUES
                    ('" .$params['widget_component_id']. "','" .$params['widget_position']. "','" .strip_tags($params['widget_title']). "','" .strip_tags($params['widget_text']). "','" .$params['widget_sort']. "','" .$params['widget_active']. "')
                    ";
                if(@$params['widget_title'] && $params['widget_title']!=''){
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
            FROM " .$this->dbname. ".cms_widget 
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
        $table_name = $this->dbname. ".cms_widget a";
        $sWhere = "";
        $sOrder = " ORDER BY a.widget_id ASC, a.widget_sort ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.widget_id AS id,
                a.widget_component_id AS component_id,
                a.widget_position AS position,
                a.widget_title AS title,
                a.widget_text AS wtext,
                a.widget_sort AS wsort,
                a.widget_active AS active 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

}