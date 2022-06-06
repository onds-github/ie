<?php

class SignUpController extends Zend_Controller_Action {

    public function init() {
    
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_admin');
        $this->view->page_id = '5ed56fd4e5322a6a6865e18e';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
    
}
