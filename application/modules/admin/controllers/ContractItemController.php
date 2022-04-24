<?php

class Admin_ContractItemController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 99;
        $this->view->title_page = 'Módulos';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/admin/contract-item/script.details.js'));
    }

    public function selectModuleDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Module');
        $model = new Module();

        foreach ($model->viewModuleDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_module'],
                value => $value['id_module'],
                text => $value['name_module']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContractItem');
        $model = new ContractItem();

        $result = $model->viewContractItem($this->_request->getParam('id_company'), $this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContractItem');
        $model = new ContractItem();

        $array = $model->viewContractItem($this->_request->getParam('id_company'));

        foreach ($array as $value) {
            $result[] = array(
                $value['id_contract_item'],
                $value['name_module'],
                $value['name_contract_item']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContractItem');
        $model = new ContractItem();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContractItem');
        $model = new ContractItem();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable($this->_request->getParam("q"), $data);
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('ContractItem');
        $model = new ContractItem();
        
        $result = $model->deleteTable($this->_request->getParam('q'));
            
        echo Zend_Json::encode($result);
    }
    
}
