<?php

class TraveltellersController extends Zend_Controller_Action {

    public function init() {
    
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_preview');
        $this->view->page_id = '5ed56fc82b0a2a0d6bdb92d2';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
    
}
