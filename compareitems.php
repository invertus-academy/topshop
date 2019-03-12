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
        $this->controllers = ['ajax'];

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }

//    public function getContent()
//    {
//        $controllerLink = Context::getContext()->link->getAdminLink('AdminCompareItemsConfiguration');
//
//        Tools::redirectAdmin($controllerLink);
//    }

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
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayNav2') &&
            $this->registerHook('displayProductPriceBlock') &&
            Configuration::updateValue('MYMODULE_COMPARISON_NUMBER', 3) &&
            Configuration::updateValue('ENABLE_PRODUCT_COMPARE', 1) &&
            Configuration::updateValue('ENABLE_PRODUCT_COMPARE_LIST', 1) &&
            Configuration::updateValue('ENABLE_PRODUCT_COMPARE_PAGE', 1);
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayNav2()
    {
        if (!Configuration::get('ENABLE_PRODUCT_COMPARE')) {
            return;
        }

        $this->context->smarty->assign([
            'comparison_link' => $this->context->link->getModuleLink('compareitems', 'compare')
        ]);

        return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/compare_items_top_link.tpl');
    }

    public function hookDisplayProductButtons()
    {
        if (!Configuration::get('ENABLE_PRODUCT_COMPARE_PAGE') || !Configuration::get('ENABLE_PRODUCT_COMPARE')) {
            return;
        }

        $this->context->smarty->assign([
            'comparison_link' => $this->context->link->getModuleLink('compareitems', 'compare')
        ]);

        return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/button.tpl');
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (!Configuration::get('ENABLE_PRODUCT_COMPARE_LIST') || !Configuration::get('ENABLE_PRODUCT_COMPARE')) {
            return;
        }

        if ($params['type'] == 'unit_price') {
            $this->context->smarty->assign('compareButton', 'Add to Comparison');
            return $this->context->smarty->fetch($this->getLocalPath().'views/templates/hook/compareitems.tpl');
        }
    }

    public function getContent()
    {
        $output = null;
        if (Tools::isSubmit('submit'.$this->name)) {
            $myModuleComparisonNumber = strval(Tools::getValue('MYMODULE_COMPARISON_NUMBER'));
            $myModuleComparisonEnable = strval(Tools::getValue('ENABLE_PRODUCT_COMPARE'));
            $myModuleComparisonEnableList = strval(Tools::getValue('ENABLE_PRODUCT_COMPARE_LIST'));
            $myModuleComparisonEnablePage = strval(Tools::getValue('ENABLE_PRODUCT_COMPARE_PAGE'));
            if (
                !$myModuleComparisonNumber ||
                empty($myModuleComparisonNumber) ||
                !Validate::isGenericName($myModuleComparisonNumber)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('MYMODULE_COMPARISON_NUMBER', $myModuleComparisonNumber);
                Configuration::updateValue('ENABLE_PRODUCT_COMPARE', $myModuleComparisonEnable);
                Configuration::updateValue('ENABLE_PRODUCT_COMPARE_LIST', $myModuleComparisonEnableList);
                Configuration::updateValue('ENABLE_PRODUCT_COMPARE_PAGE', $myModuleComparisonEnablePage);
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
                'icon' => 'icon-cogs',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Products comparison'),
                    'name' => 'MYMODULE_COMPARISON_NUMBER',
                    'size' => 20,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l(
                        'Enable Product Compare',
                        [],
                        'Modules.Contactform.Admin'
                    ),
                    'name' => 'ENABLE_PRODUCT_COMPARE',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'ENABLE_PRODUCT_COMPARE' . '_on',
                            'value' => 1,
                            'label' => $this->l('Enabled', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'ENABLE_PRODUCT_COMPARE' . '_off',
                            'value' => 0,
                            'label' => $this->l('Disabled', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l(
                        'Show product compare at product list',
                        [],
                        'Modules.Contactform.Admin'
                    ),
                    'name' => 'ENABLE_PRODUCT_COMPARE_LIST',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'ENABLE_PRODUCT_COMPARE_LIST' . '_on',
                            'value' => 1,
                            'label' => $this->l('Enabled', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'ENABLE_PRODUCT_COMPARE_LIST' . '_off',
                            'value' => 0,
                            'label' => $this->l('Disabled', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l(
                        'Show product compare at product page',
                        [],
                        'Modules.CompareItems.Admin'
                    ),
                    'name' => 'ENABLE_PRODUCT_COMPARE_PAGE',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'ENABLE_PRODUCT_COMPARE_PAGE' . '_on',
                            'value' => 1,
                            'label' => $this->l('Enabled', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'ENABLE_PRODUCT_COMPARE_LIST' . '_off',
                            'value' => 0,
                            'label' => $this->l('Disabled', [], 'Admin.Global')
                        ]
                    ]
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
        $helper->fields_value['MYMODULE_COMPARISON_NUMBER'] = Configuration::get('MYMODULE_COMPARISON_NUMBER');
        $helper->fields_value['ENABLE_PRODUCT_COMPARE'] = Configuration::get('ENABLE_PRODUCT_COMPARE');
        $helper->fields_value['ENABLE_PRODUCT_COMPARE_LIST'] = Configuration::get('ENABLE_PRODUCT_COMPARE_LIST');
        $helper->fields_value['ENABLE_PRODUCT_COMPARE_PAGE'] = Configuration::get('ENABLE_PRODUCT_COMPARE_PAGE');
        return $helper->generateForm($fieldsForm);
    }
}
