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
            $this->registerHook('displayProductListReviews') &&
            Configuration::updateValue('MYMODULE_NAME', 'compareitems');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayHeader()
    {
        echo "Viskas veikia";
    }

    public function hookDisplayProductPriceBlock()
    {
        echo "Compare";
    }

    public function hookDisplayProductListReviews()
    {
        echo "Review";
    }
}