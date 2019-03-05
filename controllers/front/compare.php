<?php




class CompareitemsCompareModuleFrontController extends ModuleFrontController
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
        $this->context->smarty->assign(array(
            'nb_product'=> Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'product`'),
            'categories'=> Db::getInstance()->executeS('SELECT `name` FROM `'._DB_PREFIX_.'category_lang` WHERE `id_lang` = ' .(int)$this->context->language->id),
            'shop_name'=>Configuration::get('PS_SHOP_NAME'),
            'manufacturer'=>Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'manufacturer`')

        ));
        $this->setTemplate('module:compareitems/views/templates/front/compare.tpl');
    }
}