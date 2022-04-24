<?php

class Finance_OrderOutController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 5;
        $this->view->title_page = 'Despesas';
        $this->view->description_page = 'Lançamentos de saída';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.order-out.js');
    }

    public function selectContactDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();
        
        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['nickname_contact'],
                value => $value['id_contact'],
                text => $value['nickname_contact']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectAccountDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Account');
        $model = new Account();
        
        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_account'],
                value => $value['id_account'],
                text => $value['name_account']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectCostCenterDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();
        
        foreach ($model->selectTableDropdown(strval($this->_request->getParam('q'))) as $value) {
            $result[] = array(
                name => $value['name_cost_center'],
                value => $value['id_cost_center'],
                text => $value['name_cost_center']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderOut');
        $model = new OrderOut();

        $result = $model->selectTable($this->_request->getParam('q'));
        
        $result[0]['price_order_out'] = number_format($result[0]['price_order_out'], 2, ',', '.');

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderOut');
        $model = new OrderOut();

        $array = $model->selectTable();

        foreach ($array as $value) {
            $result[] = array(
                $value['id_order_out'],
                array(
                    name_cost_center => $value['name_cost_center'],
                    icon_cost_center => $value['icon_cost_center'],
                    color_cost_center => $value['color_cost_center']
                ),
                $value['description_order_out'],
                array(
                    nickname_contact => $value['nickname_contact'],
                    document_contact => $value['document_contact']
                ),
                $value['name_account'],
                $value['date_due_order_out'],
                number_format($value['price_order_out'], 2, ',', '.'),
                $value['situation_order_out']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderOut');
        $model = new OrderOut();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        if ($data['id_cost_center'] == '') {
            unset($data['id_cost_center']);
        }
        
        if ($data['price_order_out'] != '') {
            $data['price_order_out'] = str_replace('.', '', $data['price_order_out']);
            $data['price_order_out'] = str_replace(',', '.', $data['price_order_out']);
        }
        
        $result = $model->insertTable($data);
        
        echo Zend_Json::encode($result);
    }
    
    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderOut');
        $model = new OrderOut();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        if ($data['id_cost_center'] == '') {
            unset($data['id_cost_center']);
        }
        
        if ($data['price_order_out'] != '') {
            $data['price_order_out'] = str_replace('.', '', $data['price_order_out']);
            $data['price_order_out'] = str_replace(',', '.', $data['price_order_out']);
        }
        
        $result = $model->updateTable($this->_request->getParam('q'), $data);
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('OrderOut');
        $model = new OrderOut();
        
        $result = $model->deleteTable($this->_request->getParam('q'));
            
        echo Zend_Json::encode($result);
    }
    
    public function filterPeriodStateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        $data['filter_period_min'] = Zend_Auth::getInstance()->getIdentity()->filter_period_min;
        $data['filter_period_max'] = Zend_Auth::getInstance()->getIdentity()->filter_period_max;
        
        echo Zend_Json::encode($data);
    }
    
    public function filterPeriodCustomAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        Zend_Loader::loadClass('User');
        $model = new User();
        
        Zend_Auth::getInstance()->getIdentity()->id_filter_period = $data['id_filter_period'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterPeriodAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        switch ($this->_request->getParam('id_filter_period')) {
            case 1:
                $data['id_filter_period'] = 1;
                $data['filter_period_min'] = date("Y-m-d");
                $data['filter_period_max'] = date("Y-m-d");
                break;
            case 2:
                $data['id_filter_period'] = 2;
                $data['filter_period_min'] = date("Y-m-d", strtotime("monday this week"));
                $data['filter_period_max'] = date("Y-m-d", strtotime("sunday this week"));
                break;
            case 3:
                $data['id_filter_period'] = 3;
                $data['filter_period_min'] = date("Y-m-d", strtotime("first day of this month"));
                $data['filter_period_max'] = date("Y-m-d", strtotime("last day of this month"));
                break;
            case 4:
                $data['id_filter_period'] = 4;
                $data['filter_period_min'] = $this->_request->getParam('filter_period_min');
                $data['filter_period_max'] = $this->_request->getParam('filter_period_max');
                break;
        }
        
        Zend_Auth::getInstance()->getIdentity()->id_filter_period = $data['id_filter_period'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterPeriodPrevAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        switch (Zend_Auth::getInstance()->getIdentity()->id_filter_period) {
            case 1:
                $data['filter_period_min'] = date("Y-m-d", strtotime("-1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("-1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 2:
                $data['filter_period_min'] = date("Y-m-d", strtotime("-1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("-1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 3:
                $data['filter_period_min'] = date("Y-m-d", strtotime("first day of previous month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("last day of previous month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
        }
        
        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterPeriodNextAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        switch (Zend_Auth::getInstance()->getIdentity()->id_filter_period) {
            case 1:
                $data['filter_period_min'] = date("Y-m-d", strtotime("+1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("+1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 2:
                $data['filter_period_min'] = date("Y-m-d", strtotime("+1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("+1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 3:
                $data['filter_period_min'] = date("Y-m-d", strtotime("first day of next month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("last day of next month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
        }
        
        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

}
