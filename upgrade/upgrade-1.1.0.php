<?php
/**
 * Copyright (C) 2019 thirty bees
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@thirtybees.com so we can send you a copy immediately.
 *
 * @author    thirty bees <modules@thirtybees.com>
 * @copyright 2019 thirty bees
 * @license   Academic Free License (AFL 3.0)
 */

if (!defined('_TB_VERSION_')) {
    exit;
}

/**
 * Upgrade from 1.0.0 to 1.1.0
 *
 * We need to
 *   1) create new database table
 *   2) populate it with data from altered core tables
 *   3) remove extra columns from core tables
 *
 * @return bool
 * @throws Adapter_Exception
 * @throws PrestaShopDatabaseException
 * @throws PrestaShopException
 */
function upgrade_module_1_1_0()
{
    /** @var ThemeManager $module */
    $module = Module::getInstanceByName('thememanager');
    if ($module) {

        // 1. create database table
        if (! $module->createDatabase(true)) {
            return false;
        }

        // 2. populate new table
        Db::getInstance()->execute("INSERT IGNORE INTO "._DB_PREFIX_."thememanager_template(entity_type, id_entity, template) SELECT 'product', id_product, template FROM "._DB_PREFIX_."product WHERE NULLIF(template, '') IS NOT NULL");
        Db::getInstance()->execute("INSERT IGNORE INTO "._DB_PREFIX_."thememanager_template(entity_type, id_entity, template) SELECT 'category', id_category, template FROM "._DB_PREFIX_."category WHERE NULLIF(template, '') IS NOT NULL");
        Db::getInstance()->execute("INSERT IGNORE INTO "._DB_PREFIX_."thememanager_template(entity_type, id_entity, template) SELECT 'cms', id_cms, template FROM "._DB_PREFIX_."cms WHERE NULLIF(template, '') IS NOT NULL");

        // 3. drop extra columns
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'category DROP COLUMN `template`');
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product DROP COLUMN `template`');
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'cms DROP COLUMN `template`');

        return true;
    }
    return false;
}
