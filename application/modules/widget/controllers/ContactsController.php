<?php

class Widget_ContactsController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_widget');
        $this->view->title = 'Conversa da Lead';

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/widget/js/contacts.js'));
    }
    
    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();   
        
        echo Zend_Json::encode($model->viewContactsWidget($this->_request->getParam('q'), null));
    }
    
    public function messageAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('ContactsMessage');
        $model = new ContactsMessage();   
        
        foreach ($model->viewContactsMessage(null, Zend_Auth::getInstance()->getIdentity()->id_contacts) as $value) {
            $result[] = array(
                $value['id'],
                $value['mensagem'],
                $value['data_hora']
            );
        }  
        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }
    
    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('ContactsMessage');
        $model = new ContactsMessage();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $data['id_contacts'] = Zend_Auth::getInstance()->getIdentity()->id_contacts;
        $data['id_user'] = Zend_Auth::getInstance()->getIdentity()->id;
        
        $info = $this->_infoProject($this->_request->getParam('q'));
        
        $mail = array(
            subject => 'Equipe ' . $info['Equipe'] . ' respondeu seu contato',
            setFromName => $info['Equipe'] . $info['nome_fantasia'],
            addToEmail => $info['email'],
            addToName => $info['nome_sobrenome'],
            replace => array($info['nome_fantasia'], $info['site'], 'Equipe ' . $info['nome_fantasia'], 'Você recebeu uma mensagem...'),
        );
        
        $this->_mail($mail);

        $model->insertTable($data);

        echo Zend_Json::encode(array(status => 'success', message => 'Sua mensagem foi enviada com sucesso, aguarde nosso contato...'));
    }
    
    public function _infoProject($id_contacts = null) {
        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();   
        
        return $model->viewContactsWidget($id_contacts, null)[0];
    }
    
    public function leadAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        header('Access-Control-Allow-Origin: *');
        
        Zend_Loader::loadClass('Contacts');
        $model = new Contacts();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $array = array(
            cookie_key => $this->_request->getParam('cookie_key'),
            id_project => $this->_request->getParam('id_project'),
            id_form => $this->_request->getParam('id_form'),
            fields => json_encode($data)
        );

        $model->insertTable($array);
        
        $form = $model->viewFormLeadSelected($this->_request->getParam('id_form'))[0];
        
        foreach ($data as $key => $value) {
            $arr.= '<b>' . str_replace('_', ' ', $key) . '</b>' . '<br />'. $value . '<br />';
        }

        $mail = array(
            subject => $form['title'],
            setFromName => $data['nome'],
            addToEmail => $form['addtoemail'],
            addToName => $form['addtoname'],
            setReplyToEmail => $data['e-mail'],
            setReplyToName => $data['nome'],
            setHtml => array(
                header => '<b>Empresa:</b> ' . $form['nome_fantasia'] . '<br /><b>Site:</b> ' . $form['site'],
                body => $arr,
                title => $form['title'],
                description => $form['description'],
                color => $form['color']
            )
        );
        
        $this->_mail($mail);

        echo Zend_Json::encode(array(status => 'success', message => 'Sua mensagem foi enviada com sucesso, aguarde nosso contato...'));
    }
    
}
