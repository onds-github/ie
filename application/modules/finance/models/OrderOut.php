<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderOut extends Zend_Db_Table {

    protected $_name = 'on_order_out';
    protected $_primary = 'id_order_out';
    protected $_rowClass = 'OrderOut';
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_oo' => $this->_name), array('_oo.id_order_out', '_oo.description_order_out', '_oo.date_due_order_out', '_oo.situation_order_out', '_oo.price_order_out', '_oo.id_contact', '_oo.id_account', '_oo.id_cost_center'))
                ->joinLeft(array('_co' => 'on_contact'), '_oo.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_cc' => 'on_cost_center'), '_oo.id_cost_center = _cc.id_cost_center', array('_cc.name_cost_center', '_cc.icon_cost_center', '_cc.color_cost_center'))
                ->joinLeft(array('_ac' => 'on_account'), '_oo.id_account = _ac.id_account', array('_ac.name_account'))
            ;
        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        $sql->where('_oo.date_due_order_out '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
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
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
