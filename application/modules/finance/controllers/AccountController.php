<?php

class Finance_AccountController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 102;
        $this->view->title_page = 'Contas';
        $this->view->description_page = 'Gerenciamento recursos';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.account.js');
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Account');
        $model = new Account();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Account');
        $model = new Account();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Account');
        $model = new Account();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $result = $model->updateTable($this->_request->getParam('q'), $data);
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Account');
        $model = new Account();
        
        $result = $model->deleteTable($this->_request->getParam('q'));
            
        echo Zend_Json::encode($result);
    }
    
}
