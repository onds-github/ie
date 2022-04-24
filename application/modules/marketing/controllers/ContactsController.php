<?php

class Marketing_ContactsController extends Zend_Controller_Action {

    public function init() {
        // $this->_initialize(1);
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Leads';

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/project/js/details.js'))
                ->appendFile($this->view->baseUrl('public/modules/marketing/js/contacts.js'));
    }
    
    public function detailsAction() {
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Detalhes';

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/project/js/details.js'))
                ->appendFile($this->view->baseUrl('public/modules/marketing/js/contacts.details.js'));
    }
    
    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();   
        
        $result = $model->viewContacts(Zend_Auth::getInstance()->getIdentity()->id_contacts, Zend_Auth::getInstance()->getIdentity()->id_project);

        echo Zend_Json::encode($result);   
    }
    
    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();   
        
        foreach ($model->viewContacts(null, Zend_Auth::getInstance()->getIdentity()->id_project) as $value) {
            $result[] = array(
                $value['id'],
                $value['nome_sobrenome'],
                $value['data_hora'],
                $value['tipo']
            );
        }  

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));   
    }
    
    public function messageAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('ContactsMessage');
        $model = new ContactsMessage();   
        
        foreach ($model->viewContactsMessage(null, Zend_Auth::getInstance()->getIdentity()->id_contacts) as $value) {
            $result[] = array(
                $value['id'],
                $value['mensagem'],
                $value['data_hora']
            );
        }  
        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }
    
    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('ContactsMessage');
        $model = new ContactsMessage();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $data['id_contacts'] = Zend_Auth::getInstance()->getIdentity()->id_contacts;
        
        $info = $this->_infoProject(Zend_Auth::getInstance()->getIdentity()->id_contacts);
        
        $mail = array(
            subject => $info['nome_sobrenome'] . ' respondeu sua conversa de lead',
            setFromName => $info['nome_sobrenome'],
            addToEmail => 'suporte@onds.com.br',
            addToName => 'Equipe ' . $info['nome_fantasia'],
            replace => array($info['nome_fantasia'], $info['site'], 'Equipe ' . $info['nome_fantasia'], 'Você recebeu uma mensagem... Para visualizar acesse: <a href="https://www.onds.com.br/widget/contacts?q=' . Zend_Auth::getInstance()->getIdentity()->id_contacts . '">clique aqui</a>'),
        );
        
        $this->_mail($mail);

        $model->insertTable($data);

        echo Zend_Json::encode(array(status => 'success', message => 'Sua mensagem foi enviada com sucesso, aguarde nosso contato...'));
    }
    
    public function _infoProject($id_contacts = null) {
        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();   
        
        return $model->viewContactsWidget($id_contacts, null)[0];
    }
    
    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();
        
        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_contacts, $data);
        
        echo Zend_Json::encode(array(status => 'success', message => 'O registro foi atualizado'));
    }
    public function startAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();
        
        $result = $model->updateTable($this->_request->getParam('q'), array(id_user => Zend_Auth::getInstance()->getIdentity()->id));
        
        Zend_Auth::getInstance()->getIdentity()->id_contacts = $this->_request->getParam('q');

        echo Zend_Json::encode('Contato Selecionado');
    }

    public function continueAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Auth::getInstance()->getIdentity()->id_contacts = $this->_request->getParam('q');

        echo Zend_Json::encode('Contato Selecionado');
    }

}
