<?php

class GalleryModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_gallery');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/agallery';
    }

    public function getDataJson($params) {
        $table_name = $this->dbname. ".cms_gallery a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.gallery_seotitle like '%".$params['s_name']."%' OR a.gallery_title like '%".$params['s_name']."%') ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.gallery_id ASC";
        $sGroup = " GROUP BY a.gallery_id";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.gallery_id AS id,
                a.gallery_seotitle AS seotitle,
                a.gallery_title AS title,
                a.gallery_active AS active,
                a.gallery_hits AS hits,
                COUNT(b.images_id) AS picture,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. "
                LEFT JOIN ".$this->dbname.".cms_gallery_images b ON b.images_gallery_id=a.gallery_id 
                " . $sWhere . $sGroup;
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
                if(Selo::instance()->access('gallery',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadPage($(this).attr(\'url\'));" url="' .$this->url. '/viewData/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('gallery',5)==true){
                    $active = ($r['active']=='N')?'Y':'N';
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadAjaxRefresh($(this).attr(\'url\'));" url="' .$this->url. '/setActive/' .$r['id']. '/' .$active. '">';
                        $data['data'][$i]['pilihan'] .= ($r['active']=='N')? '<i class="fa fa-minus" title="Active"></i>':'<i class="fa fa-check" title="No Active"></i>';
                    $data['data'][$i]['pilihan'] .= '</a>';
                }
                if(Selo::instance()->access('gallery',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAdd/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('gallery',4)==true){
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

    public function getDataJsonImage($params) {
        $table_name = $this->dbname. ".cms_gallery_images a";
        $sWhere = "";
        if(@$params['s_name'] && $params['s_name'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " (a.images_title like '%".$params['s_name']."%' OR a.images_content like '%".$params['s_name']."%') ";
        }
        if(@$params['s_id'] && $params['s_id'] != ""){
            $sWhere .= ($sWhere != "")?" AND ":" WHERE ";
            $sWhere .= " a.images_gallery_id=".$params['s_id']." ";
        }
        $sLimit = "";
        if($params['iDisplayStart'] != '' && $params['iDisplayLength'] != ''){
            $sLimit = " LIMIT " . $params['iDisplayLength'] . " OFFSET " . $params['iDisplayStart'];
        }

        $sOrder = " ORDER BY a.images_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.images_id AS id,
                a.images_gallery_id AS gallery_id,
                a.images_title AS title,
                a.images_content AS content,
                a.images_picture AS picture
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
                if(Selo::instance()->access('gallery',1)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-info no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/viewDataImage/' .$r['id']. '">
                        <i class="fa fa-eye"></i>
                    </a>';
                }
                if(Selo::instance()->access('gallery',3)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-success no-label" href="#" onclick="LoadModalForm($(this).attr(\'url\'));" url="' .$this->url. '/formAddImage/' .$r['id']. '">
                        <i class="fa fa-pencil"></i>
                    </a>';
                }
                if(Selo::instance()->access('gallery',4)==true){
                    $data['data'][$i]['pilihan'] .= '<a class="tip text-danger no-label" href="#" onclick="LoadModalDel($(this).attr(\'url\'));" url="' .$this->url. '/delDataImage/' .$r['id']. '">
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
        $table_name = $this->dbname. ".cms_gallery a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.gallery_id AS id,
            a.gallery_seotitle AS seotitle,
            a.gallery_title AS title,
            a.gallery_active AS active,
            a.gallery_hits AS hits,
            a.update_user,
            a.update_timestamp 
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function getByIdImage($params) {
        $table_name = $this->dbname. ".cms_gallery_images a";
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $return = $this->db->exec(
            "SELECT
            a.images_id AS id,
            a.images_gallery_id AS gallery_id,
            a.images_title AS title,
            a.images_content AS content,
            a.images_picture AS picture 
            FROM " .$table_name. " 
            " .$sWhere
        );
        return (@$return[0])?$return[0]:null;
    }

    public function saveData($id,$params) {
        if($id!=0){
            if(@$params['status'] && $params['status']==true){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_gallery
                    SET
                        gallery_active = '" .$params['gallery_active']. "'
                    WHERE
                        gallery_id = " .$id;
                $result = $this->db->exec($SQL);
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_gallery
                    SET
                        gallery_seotitle = '" .strip_tags($params['gallery_seotitle']). "',
                        gallery_title = '" .strip_tags($params['gallery_title']). "',
                        gallery_active = '" .$params['gallery_active']. "',
                        update_user = '" .Selo::instance()->getUser('user_name'). "',
                        update_timestamp = NOW()
                    WHERE
                        gallery_id = " .$id;
                if(@$params['gallery_seotitle'] && $params['gallery_seotitle']!=''){
                    $result = $this->db->exec($SQL);
                }
            }
        }else{
            $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_gallery
                    (gallery_seotitle,gallery_title,gallery_active,update_user,update_timestamp)
                    VALUES
                    ('" .strip_tags($params['gallery_seotitle']). "','" .strip_tags($params['gallery_title']). "','" .$params['gallery_active']. "','" .Selo::instance()->getUser('user_name'). "',NOW())
                    ";
                if(@$params['gallery_seotitle'] && $params['gallery_seotitle']!=''){
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

    public function saveDataImage($id,$params) {
        if($id!=0){
            if(@$params['images_picture']){
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_gallery_images
                    SET
                        images_gallery_id = " .$params['images_gallery_id']. ",
                        images_title = '" .strip_tags($params['images_title']). "',
                        images_content = '" .$params['images_content']. "',
                        images_picture = '" .$params['images_picture']. "'
                    WHERE
                        images_id = " .$id;
            }else{
                $SQL = "UPDATE
                    " .$this->dbname. ".cms_gallery_images
                    SET
                        images_gallery_id = " .$params['images_gallery_id']. ",
                        images_title = '" .strip_tags($params['images_title']). "',
                        images_content = '" .$params['images_content']. "'
                    WHERE
                        images_id = " .$id;
            }
            if(@$params['images_title'] && $params['images_title']!=''){
                $result = $this->db->exec($SQL);
            }
        }else{
            if(@$params['images_picture']){
                $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_gallery_images
                    (images_gallery_id,images_title,images_content,images_picture)
                    VALUES
                    (" .$params['images_gallery_id']. ",'" .strip_tags($params['images_title']). "','" .$params['images_content']. "','" .$params['images_picture']. "')
                    ";
            }else{
                $SQL = "INSERT INTO
                    " .$this->dbname. ".cms_gallery_images
                    (images_gallery_id,images_title,images_content)
                    VALUES
                    (" .$params['images_gallery_id']. ",'" .strip_tags($params['images_title']). "','" .$params['images_content']. "')
                    ";
            }
            if(@$params['images_title'] && $params['images_title']!=''){
                $result = $this->db->exec($SQL);
            }
        }
        if(@$result){
            $data = array(
                '_pesan'    => 'Data berhasil disimpan.',
                '_redirect' => true,
                '_page'     => $this->url.'/viewData/'.$params['images_gallery_id']
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
            FROM " .$this->dbname. ".cms_gallery 
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

    public function delDataImage($params) {
        $data = $this->getByIdImage($params);
        $sWhere = "";
        if($params!=null){
            foreach($params as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE $k='$v'":" AND  $k='$v'";
            }
        }
        $result = $this->db->exec(
            "DELETE
            FROM " .$this->dbname. ".cms_gallery_images 
            " . $sWhere
        );
        if($result){
            $id = (@$data['gallery_id'])?$data['gallery_id']:1;
            $file1 = 'images/gallery/'.$data['picture'];
            $file2 = 'images/thumb/gallery/'.$data['picture'];
            if(file_exists($file1)){
                unlink($file1);
            }
            if(file_exists($file2)){
                unlink($file2);
            }
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
        $table_name = $this->dbname. ".cms_gallery a";
        $sWhere = "";
        $sOrder = " ORDER BY a.gallery_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.gallery_id AS id,
                a.gallery_seotitle AS seotitle,
                a.gallery_title AS title,
                a.gallery_active AS active,
                a.gallery_hits AS hits,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function getDataAllImage() {
        $table_name = $this->dbname. ".cms_gallery_images a";
        $sWhere = "";
        $sOrder = " ORDER BY a.images_id ASC";

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.images_id AS id,
                a.images_gallery_id AS gallery_id,
                a.images_title AS title,
                a.images_content AS content,
                a.images_picture AS picture 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function countAllData() {
        $SQL = "SELECT count(a.gallery_id) AS jml FROM " .$this->dbname. ".cms_gallery a WHERE a.gallery_active='Y'";
        $return = $this->db->exec($SQL);
        return (@$return[0]['jml'] && $return[0]['jml']!=null)?$return[0]['jml']:0;
    }

}