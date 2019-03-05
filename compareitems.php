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
        $this->createTables();

        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayProductPriceBlock')
            && Configuration::updateValue('MYMODULE_COMPARISON_NUMBER', 3);
    }

    public function uninstall()
    {
        /* Deletes Module */
        if (parent::uninstall()) {
            /* Deletes tables */
            $res = $this->deleteTables();

            return (bool)$res;
        }

        return false;
    }

    public function hookDisplayHeader()
    {
        echo "Viskas veikia";
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($params['type'] == 'unit_price') {
            $this->context->smarty->assign('helloWorld', 'Compare');
            return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/compareitems.tpl');
        }
    }

    protected function createTables()
    {
        /* Comparing Items*/
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'comparingitems` (
                `id_customer` int(10) unsigned NOT NULL,
                `id_product` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_customer`, `id_product`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    /**
     * deletes tables
     */
    protected function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'comparingitems`;
        ');
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $myModuleName = strval(Tools::getValue('MYMODULE_NAME'));
            $myModuleComparisonNumber = strval(Tools::getValue('MYMODULE_COMPARISON_NUMBER'));

            if (
                !$myModuleName ||
                !$myModuleComparisonNumber ||
                empty($myModuleName) ||
                empty($myModuleComparisonNumber) ||
                !Validate::isGenericName($myModuleName) ||
                !Validate::isGenericName($myModuleComparisonNumber)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('MYMODULE_NAME', $myModuleName);
                Configuration::updateValue('MYMODULE_COMPARISON_NUMBER', $myModuleComparisonNumber);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Configuration value'),
                    'name' => 'MYMODULE_NAME',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Products comparison'),
                    'name' => 'MYMODULE_COMPARISON_NUMBER',
                    'size' => 20,
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                    '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value['MYMODULE_NAME'] = Configuration::get('MYMODULE_NAME');
        $helper->fields_value['MYMODULE_COMPARISON_NUMBER'] = Configuration::get('MYMODULE_COMPARISON_NUMBER');

        return $helper->generateForm($fieldsForm);
    }
}
