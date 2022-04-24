<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Contacts extends Zend_Db_Table {

    protected $_schema = 'marketing';
    protected $_name = 'contacts';
    protected $_primary = 'id';
    protected $_rowClass = 'Contacts';
    
    
    
    public function viewFormLeadSelected($a = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_form_lead_selected');
        
        if ($a != '') {
            $sql->where('id = ?', $a);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContactsWidget($a = null, $b = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_contacts_widget');
        
        if ($a != '') {
            $sql->where('id = ?', $a);
        }
        
        if ($b != '') {
            $sql->where('cookie_key = ?', $b);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContacts($a = null, $b = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_contacts');
        
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
    
}
