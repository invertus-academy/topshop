<?php




class CompareItemsCompareModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function init()
    {
        parent::init();
    }
    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign(array());
        $this->setTemplate('module:compareitems/views/templates/front/compare.tpl');
    }
}