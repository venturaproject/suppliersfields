<?php

/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */


declare (strict_types = 1);

use PrestaShop\Module\Suppliersfields\Install\Installer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class Suppliersfields extends Module
{
    public function __construct()
    {
        $this->name = 'suppliersfields';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Prestashop';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('suppliersfields');
        $this->description = $this->l('suppliersfields new fields');
        $this->ps_versions_compliancy = array('min' => '1.7.8', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();
        return $installer->install($this);
    }

    public function uninstall()
    {
        $installer = new Installer();
        return $installer->uninstall() && parent::uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookActionSupplierFormBuilderModifier(array $params)
    {
        $formBuilder = $params['form_builder'];
        $supplier = new Supplier($params['id']);
    
        //  one_field configuration
        $fieldOneFiledOptions = [
            'label' => $this->getTranslator()->trans('One Field', [], 'Modules.Suppliersfields.Admin'),
            'help' => $this->getTranslator()->trans('One field help info', [], 'Modules.Suppliersfields.Admin'),
            'required' => false,
        ];
    
        // other_field configuration
        $fieldOtherFieldOptions = [
            'label' => $this->getTranslator()->trans('Other Field', [], 'Modules.Suppliersfields.Admin'),
            'help' => $this->getTranslator()->trans('Other field help info', [], 'Modules.Suppliersfields.Admin'),
            'required' => false,
        ];
    
 
        $formBuilder->add('one_field', TextType::class, $fieldOneFiledOptions);
        $formBuilder->add('other_field', TextType::class, $fieldOtherFieldOptions);
    

        $fields = $formBuilder->all();
    
        // create new array with ordered fields
        $orderedFields = [];
    

        foreach ($fields as $name => $field) {

            if ($name === 'is_enabled') {
                $orderedFields['one_field'] = $formBuilder->get('one_field');
                $orderedFields['other_field'] = $formBuilder->get('other_field');
            }
    
            $orderedFields[$name] = $field;
        }
    
        // clean ther form
        $formBuilder->setData([]);
    
  
        foreach ($fields as $name => $field) {
            $formBuilder->remove($name);
        }
    
        // add the new fields in the new sort
        foreach ($orderedFields as $name => $field) {
            $formBuilder->add($name, get_class($field->getType()->getInnerType()), $field->getOptions());
        }
    
     
        $params['data']['one_field'] = $supplier->one_field;
        $params['data']['other_field'] = $supplier->other_field;
        $formBuilder->setData($params['data']);
    }

    
    public function hookActionAfterUpdateSupplierFormHandler(array $params)
    {
        $this->updateSupplierFields($params);
    }

    public function hookActionAfterCreateSupplierFormHandler(array $params)
    {
        $this->updateSupplierFields($params);
    }

    private function updateSupplierFields(array $params)
    {
        $supplierId = $params['id'];
        $formData = $params['form_data'];

        if ($supplierId) {
            Db::getInstance()->update('supplier', [
                'one_field' => pSQL($formData['one_field']),
                'other_field' => pSQL($formData['other_field']),
            ], 'id_supplier = ' . (int)$supplierId);
        }
    }
}

