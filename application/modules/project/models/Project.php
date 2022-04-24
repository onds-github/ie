<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Project extends Zend_Db_Table {

    protected $_name = 'on_project';
    protected $_primary = 'id_project';
    protected $_rowClass = 'Project';
    
    public function selectTableDropdown($query_contact = null) {
        $sql = $this->getAdapter()->select()->from(array('_pr' => 'on_project'))
            ->joinLeft(array('_co' => 'on_contact'), '_co.id_contact = _pr.id_contact', array('_co.id_company', '_co.nickname_contact'));
         
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
        if ($query_contact != '') {
        $sql->where('name_project ilike ?', '%' . $query_contact . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_pr' => 'on_project'))
            ->joinLeft(array('_co' => 'on_contact'), '_co.id_contact = _pr.id_contact', array('_co.nickname_contact'));
         
        if ($primary != '') {
            $sql->where($this->_primary . ' = ?', $primary);
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
            $where[] = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);           
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function deleteTable($_primary = null) {        
        try {   
            $where[] = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
