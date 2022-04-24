<?php

class Account_PromotionController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 0;
        $this->view->title_page = 'Visão Geral';
        $this->view->description_page = 'Resumo e gráficos';

        $this->view->headScript()
                ->appendFile('https://code.highcharts.com/highcharts.js')
                ->appendFile('https://code.highcharts.com/modules/series-label.js')
                ->appendFile('https://code.highcharts.com/modules/exporting.js')
                ->appendFile('https://code.highcharts.com/modules/export-data.js')
                ->appendFile('https://code.highcharts.com/modules/accessibility.js')
                ->appendFile('https://underscorejs.org/underscore-min.js')
                ->appendFile('https://code.highcharts.com/themes/grid-light.js')
               
                ->appendFile('/public/modules/account/script.dashboard.js?v=' . time())
        ;
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Dashboard');
        $model = new Dashboard();

        $result['total_accounts'] = number_format($model->selectTotalDashboard()[0]['total_accounts'], 2, ',', '.');

        echo Zend_Json::encode($result);
    }

    public function selectInOutAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Dashboard');
        $model = new Dashboard();

        for ($index = 12; $index > 0; $index--) {

            $year = date("Y", strtotime("-" . $index . " month", strtotime(date('Y-m-d'))));
            $month = date("m", strtotime("-" . $index . " month", strtotime(date('Y-m-d'))));

            $total_order_in;
            $total_order_out;
            foreach ($model->viewInOutDashboard($year, $month) as $value) {
                $total_order_in = $value['total_order_in'] ?: 0;
                $total_order_out = $value['total_order_out'] ?: 0;
            }

            $in[] = floatval($total_order_in);
            
            $out[] = floatval($total_order_out);
            
            $months[] = $month . '/' . $year;
        }

        $result[] = array(
            name => 'Receitas',
            data => $in,
            color => "#740aea"
        );

        $result[] = array(
            name => 'Despesas',
            data => $out,
            color => "#db2828"
        );

        echo Zend_Json::encode(array(data => $result, months => $months));
    }

    public function selectProjectTaskStatusAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Dashboard');
        $model = new Dashboard();

        $array = $model->selectProjectTaskStatusDashboard();

        foreach ($array as $value) {
            $result[] = array(
                $value['id_project_task_status'],
                $value['name_project_task_status'],
                $value['total_project_task']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function selectAccountAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Dashboard');
        $model = new Dashboard();

        $array = $model->selectAccountDashboard();

        foreach ($array as $value) {
            $result[] = array(
                $value['id_account'],
                $value['name_account'],
                number_format($value['total_order'], 2, ',', '.'),
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function ajaxCostCenterAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Dashboard');
        $model = new Dashboard();

        foreach ($model->selectCostCenterDashboard() as $value) {
            $result[] = array(
                $value['id_cost_center'],
                array(
                    icon_cost_center => $value['icon_cost_center'],
                    color_cost_center => $value['color_cost_center']
                ),
                $value['name_cost_center'],
                number_format($value['total_order'], 2, ',', '.')
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

}
