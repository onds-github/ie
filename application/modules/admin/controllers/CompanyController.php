<?php

class Admin_CompanyController extends Zend_Controller_Action {

    public function init() {
        #vericação de sessão ativa e guarda página atual
        if(!Zend_Auth::getInstance()->hasIdentity()){
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 99;
        $this->view->title_page = 'Meus clientes';

        $this->view->headScript()
                ->appendFile('/public/modules/admin/script.company.js');
    }

    public function detailsAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 99;
        $this->view->title_page = 'Info. do cliente';

        $this->view->headScript()
                ->appendFile('/public/library/viacep/default.js')
                ->appendFile('/public/modules/admin/company/script.details.js');
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();

        $result = $model->viewCompanyDetails($this->_request->getParam('id_company'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();

        foreach ($model->viewCompany() as $value) {
            $result[] = array(
                $value['id_company'],
                $value['nickname_company'],
                $value['document_company'],
                $value['phone_primary_company'],
                $value['status_company']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable($this->_request->getParam('q'), $data);
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Company');
        $model = new Company();
        
        $result = $model->deleteTable($this->_request->getParam('id_company'));
            
        echo Zend_Json::encode($result);
    }
    
}
