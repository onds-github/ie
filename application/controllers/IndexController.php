<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->redirector->gotoRoute(array('controller' => 'explore', 'action' => 'index'));
    }

    public function indexAction() {
        $this->view->page_id = '5ed1cf34bf802c19af9f8168';
        $this->view->title_page = 'Intercâmbio 360º';
        $this->view->description_page = 'A comunidade de viajantes e contadores de histórias';
        
        $this->view->headScript();
    }
    
}
