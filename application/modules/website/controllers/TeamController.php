<?php

class Website_TeamController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 3;
        $this->view->title_page = 'Team';
        $this->view->description_page = 'Gerenciamento de Equipe';
        
        $this->view->headScript()
                ->appendFile('/public/modules/website/script.team.js');
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

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Team');
        $model = new Team();

        $result = $model->selectTable($this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Team');
        $model = new Team();

        foreach ($model->selectTable() as $value) {
            $result[] = array(
                $value['id_team'],
                $value['title_team'],
                $value['description_team']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Team');
        $model = new Team();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->insertTable($data);
        
        echo Zend_Json::encode($result);
    }
    
    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Team');
        $model = new Team();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->updateTable($this->_request->getParam('q'), $data);
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Team');
        $model = new Team();
        
        $result = $model->deleteTable($this->_request->getParam('q'));
            
        echo Zend_Json::encode($result);
    }
    
}
