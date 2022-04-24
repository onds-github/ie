<?php

class Share_OrderServiceController extends Zend_Controller_Action {

    public function init() {

    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_share');
        Zend_Loader::loadClass('Post');
        $model = new Post();

        $this->view->title_page = 'Orçamento';
        $this->view->description_page = '';
        
        $this->view->headScript()
                ->appendFile('/public/assets/js/script.order.js?v=' . time());
    }
    
    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderService');
        $model = new OrderService();

        $result = $model->selectTableBudget($this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderServiceItem');
        $model = new OrderServiceItem();

        $result = $model->selectTableDetails();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }
    
}
