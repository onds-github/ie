<?php

class ExploreController extends Zend_Controller_Action {

    public function init() {
    
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_preview');
        
        $this->view->page_id = '1';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
    
    public function reviewsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
    
        echo Zend_Json::encode(json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?place_id=ChIJY0be9z3Xv5QRk3V4ubGfoCM&fields=review&key=AIzaSyD9rpLXC9wZovKtwvGj57jy1bo0H9SSxXI&language=pt-BR')));
    }
    
}
