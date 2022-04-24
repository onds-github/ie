<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Form extends Zend_Db_Table {

    protected $_schema = 'marketing';
    protected $_name = 'form';
    protected $_primary = 'id';
    protected $_rowClass = 'Form';
    
    public function viewForm($a = null, $b = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_form');

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
    
    public function updateTable($a = null, $data = null) {        
        $where = $this->getAdapter()->quoteInto('id = ?', $a);        
        return $this->update($data, $where);         
    }
    
    public function deleteTable($a = null) {        
        $where = $this->getAdapter()->quoteInto('id = ?', $a);        
        return $this->delete($where);         
    }
    
}
