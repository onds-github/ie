<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Analytics extends Zend_Db_Table {

    protected $_schema = 'marketing';
    protected $_name = 'analytics';
    protected $_primary = 'id';
    protected $_rowClass = 'Analytics';
    
    public function viewAnalyticsHighcharts($a = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_analytics_highcharts');
        
        if ($a != '') {
            $sql->where('id_project = ?', $a);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewAnalytics($a = null, $b = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_analytics');
        
        if ($a != '') {
            $sql->where('id = ?', $a);
        }
        
        if ($b != '') {
            $sql->where('id_project = ?', $b);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {  
        return $this->insert($data);
    }
    
}
