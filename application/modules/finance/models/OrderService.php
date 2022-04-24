<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderService extends Zend_Db_Table {

    protected $_name = 'on_order_service';
    protected $_primary = 'id_order_service';
    protected $_rowClass = 'OrderService';
    
    public function selectTableBudget($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_os' => 'on_order_service'), array('_os.id_order_service', '_os.reference_order_service', '_os.price_partial_order_service','_os.price_total_order_service','_os.price_discount_order_service', '_os.datetime_order_service', '_os.date_validity_order_service', '_os.description_order_service', '_os.deadline_order_service', '_os.payment_order_service'))
                ->joinLeft(array('_co' => 'on_contact'), '_co.id_contact = _os.id_contact', array('_co.name_contact', '_co.document_contact', '_co.adress_zipcode_contact', '_co.adress_public_place_contact', '_co.adress_number_contact', '_co.adress_district_contact', '_co.adress_city_contact', '_co.adress_state_contact', '_co.phone_primary_contact', '_co.email_contact'))
                ->joinLeft(array('_us' => 'on_user'), '_us.id_user = _os.id_user', array('_us.name_user'))
                ->joinLeft(array('_oss' => 'on_order_service_status'), '_oss.id_order_service_status = _os.id_order_service_status', array('_oss.name_order_service_status'));

        if ($_primary != '') {
            $sql->where('MD5(id_order_service) = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableDropdown($query = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);

        if ($query != '') {
            $sql->where('name_order_service ilike ?', '%' . $query . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_os' => 'on_order_service'), array('_os.id_order_service', '_os.reference_order_service', '_os.price_partial_order_service', '_os.datetime_order_service', '_os.date_validity_order_service', '_os.description_order_service', '_os.deadline_order_service', '_os.payment_order_service'))
                ->joinLeft(array('_co' => 'on_contact'), '_co.id_contact = _os.id_contact', array('_co.name_contact', '_co.document_contact', '_co.adress_zipcode_contact', '_co.adress_public_place_contact', '_co.adress_number_contact', '_co.adress_district_contact', '_co.adress_city_contact', '_co.adress_state_contact', '_co.phone_primary_contact', '_co.email_contact'));

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
            $where[] = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);          
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function deleteTable($_primary = null) {        
        try {
            $where[] = $this->getAdapter()->quoteInto($this->_primary . ' IN(?)', $_primary);            
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
