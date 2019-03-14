<?php

class CompareItemsCompareModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    private function getProductsFromSql()
    {
        $query = new DbQuery();
        $query
            ->select('p.*, product_shop.*, pl.`name`, pl.`link_rewrite`, i.`id_image`, i.`cover`')
            ->from('product', 'p')
            ->innerJoin(
                'product_lang',
                'pl',
                'pl.`id_product`=p.`id_product` AND pl.`id_lang` = ' . $this->context->language->id .
                ' AND pl.`id_shop`=' . $this->context->shop->id
            )
            ->leftJoin(
                'image_shop',
                'i',
                'i.`id_product` = p.`id_product` AND i.`cover` = 1 AND i.`id_shop` = ' . (int)$this->context->shop->id
            )
            ->join(Shop::addSqlAssociation('product', 'p'));

        return Db::getInstance()->executeS($query);
    }

    public function initContent()
    {
        parent::initContent();

        $productsFromSql = $this->getProductsFromSql();

        $productsReadyFormTemplate = [];

        foreach ($productsFromSql as $item) {
            $productsReadyFormTemplate[] = Product::getProductProperties($this->context->language->id, $item);
        }

        $this->addColorsToProductList($productsReadyFormTemplate);

        $this->context->smarty->assign(array(
            'products' => $productsReadyFormTemplate,
            'productIds' => json_decode($this->context->cookie->productIds),
        ));

        $this->setTemplate('module:compareitems/views/templates/front/compare.tpl');
    }

    public function postProcess()
    {
        //todo: how to save to cookie sample
        if (Tools::isSubmit('saveProduct')) {
            if (!isset($this->context->cookie->productIds)) {
                $productIds = [];
            } else {
                $productIds = json_decode($this->context->cookie->productIds);
            }
            $productId = Tools::getValue('id_product_compare');
            $productIds[] = $productId;
            $this->context->cookie->productIds = json_encode($productIds);
            $this->context->cookie->write();
        }
    }
}
