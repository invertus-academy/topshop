<?php
class CompareItemsAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign(array(
            'number_of_product' => Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'product`'),
            'categories' => Db::getInstance()->executeS('SELECT `id_category`, `name` FROM `'._DB_PREFIX_.'category_lang` WHERE `id_lang`='.(int) $this->context->language->id),
            'shop_name' => Configuration::get('PS_SHOP_NAME'),
            'manufacturer' => Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'manufacturer`'),
            'products' => Db::getInstance()->executeS('SELECT p.`id_product`, p.`price`, pl.`name` FROM `ps_product` p INNER JOIN `ps_product_lang` pl ON (pl.`id_lang` = 1 AND pl.`id_product` = p.`id_product`)'),
        ));

        $this->setTemplate('module:compareitems/views/templates/front/page.tpl');
    }
    public function postProcess()
    {
        parent::postProcess();
    }
    public function setMedia()
    {
        parent::setMedia();
        $this->registerStylesheet(
            'my-stylesheet',
            'modules/'.$this->module->name.'/views/css/ajax.css'
        );
        $this->registerJavascript(
            'my-Javascript',
            'modules/'.$this->module->name.'/views/js/ajax.js'
        );
    }
}
