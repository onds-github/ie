<?php

class Widget_StatusController extends Zend_Controller_Action {

    public function init() {
        $this->_initialize(1);
    }

    public function domainAction() {  
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Domínio';
        
        $this->view->headScript()
            ->appendFile('https://cdnjs.cloudflare.com/ajax/libs/json2html/1.3.0/json2html.min.js')
                
            ->appendFile($this->view->baseUrl('public/modules/widget/js/status.domain.js'));
    }
    
    public function websiteAction() {  
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Website';
        
        $this->view->headScript()
            ->appendFile($this->view->baseUrl('public/modules/widget/js/status.website.js'));
    }
    
    
    public function requestAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
    
        echo Zend_Json::encode(json_decode(file_get_contents('https://rdap.registro.br/domain/' . Zend_Auth::getInstance()->getIdentity()->site)));
    }
    
}
