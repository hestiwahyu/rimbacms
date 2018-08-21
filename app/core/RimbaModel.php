<?php

class RimbaModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;
    protected $seloLib;
    protected $visitorLib;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_theme');
        $f3 = Base::instance();
        $this->f3       = $f3;
        $this->dbname   = $this->f3->get('DEVDBNAME');
        $this->f3->set('LANG',$this->f3->get('SESSION.lang'));
        $seloLib = Selo::instance();
        $this->seloLib = $seloLib;
        $visitorLib = Visitor::instance();
        $this->visitorLib = $visitorLib;
    }

    public function getTheme() {
        $table_name = $this->dbname. ".cms_theme a";
        $sWhere = " WHERE a.theme_active='Y'";
        $return = $this->db->exec(
            "SELECT
            a.theme_folder AS theme
            FROM " .$table_name. "  
            " .$sWhere. "
            ORDER BY a.theme_id DESC"
        );
        return (@$return[0])?$return[0]['theme'].'/':'default/';
    }

    // category
    public function getCategory($params=null) {
        $table_name = $this->dbname. ".cms_category a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.category_id AS id,
                a.category_parent_id AS parent,
                a.category_seotitle AS seotitle,
                a.category_picture AS picture,
                a.category_active AS active,
                b.category_title AS title,
                a.update_user,
                a.update_timestamp ";
        }
        $sWhere = " WHERE b.category_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.category_id ASC";
        $sGroup = " GROUp BY a.category_id";
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. "
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_category_text b ON b.category_id=a.category_id
                LEFT JOIN " .$this->dbname. ".cms_post_category c ON c.category_id=a.category_id
                " . $sWhere . $sGroup;
        $SQL .= $sOrder . $sLimit;
        try {
            $result = $this->db->exec($SQL);
            return $result;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getCountCategory($params=null) {
        $table_name = $this->dbname. ".cms_category a";
        $select = "COUNT(a.category_id) As total";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $SQL = "SELECT
                " .$select. "
                FROM " .$table_name. " 
                " . $sWhere . $sGroup;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['total'])?$result[0]['total']:0;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return 0;
        }
    }

    // tag
    public function getTag($params=null) {
        $table_name = $this->dbname. ".cms_tag a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.tag_id AS id,
                a.tag_seotitle AS seotitle,
                a.tag_title AS title,
                a.tag_count AS count,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= $params['like'];
        }
        $sOrder = " ORDER BY a.tag_id ASC";
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder . $sLimit;
        try {
            $result = $this->db->exec($SQL);
            return $result;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getCountTag($params=null) {
        $table_name = $this->dbname. ".cms_tag a";
        $select = "COUNT(a.tag_id) As total";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                " . $sWhere;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['total'])?$result[0]['total']:0;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return 0;
        }
    }

    // post
    public function getListPostBy($params=null) {
        $table_name = $this->dbname. ".cms_post a";
        if(@$params['select']&&$params['select']!=null){
            $select = 'SQL_CALC_FOUND_ROWS ' .$params['select'];
        }else{
            $select = "SQL_CALC_FOUND_ROWS 
                a.post_id AS id,
                a.post_seotitle AS seotitle,
                a.post_tag AS tag,
                a.post_time AS ptime,
                a.post_date AS pdate,
                a.post_publishdate AS publishdate,
                a.post_editor AS editor,
                a.post_headline AS headline,
                a.post_comment AS comment,
                a.post_picture AS picture,
                a.post_picture_desc AS picture_desc,
                a.post_active AS active,
                a.post_hits AS hits,
                b.post_title AS title,
                b.post_content AS content,
                GROUP_CONCAT(CONCAT(d.category_id,',',e.category_title,',',d.category_seotitle) SEPARATOR'|') AS category,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = " WHERE b.post_lang_code='" .$this->f3->get('LANG'). "' AND e.category_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $sOrder = " ORDER BY ";
        if(@$params['order']&&$params['order']!=null){
            $sOrder .= $params['order'];
        }else{
            $sOrder .= " a.post_id DESC";
        }
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_post_text b ON b.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_post_category c ON c.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_category d ON d.category_id=c.category_id
                LEFT JOIN " .$this->dbname. ".cms_category_text e ON d.category_id=e.category_id
                " . $sWhere . " GROUP BY a.post_id ";
        $SQL .= $sOrder . $sLimit;
        try {
            $result = $this->db->exec($SQL);
            return $result;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getPostBy($params=null) {
        $table_name = $this->dbname. ".cms_post a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.post_id AS id,
                a.post_seotitle AS seotitle,
                a.post_tag AS tag,
                a.post_time AS ptime,
                a.post_date AS pdate,
                a.post_publishdate AS publishdate,
                a.post_editor AS editor,
                a.post_headline AS headline,
                a.post_comment AS comment,
                a.post_picture AS picture,
                a.post_picture_desc AS picture_desc,
                a.post_active AS active,
                a.post_hits AS hits,
                b.post_title AS title,
                b.post_content AS content,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = " WHERE b.post_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $sOrder = (@$params['prev']&&$params['prev']==true)?" ORDER BY a.id DESC":" ORDER BY a.id ASC";
        $sLimit = " ";
        if((@$params['next']&&$params['next']==true) || (@$params['prev']&&$params['prev']==true)){
            $sLimit .= ' LIMIT 1';
        }
        $SQL = "SELECT * FROM 
                (SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_post_text b ON b.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_post_category c ON c.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_category d ON d.category_id=c.category_id
                " . $sWhere . " GROUP BY a.post_id) a ";
        $SQL .= $sOrder . $sLimit;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0])?$result[0]:null;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getCountPost($params=null) {
        $table_name = $this->dbname. ".cms_post a";
        $select = "COUNT(a.post_id) As total";
        $sWhere = " WHERE b.post_lang_code='" .$this->f3->get('LANG'). "' AND e.category_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM (
                SELECT a.post_id
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_post_text b ON b.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_post_category c ON c.post_id=a.post_id
                LEFT JOIN " .$this->dbname. ".cms_category d ON d.category_id=c.category_id
                LEFT JOIN " .$this->dbname. ".cms_category_text e ON d.category_id=e.category_id
                " . $sWhere . " GROUP BY a.post_id) a ";
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['total'])?$result[0]['total']:0;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return 0;
        }
    }

    public function hitsPost($seo=null){
        $SQL = "UPDATE
                    " .$this->dbname. ".cms_post
                    SET
                        post_hits = (post_hits+1)
                    WHERE
                        post_seotitle = '".$seo."'";
        try {
            $result = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
        }
    }

    // gallery
    public function getListGalleryBy($params=null) {
        $table_name = $this->dbname. ".cms_gallery a";
        if(@$params['select']&&$params['select']!=null){
            $select = 'SQL_CALC_FOUND_ROWS ' .$params['select'];
        }else{
            $select = "SQL_CALC_FOUND_ROWS 
                a.gallery_id AS id,
                a.gallery_title AS title,
                a.gallery_seotitle AS seotitle,
                a.gallery_active AS active,
                a.gallery_hits AS hits,
                a.images_id AS img_id,
                a.images_title AS img_title,
                a.images_content AS content,
                a.images_picture AS picture,
                COUNT(a.images_id) AS img_count,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $sOrder = " ORDER BY ";
        if(@$params['order']&&$params['order']!=null){
            $sOrder .= $params['order'];
        }else{
            $sOrder .= " a.gallery_id DESC";
        }
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $sGroup = " GROUP BY a.gallery_id ";
        $SQL = "SELECT
                " .$select. " 
                FROM 
                (SELECT * FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_gallery_images b ON a.gallery_id=b.images_gallery_id
                " . $sWhere . $sOrder . ") a";
        $SQL .= $sGroup . $sLimit;
        try {
            $result = $this->db->exec($SQL);
            return $result;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getImgGalleryBy($params=null) {
        $table_name = $this->dbname. ".cms_gallery a";
        if(@$params['select']&&$params['select']!=null){
            $select = 'SQL_CALC_FOUND_ROWS ' .$params['select'];
        }else{
            $select = "SQL_CALC_FOUND_ROWS 
                a.gallery_id AS id,
                a.gallery_title AS title,
                a.gallery_seotitle AS seotitle,
                a.gallery_active AS active,
                a.gallery_hits AS hits,
                b.images_id AS img_id,
                b.images_title AS img_title,
                b.images_content AS content,
                b.images_picture AS picture,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = " WHERE b.images_id IS NOT NULL ";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $sOrder = " ORDER BY ";
        if(@$params['order']&&$params['order']!=null){
            $sOrder .= $params['order'];
        }else{
            $sOrder .= " b.images_gallery_id ASC";
        }
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_gallery_images b ON a.gallery_id=b.images_gallery_id
                " . $sWhere;
        $SQL .= $sOrder . $sLimit;
        try {
            $result = $this->db->exec($SQL);
            return $result;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getGalleryBy($params=null) {
        $table_name = $this->dbname. ".cms_gallery a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.gallery_id AS id,
                a.gallery_title AS title,
                a.gallery_seotitle AS seotitle,
                a.gallery_active AS active,
                a.gallery_hits AS hits,
                a.update_user,
                a.update_timestamp,
                b.images_picture AS picture";
        }
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $k = str_replace('post','gallery',$k);
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $sOrder = " ORDER BY a.gallery_id DESC";
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN ".$this->dbname. ".cms_gallery_images b ON b.images_gallery_id=a.gallery_id
                " . $sWhere . " GROUP BY a.gallery_id";
        $SQL .= $sOrder;
        try {
            $result_gal = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                return $err[2];
            }
        }
        $SQL2 = "SELECT
                b.images_id AS img_id,
                b.images_title AS img_title,
                b.images_content AS content,
                b.images_picture AS picture
                FROM " .$table_name. "
                LEFT JOIN " .$this->dbname. ".cms_gallery_images b ON a.gallery_id=b.images_gallery_id 
                " . $sWhere;
        $SQL2 .= $sOrder . $sLimit;
        try {
            $result_img = $this->db->exec($SQL2);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
        }
        $result = array();
        if(@$result_gal[0]) {
            $result = $result_gal[0];
            if(@$result_img){
                $img['images'] = $result_img;
            }
            $result = array_merge($result,$img);
        }
        return $result;
    }

    public function getCountGallery($params=null) {
        $table_name = $this->dbname. ".cms_gallery a";
        $select = "COUNT(a.gallery_id) As total";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                " . $sWhere;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['total'])?$result[0]['total']:0;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return 0;
        }
    }

    public function getCountImagesGallery($params=null) {
        $table_name = $this->dbname. ".cms_gallery a";
        $select = "COUNT(b.images_id) As total";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        if(@$params['like']&&$params['like']!=null){
            $sWhere .= ($sWhere=="")?" WHERE":" AND";
            $sWhere .= " ".$params['like'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_gallery_images b ON a.gallery_id=b.images_gallery_id
                " . $sWhere;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['total'])?$result[0]['total']:0;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return 0;
        }
    }

    public function hitsGallery($seo=null){
        $SQL = "UPDATE
                    " .$this->dbname. ".cms_gallery
                    SET
                        gallery_hits = (gallery_hits+1)
                    WHERE
                        gallery_seotitle = '".$seo."'";
        try {
            $result = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
        }
    }

    public function publishPost($params){
        $SQL = "UPDATE
            " .$this->dbname. ".cms_post
            SET
                post_active = 'Y'
            WHERE
                post_active = 'N' 
                AND ".$params['like'];
        try {
            return $result = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    // pages
    public function getPagesBy($params=null) {
        $table_name = $this->dbname. ".cms_pages a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.pages_id AS id,
                a.pages_seotitle AS seotitle,
                a.pages_picture AS picture,
                b.pages_title AS title,
                b.pages_content AS content,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = " WHERE b.pages_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.pages_id ASC";
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_pages_text b ON b.pages_id=a.pages_id
                " . $sWhere;
        $SQL .= $sOrder;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0])?$result[0]:null;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function getPages($params=null) {
        $table_name = $this->dbname. ".cms_pages a";
        if(@$params['select']&&$params['select']!=null){
            $select = $params['select'];
        }else{
            $select = "a.pages_id AS id,
                a.pages_seotitle AS seotitle,
                a.pages_picture AS picture,
                b.pages_title AS title,
                b.pages_content AS content,
                a.update_user,
                a.update_timestamp";
        }
        $sWhere = " WHERE b.pages_lang_code='" .$this->f3->get('LANG'). "'";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.pages_id ASC";
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_pages_text b ON b.pages_id=a.pages_id
                " . $sWhere;
        $SQL .= $sOrder;
        try {
            $result = $this->db->exec($SQL);
            return (@$result)?$result:null;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    // comment
    public function getComment($params=null) {
        $table_name = $this->dbname. ".cms_comment a";
        $select = "a.comment_id AS id,
                a.comment_parent_id AS parent,
                a.comment_post_id AS post_id,
                a.comment_name AS name,
                a.comment_email AS email,
                a.comment_url AS url,
                a.comment_text AS text,
                a.comment_date AS pdate,
                a.comment_time AS ptime,
                a.comment_active AS active,
                a.comment_status AS status";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.comment_id ASC";
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder . $sLimit;
        try {
            $_tmp = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
        if(@$params['id_post'] && $params['id_post']!=null){
            $_comment = array();
            if(@$_tmp && $_tmp != null) {
                foreach($_tmp as $r) {
                    $_comment[$r['parent']][$r['id']] = $r;
                }
            }
            $result = "";
            $params_post['field'] = array('a.post_id'=>$params['id_post']);
            $_post = $this->getPostBy($params_post);
            $result .= '<div id="form-default">
                        <form method="post" action="' .$this->f3->get('BASE').'/comment" id="form-comment">
                        <input type="hidden" name="comment_parent_id" id="comment_parent_id" value="0">
                        <input type="hidden" name="comment_post_id" value="' .$params['id_post']. '">
                        <input type="hidden" name="comment_url" value="'.$_post['seotitle']. '">
                        <div class="form-group">
                            <h4>' .$this->seloLib->setLang('add_your_comment'). '</h4>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>' .$this->seloLib->setLang('name'). '</label>
                                <input type="text" name="comment_name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>' .$this->seloLib->setLang('email'). '</label>
                                <input type="email" name="comment_email" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>' .$this->seloLib->setLang('comment'). '</label>
                            <textarea name="comment_text" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-outline-secondary" type="submit">' .$this->seloLib->setLang('send'). '</button>
                        </div>
                        </form>
                    </div>';
            if($_comment != null) {
                $result .= '<ul>';
                $result .= $this->setCommentTree($_comment,0);
                $result .= '</ul>';
            }
            $result .= '<script>
                    $(\'.btn-batal\').hide();
                    function getFormComment(id){
                        var formBalas = $(\'#form-comment\');
                        $(\'#balas-\'+id).append(formBalas);
                        $(\'.btn-balas\').show();
                        $(\'#btn-balas-\'+id).hide();
                        $(\'.btn-batal\').hide();
                        $(\'#btn-batal-\'+id).show();
                        $(\'#comment_parent_id\').val(id);
                    }
                    function cancelComment(){
                        var formBalas = $(\'#form-comment\');
                        $(\'#form-default\').append(formBalas);
                        $(\'.btn-balas\').show();
                        $(\'.btn-batal\').hide();
                        $(\'#comment_parent_id\').val(0);
                    }
                    </script>';
        }else{
            $result = $_tmp;
        }
        return $result;
    }

    public function setCommentTree($_comment,$parent) {
        $html = "";
        if(@$_comment[$parent] && $_comment[$parent] != null) {
            foreach($_comment[$parent] as $r) {
                if(@$_comment[$r['id']] && $_comment[$r['id']] != null) {
                    $html .= '<li class="nav-item">';
                    $html .= '<a href="#" class="comment-author">' .ucwords($r['name']). '</a>
                                <div class="comment-time">
                                    <span>' .date('F d, Y',strtotime($r['pdate'])). ' - ' .$r['ptime']. '</span>
                                </div>
                                <p>' .$r['text']. '</p>
                                <div style="margin-bottom:10px;text-align: right;">
                                    <button id="btn-balas-' .$r['id']. '" class="btn btn-sm btn-outline-secondary btn-balas" onclick="getFormComment(\'' .$r['id']. '\')">' .$this->seloLib->setLang('reply'). '</button>
                                    <button id="btn-batal-' .$r['id']. '" class="btn btn-sm btn-outline-secondary btn-batal" onclick="cancelComment()">' .$this->seloLib->setLang('cancel'). '</button>
                                </div>
                                <div id="balas-' .$r['id']. '"></div>
                            <ul>';
                    $html .= $this->setCommentTree($_comment,$r['id']);
                    $html .= '</ul>';
                } else {
                    $html .= '<li class="nav-item">
                            <a href="#" class="comment-author">' .ucwords($r['name']). '</a>
                            <div class="comment-time">
                                <span>' .date('F d, Y',strtotime($r['pdate'])). ' - ' .$r['ptime']. '</span>
                            </div>
                            <p>' .$r['text']. '</p>
                            <div style="margin-bottom:10px;text-align: right;">
                                <button id="btn-balas-' .$r['id']. '" class="btn btn-sm btn-outline-secondary btn-balas" onclick="getFormComment(\'' .$r['id']. '\')">' .$this->seloLib->setLang('reply'). '</button>
                                <button id="btn-batal-' .$r['id']. '" class="btn btn-sm btn-outline-secondary btn-batal" onclick="cancelComment()">' .$this->seloLib->setLang('cancel'). '</button>
                            </div>
                            <div id="balas-' .$r['id']. '"></div>
                        </li>';
                }
            }
        }
        return $html;
    }

    public function saveComment($params) {
        $SQL = "INSERT INTO
                " .$this->dbname. ".cms_comment
                (comment_parent_id,comment_post_id,comment_name,comment_email,comment_url,comment_text,comment_date,comment_time,comment_active,comment_status)
                VALUES
                ('" .$params['comment_parent_id']. "','" .$params['comment_post_id']. "','" .$params['comment_name']. "','" .$params['comment_email']. "','" .$params['comment_url']. "','" .$params['comment_text']. "','" .date('Y-m-d'). "','" .date('H:i:s'). "','N','N')
                ";
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

    public function getCountComment($params=null) {
        $table_name = $this->dbname. ".cms_comment a";
        $SQL = "SELECT COUNT(*) AS total FROM " . $table_name;
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $SQL .= $sWhere;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['total'])?$result[0]['total']:0;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return 0;
        }
    }

    // setting
    public function getSetting($params=null) {
        $table_name = $this->dbname. ".cms_setting a";
        $select = "a.setting_value AS value";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.setting_id ASC";
        $SQL = "SELECT
                " .$select. " 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0]['value'])?$result[0]['value']:'';
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return '';
        }
    }

    // widget
    public function getWidget($params=null) {
        $table_name = $this->dbname. ".cms_widget a";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY ";
        if(@$params['order']&&$params['order']!=null){
            $sOrder .= $params['order'];
        }else{
            $sOrder .= " a.widget_sort ASC";
        }
        $SQL = "SELECT
                b.component AS component,
                b.component_type AS type,
                a.widget_title AS title,
                a.widget_text AS value
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_component b ON a.widget_component_id=b.component_id
                AND b.component_active = 'Y'
                " . $sWhere;
        $SQL .= $sOrder;
        try {
            $result = $this->db->exec($SQL);
            return $result;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    // menu
    public function getMenu($class=null,$type=null,$title=null) {
        $table_name = $this->dbname. ".cms_menu a";
        $sWhere = " WHERE b.menu_group_title='" .$title. "' AND a.menu_active='Y'";
        $sOrder = " ORDER BY a.menu_position ASC";
        $SQL = "SELECT
                    a.menu_id AS id,
                    a.menu_parent_id AS parent,
                    a.menu_title AS title,
                    a.menu_url AS url,
                    a.menu_class AS class,
                    a.menu_position, 
                    b.menu_group_id AS group_id, 
                    b.menu_group_title AS group_title, 
                    a.menu_active AS active, 
                    a.menu_target AS target 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_menu_group b ON a.menu_group_id=b.menu_group_id
                " . $sWhere;
        $SQL .= $sOrder;
        try {
            $_tmp = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
        $_menu = array();
        if(@$_tmp && $_tmp != null) {
            if($type=='xml'){
                return $_tmp;
            }
            foreach($_tmp as $r) {
                $_menu[$r['parent']][$r['id']] = $r;
            }
        }
        $result = "";
        if($_menu != null) {
            $result = '<ul class="' .$class. '">';
            $result .= $this->setMenuTree($_menu,0,$type);
            $result .= '</ul>';
        }
        return $result;
    }

    public function setMenuTree($_menu,$parent,$type) {
        $html = "";
        $html = ($type=='collapse'&&$parent==0)?'<li class="header">MAIN NAVIGATION</li>':'';
        if(@$_menu[$parent] && $_menu[$parent] != null) {
            foreach($_menu[$parent] as $r) {
                if($type=='collapse'){
                    $_class = 'treeview-menu';
                    $_type = 'treeview';
                    $_id = str_replace(' ','-',$r['title']);
                    $_a = 'side_menu';
                    $_nav_item = 'treeview';
                    $_arrow = '<i class="fa fa-angle-left pull-right"></i>';
                }elseif($type=='sitemap'){
                    $_class = '';
                    $_type = '';
                    $_id = '';
                    $_a = '';
                    $_nav_item = '';
                    $_arrow = '';
                }else{
                    $_class = '';
                    $_type = 'dropdown';
                    $_id = '';
                    $_a = ($parent==0)?'nav-link':'';
                    $_nav_item = 'nav-item';
                    $_arrow = '';
                }
                if($r['class']!=null && $r['class']!=''){
                    if(strpos(' '.$r['class'],'fa-')){
                        $_icon = '<i class="fa '.$r['class'].'"></i>';
                    }else{
                        $_icon = '<img src="'.$this->f3->get('BASE').'/images/thumb/'.$r['class'].'">';
                    }
                }else{
                    $_icon = '';
                }
                if(@$_menu[$r['id']] && $_menu[$r['id']] != null) {
                    $cek = $this->checkMenuAccess(strtolower($r['title']),strtolower($r['url']));
                    if($cek==true || $type!='collapse'){
                        $html .= ($parent==0)?'<li class="'.$_nav_item.' '.$_type.'">':'<li class="'.$_type.'-submenu">';
                        if($type=='sitemap'){
                            $html .= '<a class="" href="#">
                                '.$_icon.'
                                <span class="menu-label">' .ucwords($r['title']). '</span>
                                </a>
                                    <ul class="" id="'.str_replace(' ','-',$r['title']).'">';
                        }else{
                            $html .= '<a class="' .$_a. ' dropdown-toggle" href="#'.$_id.'" data-toggle="'.$type.'" aria-haspopup="true" aria-expanded="false">
                                '.$_icon.'
                                <span class="menu-label">' .ucwords($r['title']). '</span>
                                '.$_arrow.'
                                </a>
                                    <ul class="'.$_class.' '.$_type.'-menu " id="'.str_replace(' ','-',$r['title']).'">';
                        }
                        $html .= $this->setMenuTree($_menu,$r['id'],$type);
                        $html .= '</ul>';
                        $html .= '</li>';
                    }
                } else {
                    $cek = $this->checkMenuAccess(strtolower($r['title']),strtolower($r['url']));
                    if($cek==true || $type!='collapse'){
                        $html .= '<li class="'.$_nav_item.'">
                                <a class="' .$_a. '" href="' .$this->f3->get('BASE').$r['url']. '">'.$_icon.'<span class="menu-label">' .ucwords($r['title']). '</span></a>
                            </li>';
                    }
                }
            }
        }
        return $html;
    }

    // component
    public function subscribe(){
        $SQL = "SELECT
                a.subscribe_email AS email,
                a.subscribe_name AS name,
                a.subscribe_active AS active,
                a.subscribe_instansi AS instansi
            FROM
            " .$this->dbname. ".cms_subscribe a
            WHERE
                a.subscribe_active = 'Y'";
        try {
            return $result = $this->db->exec($SQL);
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function saveSubscribe($params) {
        $SQL = "INSERT INTO
                " .$this->dbname. ".cms_subscribe
                (subscribe_email,subscribe_name)
                VALUES
                ('" .$params['subscribe_email']. "','" .$params['subscribe_name']. "')
                ";
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

    public function getUserBy($params=null) {
        $table_name = $this->dbname. ".cms_user a";
        $sWhere = "";
        if(@$params['field']&&$params['field']!=null){
            foreach($params['field'] as $k => $v){
                $sWhere .= ($sWhere=="")?" WHERE":" AND";
                $sWhere .= " $k='$v'";
            }
        }
        $sOrder = " ORDER BY a.user_id ASC";
        $SQL = "SELECT
                a.user_id AS id,
                a.user_real_name AS real_name,
                a.user_user_name AS user_name,
                a.user_email AS email,
                a.user_password AS pass,
                a.user_desc,
                a.user_no_password AS no_password,
                a.user_active AS active,
                a.user_force_logout AS force_logout,
                a.user_active_lang_code AS lang_code,
                a.user_last_logged_in AS last_logged_in,
                a.user_last_ip AS last_ip,
                a.insert_user,
                a.insert_timestamp,
                a.update_user,
                a.update_timestamp,
                a.user_skin AS skin,
                GROUP_CONCAT(b.usergroup_group_id) AS ugroup 
                FROM " .$table_name. " 
                LEFT JOIN " .$this->dbname. ".cms_user_group b ON a.user_id=b.usergroup_user_id
                " . $sWhere . " GROUP BY a.user_id";
        $SQL .= $sOrder;
        try {
            $result = $this->db->exec($SQL);
            return (@$result[0])?$result[0]:null;
        } catch(\PDOException $e) {
            $err = $e->errorInfo;
            if($this->f3->get('DEBUG')==3){
                echo $err[2];
            }
            return null;
        }
    }

    public function updateUser() {
        $SQL = "UPDATE
                " .$this->dbname. ".cms_user
                SET
                    user_last_logged_in = '" .date('Y-m-d H:i:s'). "',
                    user_last_ip = '" .$_SERVER['REMOTE_ADDR']. "'
                WHERE
                    user_id = " .Selo::instance()->getUser('id');
        $result = $this->db->exec($SQL);
    }

    public function visitorSave() {
        $SQL = "SELECT * FROM 
                " .$this->dbname. ".cms_visitor a 
                WHERE 
                visitor_ip = '".$this->visitorLib->ip_user()."'
                AND visitor_date = '".date('Y-m-d')."'";
        $result = $this->db->exec($SQL);
        if($result==null){
            $SQL = "INSERT INTO
                " .$this->dbname. ".cms_visitor 
                (visitor_ip,visitor_os,visitor_browser,visitor_hits,visitor_date)
                VALUES
                ('".$this->visitorLib->ip_user()."','".$this->visitorLib->os_user()."','".$this->visitorLib->browser_user()."',1,'".date('Y-m-d')."')";
            $result = $this->db->exec($SQL);
        }else{
            $SQL = "UPDATE
                " .$this->dbname. ".cms_visitor
                SET
                    visitor_hits = (visitor_hits+1)
                WHERE
                    visitor_ip = '".$this->visitorLib->ip_user()."'
                    AND visitor_date = '".date('Y-m-d')."'";
            $result = $this->db->exec($SQL);
        }
    }

    public function checkMenuAccess($title,$url) {
        $user_access = $this->f3->get('SESSION.access');
        if(@$user_access[str_replace('/a','',$url)] || @$user_access[$title]){
            return true;
        }else{
            return false;
        }
    }

}