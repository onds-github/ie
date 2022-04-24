<?php

class Finance_CostCenterController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_app');
        $this->view->id_module = 10;
        $this->view->title_page = 'Categorias';
        $this->view->description_page = 'Forma eficiente de agrupar despesas e receitas';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.cost-center.js');
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();

        $result = $model->selectTable($this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();

        $array = $model->selectTable();

        foreach ($array as $value) {
            $result[] = array(
                $value['id_cost_center'],
                array(
                    icon_cost_center => $value['icon_cost_center'],
                    color_cost_center => $value['color_cost_center']
                ),
                $value['name_cost_center']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $data['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();

        $result = $model->deleteTable($this->_request->getParam('q'));

        echo Zend_Json::encode($result);
    }

}
