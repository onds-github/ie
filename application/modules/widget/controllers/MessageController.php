<?php

class Support_MessageController extends Zend_Controller_Action {

    public function init() {
        $this->_initialize(1);
        if (!Zend_Auth::getInstance()->getIdentity()->id_ticket) {
            $this->_helper->redirector->gotoRoute(array('module' => 'support', 'controller' => 'ticket', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Mensagens';

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/support/js/details.js'))
                ->appendFile($this->view->baseUrl('public/modules/support/js/message.js'));
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass("Message");
        $model = new Message();

        foreach ($model->viewMessage(Zend_Auth::getInstance()->getIdentity()->id_ticket) as $value) {
            $result[] = array(
                $value['id'],
                nl2br($value['mensagem']),
                $value['data_hora'],
                $value['nome_user'],
                ($value['id_user'] == Zend_Auth::getInstance()->getIdentity()->id ? 1 : 2)
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass("Message");
        $model = new Message();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $data["id_user"] = Zend_Auth::getInstance()->getIdentity()->id;
        $data["id_ticket"] = Zend_Auth::getInstance()->getIdentity()->id_ticket;
        
        $model->insertTable($data);
        
        echo Zend_Json::encode(array(status => 'success', message => 'O registro foi salvo.'));
    }

}
