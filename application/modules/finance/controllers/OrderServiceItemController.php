<?php

class Finance_OrderServiceItemController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 5;
        $this->view->title_page = 'Itens da ordem de serviço';
        $this->view->description_page = 'Lançamentos de entrada';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.order-service.js?v=' . time());
        
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderService');
        $model = new OrderService();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderService');
        $model = new OrderService();

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

        Zend_Loader::loadClass('OrderService');
        $model = new OrderService();

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
        
        Zend_Loader::loadClass('OrderService');
        $model = new OrderService();
        
        $result = $model->deleteTable($this->_request->getParam('q'));
            
        echo Zend_Json::encode($result);
    }

}
