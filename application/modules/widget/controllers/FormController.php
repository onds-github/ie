<?php

class Widget_FormController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        header('Access-Control-Allow-Origin: *');
        
        Zend_Loader::loadClass('Form');
        $model = new Form();  
        
        $result = $model->viewForm($this->_request->getParam('id_form'));
        
        foreach ($result as $value) {
            $data[] = array(
                id_project => $value['id_project'],
                fields => json_decode($value['fields'], false),
                validate => json_decode($value['validate'], false)
            );
        }
        
        echo Zend_Json::encode($result ? array(status => 'success', results => $data) : array(status => 'danger', results => 'Erro desconhecido'));   
    }
   
}
