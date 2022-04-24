<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProjectTask extends Zend_Db_Table {

    protected $_name = 'on_project_task';
    protected $_primary = 'id_project_task';
    protected $_rowClass = 'ProjectTask';
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_pt' => 'on_project_task'))
            ->joinLeft(array('_pr' => 'on_project'), '_pt.id_project = _pr.id_project', array('_pr.name_project'))
            ->joinLeft(array('_pts' => 'on_project_task_status'), '_pt.id_project_task_status = _pts.id_project_task_status', array('_pts.name_project_task_status'))
            ->joinLeft(array('_co' => 'on_contact'), '_pr.id_contact = _co.id_contact', array('_co.id_company'))
                ;
        
        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
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
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function deleteTable($_primary = null) {        
        try {  
            $where = $this->getAdapter()->quoteInto($this->_primary . ' IN(?)', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
