<?php

class Account_ContactController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 1;
        $this->view->title_page = 'Contatos';
        $this->view->description_page = 'Gerencimento de contatos';

        $this->view->headScript()
                ->appendFile('/public/assets/addons/viacep/viacep.js')
                ->appendFile('/public/modules/account/script.contact.js');
    }

    public function existsDocumentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $result = $model->selectTableDocumentExists($this->_request->getParam('q'));

        echo Zend_Json::encode($result ? false : true);
    }
    
    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, APIURL . '/rest/v1/on_contact?company_id=eq.1&select=fullname,email,phone,on_form(form_id)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Apikey: ' . APIKEY;
        $headers[] = 'Authorization: Bearer ' . AUTHORIZATION;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch), true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        foreach ($result as $value) {
            $data[] = array(
                'contact_id' => $value['contact_id'],
                'fullname' => $value['fullname'],
                'email' => $value['email'],
                'phone' => $value['phone'],
                'created_at' => $value['created_at']
            );
        }

        echo Zend_Json::encode(array('data' => ($data == null ? [] : $data)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        #vari??veis 
        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        $data['company_id'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        #par??metros da tabela
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL . '/rest/v1/on_contact');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        #headers
        $headers = array();
        $headers[] = 'Apikey: ' . APIKEY;
        $headers[] = 'Authorization: Bearer ' . AUTHORIZATION;
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Prefer: return=representation';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        #retorno
        curl_exec($ch);
        if (curl_errno($ch)) {
            echo Zend_Json::encode(array('status' => 'warning', 'message' => CRUD_MESSAGE_ERROR, 'error' => curl_error($ch)));
        }
        curl_close($ch);
        echo Zend_Json::encode(array('status' => 'success', 'message' => CREATE_MESSAGE_SUCCESS));
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        #vari??veis 
        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        #par??metros da tabela
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL . '/rest/v1/on_contact?contact_id=eq.' . $this->_request->getParam("id"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        #headers
        $headers = array();
        $headers[] = 'Apikey: ' . APIKEY;
        $headers[] = 'Authorization: Bearer ' . AUTHORIZATION;
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Prefer: return=representation';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        #retorno
        curl_exec($ch);
        if (curl_errno($ch)) {
            echo Zend_Json::encode(array('status' => 'warning', 'message' => CRUD_MESSAGE_ERROR, error => curl_error($ch)));
        }
        curl_close($ch);
        echo Zend_Json::encode(array('status' => 'success', 'message' => UPDATE_MESSAGE_SUCCESS));
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        #par??metros da tabela
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL . '/rest/v1/on_contact?contact_id=eq.' . $this->_request->getParam("id"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        #headers
        $headers = array();
        $headers[] = 'Apikey: ' . APIKEY;
        $headers[] = 'Authorization: Bearer ' . AUTHORIZATION;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        #retorno
        curl_exec($ch);
        if (curl_errno($ch)) {
            echo Zend_Json::encode(array('status' => 'warning', 'message' => CRUD_MESSAGE_ERROR, error => curl_error($ch)));
        }
        curl_close($ch);
        echo Zend_Json::encode(array('status' => 'success', 'message' => DELETE_MESSAGE_SUCCESS));
    }

}
