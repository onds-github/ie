<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Zend_View_Helper_Menu extends Zend_View_Helper_Abstract {

    public function menu($menu = null, $type = null) {
        Zend_Loader::loadClass("Menu");
        $model = new Menu();

        return $model->itens($menu, $type);
    }

}
