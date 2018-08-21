<?php

class LanguageModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_lang');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/alang';
    }

    public function getDataAll() {
        $table_name = $this->dbname. ".cms_lang a";
        $sWhere = " WHERE a.lang_active='Y'";
        $sOrder = " ORDER BY a.lang_id ASC";

        $SQL = "SELECT 
                a.lang_id AS id,
                a.lang_title AS title,
                a.lang_code AS code,
                a.lang_active AS active,
                a.update_user,
                a.update_timestamp 
                FROM " .$table_name. " 
                " . $sWhere;
        $SQL .= $sOrder;
        $result = $this->db->exec($SQL);
        return $result;
    }

}