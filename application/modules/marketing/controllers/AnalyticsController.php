<?php

class Marketing_AnalyticsController extends Zend_Controller_Action {

    public function init() {
        // $this->_initialize(1);
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Análise';

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/library/highcharts/highcharts.js'))
                ->appendFile($this->view->baseUrl('public/library/highcharts/data.js'))
                ->appendFile($this->view->baseUrl('public/library/highcharts/exporting.js'))
                ->appendFile($this->view->baseUrl('public/library/highcharts/export-data.js'))
                ->appendFile($this->view->baseUrl('public/library/highcharts/accessibility.js'))
                
                ->appendFile($this->view->baseUrl('public/modules/project/js/details.js'))
                ->appendFile($this->view->baseUrl('public/modules/marketing/js/analytics.js'));
    }
    
    public function highchartsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Analytics');
        $model = new Analytics();   
        
        foreach ($model->viewAnalyticsHighcharts(Zend_Auth::getInstance()->getIdentity()->id_project) as $value) {
            $result[] = array(datatime => $value['dia'], visitas => $value['visitas']);
        }  

        echo Zend_Json::encode($result);   
    }
    
    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Analytics');
        $model = new Analytics();   
        
        foreach ($model->viewAnalytics(null, 1) as $value) {
            $result[] = array(
                $value['id'],
                $value['cookie_key'],
                $value['city'] . ' - ' . $value['state'],
                $value['data_hora']
            );
        }  
        
        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));   
    }
    
    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        header('Access-Control-Allow-Origin: *');
        Zend_Loader::loadClass('Analytics');
        $model = new Analytics();

        $localize = $this->localize($_SERVER['REMOTE_ADDR']);
        $array = array(
            device => $_SERVER['HTTP_USER_AGENT'],
            url => $this->_request->getParam("server_name"),
            ip => $localize->ip,
            country => $localize->country_code,
            state => $localize->region_code,
            city => $localize->city,
            long_lat => $localize->latitude . ',' . $localize->longitude,
            cookie_key => $this->_request->getParam('cookie_key')
        );

        $model->insertTable($array);
        
        echo Zend_Json::encode(array(result => $result));
    }
    
    private function localize($remote_addr) {
        $details = json_decode(file_get_contents("http://api.ipstack.com/{$remote_addr}?access_key=dbd38f82b38e5cc2e26988cc58631c45"));
        return $details;
    }
    
    private function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

}
