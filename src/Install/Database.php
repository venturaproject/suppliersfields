<?php


declare(strict_types=1);

namespace PrestaShop\Module\Suppliersfields\Install;

use Db;

class Database 
{
    /**
     * Liste des tables à installer
     * @return array
     */
    public static function installQueries(): array
    {
        return [
            'ALTER TABLE ' . _DB_PREFIX_ . 'supplier ADD `one_field` VARCHAR(255) NOT NULL, ADD `other_field` VARCHAR(255) NOT NULL;'
        ];
    }

    /**
     * Requêtes de désinstallation
     * @return array
     */
    public static function uninstallQueries(): array
    {
        $queries = [];
        
        if (self::columnExists('supplier', 'one_field')) {
            $queries[] = 'ALTER TABLE ' . _DB_PREFIX_ . 'supplier DROP `one_field`;';
        }

        if (self::columnExists('supplier', 'other_field')) {
            $queries[] = 'ALTER TABLE ' . _DB_PREFIX_ . 'supplier DROP `other_field`;';
        }

        return $queries;
    }

    /**
     * Vérifie si une colonne existe dans une table
     * @param string $table
     * @param string $column
     * @return bool
     */
    private static function columnExists(string $table, string $column): bool
    {
        $result = Db::getInstance()->executeS('SHOW COLUMNS FROM ' . _DB_PREFIX_ . $table . ' LIKE \'' . pSQL($column) . '\'');
        return !empty($result);
    }
}