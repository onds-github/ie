<?php

class Project_IndexController extends Zend_Controller_Action {

    protected $_table = "on_project";
    protected $_primaryKey = "project_id";

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array("module" => "account", "controller" => "access", "action" => "index"));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout("layout_webapp");
        $this->view->id_module = 3;
        $this->view->title_page = "Projetos";
        $this->view->description_page = "Gerenciamento de projetos";
        
        $this->view->headScript()
                ->appendFile("/public/modules/project/script.index.js?v=" . time());
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, APIURL . "/rest/v1/{$this->_table}?{$this->_primaryKey}=eq.1&select=project_id,project_name,created_at");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Apikey: " . APIKEY;
        $headers[] = "Authorization: Bearer " . AUTHORIZATION;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $data = json_decode(curl_exec($ch), true);
        if (curl_errno($ch)) {
            echo "Error:" . curl_error($ch);
        }
        curl_close($ch);

        echo Zend_Json::encode(array("data" => ($data == null ? [] : $data)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");
        
        #variáveis 
        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        #parâmetros da tabela
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL . "/rest/v1/{$this->_table}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        #headers
        $headers = array();
        $headers[] = "Apikey: " . APIKEY;
        $headers[] = "Authorization: Bearer " . AUTHORIZATION;
        $headers[] = "Content-Type: application/json";
        $headers[] = "Prefer: return=representation";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        #retorno
        curl_exec($ch);
        if (curl_errno($ch)) {
            echo Zend_Json::encode(array("status" => "warning", "message" => CRUD_MESSAGE_ERROR, "error" => curl_error($ch)));
        }
        curl_close($ch);
        echo Zend_Json::encode(array("status" => "success", "message" => CREATE_MESSAGE_SUCCESS));
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        #variáveis 
        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        #parâmetros da tabela
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL . "/rest/v1/{$this->_table}?{$this->_primaryKey}=eq." . $this->_request->getParam("id"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        #headers
        $headers = array();
        $headers[] = "Apikey: " . APIKEY;
        $headers[] = "Authorization: Bearer " . AUTHORIZATION;
        $headers[] = "Content-Type: application/json";
        $headers[] = "Prefer: return=representation";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        #retorno
        curl_exec($ch);
        if (curl_errno($ch)) {
            echo Zend_Json::encode(array("status" => "warning", "message" => CRUD_MESSAGE_ERROR, 'error' => curl_error($ch)));
        }
        curl_close($ch);
        echo Zend_Json::encode(array("status" => "success", "message" => UPDATE_MESSAGE_SUCCESS));
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        #parâmetros da tabela
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL . "/rest/v1/{$this->_table}?{$this->_primaryKey}=eq." . $this->_request->getParam("id"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        #headers
        $headers = array();
        $headers[] = "Apikey: " . APIKEY;
        $headers[] = "Authorization: Bearer " . AUTHORIZATION;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        #retorno
        curl_exec($ch);
        if (curl_errno($ch)) {
            echo Zend_Json::encode(array("status" => "warning", "message" => CRUD_MESSAGE_ERROR, "error" => curl_error($ch)));
        }
        curl_close($ch);
        echo Zend_Json::encode(array("status" => "success", "message" => DELETE_MESSAGE_SUCCESS));
    }

}
