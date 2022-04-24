<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ContactsMessage extends Zend_Db_Table {

    protected $_schema = 'marketing';
    protected $_name = 'contacts_message';
    protected $_primary = 'id';
    protected $_rowClass = 'ContactsMessage';
    
    public function viewContactsMessage($a = null, $b = null) {
        $sql = $this->getAdapter()->select()->from('marketing.view_contacts_message');
        
        if ($a != '') {
            $sql->where('id = ?', $a);
        }
        
        if ($b != '') {
            $sql->where('id_contacts = ?', $b);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {  
        return $this->insert($data);
    }
    
}
