<?php

class Project_ProjectTaskChecklistController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 2;
        $this->view->title_page = 'Tarefas - Detalhes';
        $this->view->description_page = 'Controle sua tarefa e checklist';

        $this->view->headScript()
                ->appendFile('/public/modules/project/script.project-task-checklist.js');
    }

    public function printAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 2;
        $this->view->title_page = 'Orçamento';
        $this->view->description_page = 'Controle sua tarefa e checklist';

        $this->view->headScript()
                ->appendFile('/public/modules/project/script.project-task-checklist.js');
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ProjectTaskChecklist');
        $model = new ProjectTaskChecklist();

        $result = $model->selectTable($this->_request->getParam('id_project_task'), $this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ProjectTaskChecklist');
        $model = new ProjectTaskChecklist();

        foreach ($model->selectTable($this->_request->getParam('q')) as $value) {
            $result[] = array(
                $value['id_project_task_checklist'],
                $value['name_project_task_checklist'],
                $value['check_project_task_checklist'],
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ProjectTaskChecklist');
        $model = new ProjectTaskChecklist();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $data['id_project_task'] = $this->_request->getParam('q');

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ProjectTaskChecklist');
        $model = new ProjectTaskChecklist();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ProjectTaskChecklist');
        $model = new ProjectTaskChecklist();

        $result = $model->deleteTable($this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

}
