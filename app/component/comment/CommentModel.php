<?php

class CommentModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_comment');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/acomment';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_comment a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.comment_name like '%".$params['s_name']."%' OR a.comment_email like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.comment_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.comment_id AS id,
                a.comment_parent_id AS parent_id,
                b.post_seotitle AS seotitle,
                c.post_title AS title,
                a.comment_name AS name, 
                a.comment_email AS email, 
                a.comment_url AS url, 
                a.comment_text AS ctext, 
                a.comment_date AS cdate, 
                a.comment_time AS ctime, 
                a.comment_active AS active, 
                a.comment_status AS status
                FROM " .$table_name. " 
                LEFT JOIN ".$this->dbname.".cms_post b ON b.post_id=a.comment_post_id
                LEFT JOIN ".$this->dbname.".cms_post_text c ON c.post_id=b.post_id AND c.post_lang_code='" .$this->f3->get('LANG'). "'
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
                        case 'name':
                            $data['data'][$i][$k] = $v. ' - ' .$r['email']. '<br>';
                            $data['data'][$i][$k] .= '<a href="'.$this->f3->hive()['BASE']. '/post/' .$r['seotitle']. '" target="blank">' .$r['title']. '</a>';
                            break;
                        case 'cdate':
                            $data['data'][$i][$k] = Selo::instance()->dateFormat($v. ' ' .$r['ctime'],'H:i d M Y');
                            break;
                        default:
                            $data['data'][$i][$k] = $v;
                            break;
                    }
                }
                $data['data'][$i]['no'] = $no;
                $data['data'][$i]['pilihan'] = '<div class="btn-group">';
                if(Selo::instance()->access('comment',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Publish"></i>':'<i class="fa fa-check" title="Unpublish"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('comment',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formReply/' .$r['id']. '">
                        <i class="fa fa-reply" title="Reply"></i>
                    </a>';
                }
                if(Selo::instance()->access('comment',4)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-danger no-label" href="#" onclick="LoadModalDel($(this).attr(\'url\'));" url="' .$this->url. '/delData/' .$r['id']. '">
                        <i class="fa fa-remove" title="Remove"></i>
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
        $table_name = $this->dbname. ".cms_comment a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.comment_id AS id,
            a.comment_parent_id AS parent_id,
            b.post_id AS post_id,
            b.post_seotitle AS seotitle,
            c.post_title AS title,
            a.comment_post_id AS post_id,
            a.comment_name AS name, 
            a.comment_email AS email, 
            a.comment_url AS url, 
            a.comment_text AS ctext, 
            a.comment_date AS cdate, 
            a.comment_time AS ctime, 
            a.comment_active AS active, 
            a.comment_status AS status 
            FROM " .$table_name. " 
            LEFT JOIN ".$this->dbname.".cms_post b ON b.post_id=a.comment_post_id
            LEFT JOIN ".$this->dbname.".cms_post_text c ON c.post_id=b.post_id AND c.post_lang_code='" .$this->f3->get('LANG'). "'
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        $user = $this->f3->get('SESSION.user');
        if(@$params['status'] && $params['status']==true){
            $SQL = "UPDATE
                    " .$this->dbname. ".cms_comment
                    SET
                        comment_active = '" .$params['comment_active']. "'
                    WHERE
                        comment_id = " .$id;
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_comment
                    (comment_parent_id,comment_post_id,comment_name,comment_email,comment_url,comment_text,comment_date,comment_time,comment_active,comment_status)
                    VALUES
                    ('" .$id. "','" .$params['comment_post_id']. "','" .$user['real_name']. "','" .$user['email']. "','" .$params['comment_url']. "','" .strip_tags($params['comment_text']). "','" .date('Y-m-d'). "','" .date('H:i:s'). "','Y','Y')
                    ";
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
            FROM " .$this->dbname. ".cms_comment 
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
        $table_name = $this->dbname. ".cms_comment a";
        $sWhere = "";
        $sOrder = " ORDER BY a.comment_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.comment_id AS id,
                a.comment_parent_id AS parent_id,
                a.comment_post_id AS post_id,
                a.comment_name AS name, 
                a.comment_email AS email, 
                a.comment_url AS url, 
                a.comment_text AS ctext, 
                a.comment_date AS cdate, 
                a.comment_time AS ctime, 
                a.comment_active AS active, 
                a.comment_status AS status 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function countAllData() {
        $SQL = "SELECT count(a.comment_id) AS jml FROM " .$this->dbname. ".cms_comment a WHERE a.comment_active='Y'";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}