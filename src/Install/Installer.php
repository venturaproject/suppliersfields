<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\Suppliersfields\Install;

use Db;
use Module;
use Language;

class Installer 
{

    /**
     * Déclenche l'installation du module
     * @param Module $module
     * @return bool
     */
    public function install(Module $module): bool
    {

        if (!$this->installDatabase()) {
            return false;
        }

        if (!$this->registerHooks($module)) {
            return false;
        }


        return true;
    }

    /**
     * Désinstalle le module
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->uninstallDatabase();
    }

    /**
     * Install les tables du module
     * @return bool
     */
    public function installDatabase(): bool
    {
        return $this->executeQueries(Database::installQueries());
    }

    /**
     * Désinstall les tables du module
     * @return bool
     */
    private function unInstallDatabase(): bool
    {
        return $this->executeQueries(Database::unInstallQueries());
    }


    /**
     * Accroche le module sur le hook
     * @param Module $module
     * @return bool
     */
    public function registerHooks(Module $module): bool
    {
        $hooks = [
            'actionSupplierFormBuilderModifier',
            'actionAfterUpdateSupplierFormHandler',
            'actionAfterCreateSupplierFormHandler'
        ];

        return (bool) $module->registerHook($hooks);
    }

    /**
     * Execute les requêtes SQL
     * @param array $queries
     * @return bool
     */
    private function executeQueries(array $queries): bool
    {
        if(empty($queries)) return true;

        foreach ($queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }


}