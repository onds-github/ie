<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Dashboard extends Zend_Db_Table {

    public function selectProjectTaskStatusDashboard() {
        $sql = $this->getAdapter()->select()->from(
                array('_pts' => 'on_project_task_status'), 
                array(
                    '_pts.id_project_task_status',
                    '_pts.name_project_task_status',
                    '(SELECT count(_pt.id_project_task) FROM on_project_task _pt '
                    . 'WHERE _pt.id_project_task_status = _pts.id_project_task_status) as total_project_task'
                    )
                );

        $sql->where('_pts.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectAccountDashboard() {
        $sql = $this->getAdapter()->select()->from(
                array('_as' => 'on_account'), 
                array(
                    '_as.id_account',
                    '_as.name_account',
                    '('
                    . '(SELECT sum(_in.price_order_in) FROM on_order_in _in '
                    . 'WHERE _in.id_account = _as.id_account '
                    . 'AND _in.situation_order_in = 1) - '
                    . '( SELECT sum(_out.price_order_out) FROM on_order_out _out '
                    . 'WHERE _out.id_account = _as.id_account '
                    . 'AND _out.situation_order_out = 1)'
                    . ') as total_order'
                    )
                );

        $sql->where('_as.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
        $sql->group(array('_as.id_account', '_as.name_account'));
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectCostCenterDashboard() {
        $sql = $this->getAdapter()->select()->from(
            array('_cc' => 'on_cost_center'), 
            array(
                'id_cost_center',
                'icon_cost_center',
                'color_cost_center',
                'name_cost_center',
                'COALESCE((SELECT sum(_out.price_order_out) FROM on_order_out _out '
                . 'LEFT JOIN on_contact _co on _out.id_contact = _co.id_contact '
                . 'WHERE _co.id_company = _cc.id_company '
                . 'AND _out.id_cost_center = _cc.id_cost_center '
//                . 'AND _out.situation_order_out = 1'
                . '), 0) as total_order'
                )
            );

        $sql->where('_cc.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTotalDashboard() {
        $sql = $this->getAdapter()->select()->from(
                array('_co' => 'on_company'), 
                array(
                    'total_accounts' => 
                    '('
                    . '(SELECT sum(_in.price_order_in) FROM on_order_in _in '
                    . 'LEFT JOIN on_contact _con on _con.id_contact = _in.id_contact '
                    . 'WHERE _con.id_company = _co.id_company '
                    . 'AND _in.situation_order_in = 1) - '
                    . '(SELECT sum(_out.price_order_out) FROM on_order_out _out '
                    . 'LEFT JOIN on_contact _con on _con.id_contact = _out.id_contact '
                    . 'WHERE _con.id_company = _co.id_company '
                    . 'AND _out.situation_order_out = 1)'
                    . ')'
                    )
                );
        
        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
         
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewInOutDashboard($year = null, $month = null) {
        $sql = $this->getAdapter()->select()->from(
                array('_co' => 'on_company'), 
                array(
                    '(SELECT sum(_in.price_order_in) FROM on_order_in _in '
                    . 'LEFT JOIN on_contact _con on _con.id_contact = _in.id_contact '
                    . 'WHERE _con.id_company = _co.id_company '
                    . 'AND YEAR(_in.date_due_order_in) = ' . $year . ' AND MONTH(_in.date_due_order_in) = ' . $month . ' '
                    . 'AND _in.situation_order_in = 1'
                    . ') as total_order_in',
                    '(SELECT sum(_out.price_order_out) FROM on_order_out _out '
                    . 'LEFT JOIN on_contact _con on _con.id_contact = _out.id_contact '
                    . 'WHERE _con.id_company = _co.id_company '
                    . 'AND YEAR(_out.date_due_order_out) = ' . $year . ' AND MONTH(_out.date_due_order_out) = ' . $month . ' '
                    . 'AND _out.situation_order_out = 1'
                    . ') as total_order_out'
                    )
                );

        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
}
