<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Company extends Zend_Db_Table {

    protected $_name = 'on_company';
    protected $_primary = 'id_company';
    protected $_rowClass = 'Company';
    
    public function viewCompanyDetails($id_company = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_company');

        $sql->where('id_company = ?', $id_company);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewCompany($_primary = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);

        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {    
        try {     
            $this->insert($data);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function updateTable($_primary = null, $data = null) {    
        try {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
}
