<?php
/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */


declare(strict_types=1);

namespace PrestaShop\Module\Suppliersfields\Install;

use Db;

class Database 
{
    /**
     * List of queries to install tables
     * @return array
     */
    public static function installQueries(): array
    {
        return [
            'ALTER TABLE ' . _DB_PREFIX_ . 'supplier ADD `one_field` VARCHAR(255) NOT NULL, ADD `other_field` VARCHAR(255) NOT NULL;'
        ];
    }

    /**
     * Uninstall queries
     * @return array
     */
    public static function uninstallQueries(): array
    {
        $queries = [];
        
        // Check if 'one_field' exists and add drop query if it does
        if (self::columnExists('supplier', 'one_field')) {
            $queries[] = 'ALTER TABLE ' . _DB_PREFIX_ . 'supplier DROP `one_field`;';
        }

        // Check if 'other_field' exists and add drop query if it does
        if (self::columnExists('supplier', 'other_field')) {
            $queries[] = 'ALTER TABLE ' . _DB_PREFIX_ . 'supplier DROP `other_field`;';
        }

        return $queries;
    }

    /**
     * Checks if a column exists in a table
     * @param string $table
     * @param string $column
     * @return bool
     */
    private static function columnExists(string $table, string $column): bool
    {
        // Execute SHOW COLUMNS query to check for existence of column
        $result = Db::getInstance()->executeS('SHOW COLUMNS FROM ' . _DB_PREFIX_ . $table . ' LIKE \'' . pSQL($column) . '\'');
        
        // Return true if result is not empty, indicating column exists
        return !empty($result);
    }
}
