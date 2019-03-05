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

//        if (!Configuration::get('MYMODULE_NAME')) {
//            $this->warning = $this->l('No name provided.');
//        }
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayProductPriceBlock') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayShoppingCart') &&
            $this->registerHook('displayHome') &&

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
    
//    public function hookDisplayProductAdditionalInfo($params)
  //  {
//        echo "<button class=\"btn btn-primary\" data-button-action=\"add-to-comparison\" type=\"submit\">
//            Add to comparison
//          </button>";
//        if ($params['type'] == 'unit_price') {
//            $this->context->smarty->assign('button', 0);
//            return $this->context->smarty->fetch($this->getLocalPath() . 'views/templates/hook/compareitems.tpl');
//        }
        
    //}

    public function hookDisplayProductButtons()
    {
        return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/button.tpl');
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





}
