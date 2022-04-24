<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Contact extends Zend_Db_Table {

    protected $_schema = 'marketing';
    protected $_name = 'contact';
    protected $_primary = 'id';
    protected $_rowClass = 'Contact';
    
    public function insertTable($data = null) {  
        return $this->insert($data);
    }
    
}
