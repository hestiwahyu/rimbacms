<?php

class ArchivesModel extends DB\SQL\Mapper {

    protected $dbname;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_post');
        $f3 = Base::instance();
        $this->f3       = $f3;
        $this->dbname   = $this->f3->get('DEVDBNAME');
    }

    public function getArchives() {
        $SQL = "SELECT DISTINCT DATE_FORMAT(a.post_publishdate,'%Y-%m') AS archives FROM ".$this->dbname.".cms_post a";
        $result = $this->db->exec($SQL);
        return $result;
    }

}