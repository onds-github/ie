<?php

class Finance_OrderController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 100;
        $this->view->title_page = 'Lançamentos';
        $this->view->description_page = 'Lançamentos';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.order.js?v=' . time());
        
    }

    public function dropdownContactAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();
        
        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['nickname_contact'],
                value => $value['id_contact']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function dropdownCategoryAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Category');
        $model = new Category();
        
        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_category'],
                value => $value['id_category']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function dropdownAccountAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Account');
        $model = new Account();
        
        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_account'],
                value => $value['id_account']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function balanceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $result = $model->selectBalance()[0];

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        if ($data['price_order'] != '') {
            $data['price_order'] = str_replace('.', '', $data['price_order']);
            $data['price_order'] = str_replace(',', '.', $data['price_order']);
        }
        
        $result = $model->insertTable($data);
        
        echo Zend_Json::encode($result);
    }
    
    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        if ($data['price_order'] != '') {
            $data['price_order'] = str_replace('.', '', $data['price_order']);
            $data['price_order'] = str_replace(',', '.', $data['price_order']);
        }
        
        $result = $model->updateTable($this->_request->getParam('q'), $data);
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Order');
        $model = new Order();
        
        $data = json_decode($this->_request->getParam("data"), true);
        
        $result = $model->deleteTable($data);
            
        echo Zend_Json::encode($result);
    }

}
