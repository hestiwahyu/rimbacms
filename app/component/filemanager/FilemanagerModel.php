<?php

class FilemanagerModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_gallery_images');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/afilemanager';
    }

    public function getDataAll($params) {
        $table_name = $this->dbname. ".cms_gallery_images a";
        $sLimit = " ";
        if(@$params['limit']&&$params['limit']!=null){
            $sLimit .= $params['limit'];
        }

        $SQL = "SELECT
                SQL_CALC_FOUND_ROWS 
                a.images_id AS id,
                a.images_title AS title,
                a.images_content AS content,
                a.images_picture AS picture
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sLimit;
        $result = $this->db->exec($SQL);
        return $result;
    }

    public function saveData($id,$params) {
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
        if(@$result){
            $data = array(
                '_pesan'    => 'Data berhasil disimpan.',
                '_redirect' => true
            );
        }else{
            $data = array(
                '_pesan'    => 'Gagal menyimpan data!',
                '_redirect' => false
            );
        }
        return $data;
    }

}