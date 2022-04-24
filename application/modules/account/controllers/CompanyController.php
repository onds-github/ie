<?php

class Account_CompanyController extends Zend_Controller_Action {

    public function init() {
        $this->_permission(6);
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 6;
        $this->view->title_page = 'Info. da Empresa';
        $this->view->description_page = 'Gerenciamento de informações';
        
        $this->view->headScript()
                ->appendFile('/public/modules/account/company/script.default.js');
        
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();
        
        $result = $model->viewCompany(Zend_Auth::getInstance()->getIdentity()->id_company_session);

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

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_company_session, $data);
        
        if ($result['status'] == 'success') {
            Zend_Auth::getInstance()->getIdentity()->name_company = $data['name_company'];
        }
        
        echo Zend_Json::encode($result);
    }

    public function permissionsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $result['check_juno_company'] = Zend_Auth::getInstance()->getIdentity()->check_juno_company;

        echo Zend_Json::encode($result);
    }

}
