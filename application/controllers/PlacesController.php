<?php

class PlacesController extends Zend_Controller_Action {

    public function init() {
    
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_preview');
        $this->view->page_id = '5ed56fd4e5322a6a6865e18e';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
    
    public function placeAction() {
        $this->_helper->layout->setLayout('layout_preview');
        $this->view->page_id = '5ed44447a0977f3f1e4c8ef5';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
   
}
