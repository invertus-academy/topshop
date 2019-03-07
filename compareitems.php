<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class CompareItems extends Module {

    public function __construct()
    {
        $this->name ='compareitems';
        $this->version = '1.0.0';
        $this->author = 'Topshopai';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Compare Items');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }

    public function getContent()
    {
        $controllerLink = Context::getContext()->link->getAdminLink('AdminCompareItemsConfiguration');

        Tools::redirectAdmin($controllerLink);
    }

    public function getTabs()
    {
        return [
            [
                'name' => 'compareitems',
                'parent_class_name' => 'AdminParentModulesSf',
                'class_name' => 'AdminCompareItemsParent',
                'visible' => false,
            ],
            [
                'name' => 'Configuration',
                'parent_class_name' => 'AdminCompareItemsParent',
                'class_name' => 'AdminCompareItemsConfiguration',
            ]
        ];
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayProductPriceBlock');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($params['type'] == 'unit_price') {
            $this->context->smarty->assign('helloWorld', 'Compare');
            return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/compareitems.tpl');
        }
    }
}
