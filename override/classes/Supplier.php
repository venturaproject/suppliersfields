<?php

class Supplier extends SupplierCore
{
    public $one_field;
    public $other_field;

    public function __construct($id = null, $id_lang = null)
    {
        self::$definition['fields']['one_field'] = array(
            'type' => self::TYPE_STRING,
            'validate' => 'isGenericName',
        );
        self::$definition['fields']['other_field'] = array(
            'type' => self::TYPE_STRING,
            'validate' => 'isGenericName',
        );

        parent::__construct($id, $id_lang);
    }
}
