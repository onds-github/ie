<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProjectTaskStatus extends Zend_Db_Table {

    protected $_name = 'on_project_task_status';
    protected $_primary = 'id_project_task_status';
    protected $_rowClass = 'ProjectTaskStatus';
    
    public function selectTableDropdown($_primary = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $returning = $this->insert($data);
            return array(status => 'success', message => 'O registro foi salvo!', returning => $returning);
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function updateTable($_primary = null, $data = null) {    
        try {
            $where[] = $this->getAdapter()->quoteInto('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session); 
            $where[] = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);           
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function deleteTable($_primary = null) {        
        try {   
            $where[] = $this->getAdapter()->quoteInto('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session); 
            $where[] = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
