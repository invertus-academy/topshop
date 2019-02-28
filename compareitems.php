<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Compareitems extends Module {

    public function __construct()
    {
        $this->name ='compareitems';
        $this->version = '1.0.0';
        $this->author = 'Topshopai';
        parent::__construct();

        $this->displayName = $this->l('Compare Items');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayProductPriceBlock') &&
            $this->registerHook('displayProductListReviews');


    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayHeader()
    {
        echo "Viskas veikia";
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($params['type'] == 'unit_price') {
            $this->context->smarty->assign('helloWorld', 0);
            return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/compareitems.tpl');
//            echo "Compare";
        }
    }

    public function hookDisplayProductListReviews()
    {
        echo "Review";
    }

    protected function createTables()
    {
        /* Comparing Items*/
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'comparingitems` (
                `id_customer` int(10) unsigned NOT NULL,
                `id_product` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_customer`, `id_item`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }
}
