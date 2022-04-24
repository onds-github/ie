<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Order extends Zend_Db_Table {

    protected $_name = 'on_order';
    protected $_primary = 'id_order';
    protected $_rowClass = 'Order';
     
    public function selectBalance() {
        $sql = $this->getAdapter()->select()->from(
                array('_or' => 'on_order'), 
                array(
                    'total_in_okay' => 'SUM(IF(id_type_order = 1 AND situation_order = 1, price_order, 0))', 
                    'total_in_notokay' => 'SUM(IF(id_type_order = 1 AND situation_order = 0, price_order, 0))', 
                    'total_out_okay' => 'SUM(IF(id_type_order = 2 AND situation_order = 1, price_order, 0))', 
                    'total_out_notokay' => 'SUM(IF(id_type_order = 2 AND situation_order = 0, price_order, 0))', 
                    )
                );

        $sql->where('_or.date_due_order '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_contact != 0) {
            $sql->where('_or.id_contact = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_contact);
        }
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_account != 0) {
            $sql->where('_or.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_account);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order'), array('_or.id_order', '_or.description_order', '_or.date_due_order', '_or.price_order', '_or.situation_order', '_or.id_contact', '_or.id_account', '_or.id_category', '_or.id_type_order'))
                ->joinLeft(array('_co' => 'on_contact'), '_or.id_contact = _co.id_contact', array('_co.nickname_contact'))
                ->joinLeft(array('_ca' => 'on_category'), '_or.id_category = _ca.id_category', array('_ca.name_category'))
                ->joinLeft(array('_ac' => 'on_account'), '_or.id_account = _ac.id_account', array('_ac.name_account'))
            ;

        switch (Zend_Auth::getInstance()->getIdentity()->filter_id_type_period) {
            case 1:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d");
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d");
                break;
            case 2:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d", strtotime("monday this week"));
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d", strtotime("sunday this week"));
                break;
            case 3:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d", strtotime("first day of this month"));
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d", strtotime("last day of this month"));
                break;
            case 4:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d", strtotime("first day of january"));
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d", strtotime("last day of December"));
                break;
        }
        
        $sql->where('_or.date_due_order '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_contact != 0) {
            $sql->where('_or.id_contact = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_contact);
        }
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_account != 0) {
            $sql->where('_or.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_account);
        }
        
        
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
