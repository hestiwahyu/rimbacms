<?php

class DashboardModel extends DB\SQL\Mapper {

    protected $dbname;
    protected $url;

    public function __construct(DB\SQL $db) {
        parent::__construct($db, Base::instance()->get('DEVDBNAME'). '.cms_visitor');
        $f3 = Base::instance();
        $this->f3 = $f3;
        $this->dbname = $this->f3->get('DEVDBNAME');
        $this->url    = $this->f3->hive()['BASE'] . '/dashbard';
    }

    public function getVisitor($params=null) {
        $table_name = $this->dbname. ".cms_visitor a";

        if(@$params['type'] && $params['type']=='H'){
            $sWhere = " WHERE DATE_FORMAT(a.visitor_date,'%Y-%m')='".date('Y-m')."'";
            $sGroup = " GROUP BY DATE_FORMAT(a.visitor_date,'%d')";
            $label = "DATE_FORMAT(a.visitor_date,'%d') AS label";
        }else{
            $sWhere = " WHERE DATE_FORMAT(a.visitor_date,'%Y')='".date('Y')."'";
            $sGroup = " GROUP BY DATE_FORMAT(a.visitor_date,'%m')";
            $label = "DATE_FORMAT(a.visitor_date,'%M') AS label";
        }

        $SQL = "SELECT
                COUNT(a.visitor_id) AS visitor, 
                SUM(a.visitor_hits) AS hits,
                ".$label."
                FROM " .$table_name. " 
                " . $sWhere . $sGroup;
        $result = $this->db->exec($SQL);
        return $result;
    }

}