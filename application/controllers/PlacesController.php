<?php

class PlacesController extends Zend_Controller_Action {

    public function init() {
    
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_preview');
        
        $this->view->page_id = '1';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
    
}
