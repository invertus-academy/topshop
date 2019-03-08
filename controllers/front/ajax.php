<?php
class CompareItemsAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
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
