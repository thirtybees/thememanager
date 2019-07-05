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

if (!defined('_TB_VERSION_'))
    exit;

class ThemeManager extends Module
{

    /**
     * ThemeManager constructor.
     *
     * @throws PrestaShopException
     */
    public function __construct()
    {
        $this->name = 'thememanager';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'thirty bees';
        $this->tb_min_version = '1.0.0';
        $this->tb_versions_compliancy = '> 1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Template Manager');
        $this->description = $this->l('Lets you choose different templates for different pages');
        $this->confirmUninstall = $this->l('Are you sure you want to delete this module?');
    }

    /**
     * Module installation
     *
     * @param bool $createTables
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function install($createTables = true)
    {
        if (!parent::install() OR
            !$this->createDatabase($createTables) OR
            !$this->registerHook('displayOverrideTemplate') OR
            !$this->registerHook('displayAdminCmsContentForm') OR
            !$this->registerHook('displayAdminProductsExtra') OR
            !$this->registerHook('displayBackOfficeCategory') OR
            !$this->registerHook('actionProductUpdate') OR
            !$this->registerHook('actionObjectCmsUpdateAfter') OR
            !$this->registerHook('categoryUpdate')
        )
            return false;
        return true;
    }

    /**
     * Module uninstallation
     *
     * @param bool $removeTables
     *
     * @return bool
     */
    public function uninstall($removeTables = true)
    {
        if (!parent::uninstall() OR
            !$this->removeDatabase($removeTables)
        )
            return false;
        return true;
    }

    /**
     * Module soft reset
     *
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function reset()
    {
        return $this->uninstall(false) && $this->install(false);
    }

    /**
     * Hook used to extend category form
     *
     * @return string
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function hookDisplayBackOfficeCategory()
    {
        return $this->displayTemplateForm('category');
    }

    /**
     * Hook called when category is saved
     *
     * @param $params
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookCategoryUpdate($params)
    {
        if (Tools::isSubmit('product_template')) {
            $this->updateTemplate('category', (int)$params['category']->id, Tools::getValue('product_template'));
        }
    }

    /**
     * Hook used to display extra tab on product page
     *
     * @return string
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function hookDisplayAdminProductsExtra()
    {
        return $this->displayTemplateForm('product');
    }

    /**
     * Hook called when product is saved
     *
     * @param $params
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookActionProductUpdate($params)
    {
        if (Tools::isSubmit('product_template')) {
            $this->updateTemplate('product', (int)$params['id_product'], Tools::getValue('product_template'));
        }
    }

    /**
     * Hook used to extend CMS form
     *
     * @return string
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function hookDisplayAdminCmsContentForm()
    {
        return $this->displayTemplateForm('cms');
    }

    /**
     * Hook called when CMS object is saved
     *
     * @param $params
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookActionObjectCmsUpdateAfter($params)
    {
        if (Tools::isSubmit('product_template')) {
            $this->updateTemplate('cms', (int)$params['object']->id, Tools::getValue('product_template'));
        }
    }

    /**
     * This hook is called to retrieve override template for given controller
     *
     * @param array $params
     * @return string
     * @throws PrestaShopException
     */
    public function hookDisplayOverrideTemplate($params)
    {
        $modifiedPages = ['product', 'category', 'cms'];

        if (isset($params['controller']->php_self)) {
            $controller = $params['controller']->php_self;

            if (in_array($controller, $modifiedPages)) {
                // try to get a specific product template
                $template = $this->getCustomTemplate($controller, (int)Tools::getValue('id_' . $controller));
                if ($template) {
                    $path = _PS_THEME_DIR_ . '/templates/' . $controller . '/' . $template . '.tpl';
                    if (file_exists($path)) {
                        return $path;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Retrieves template associated with given entity
     *
     * @param string $entityType
     * @param int $entityId
     * @return string
     * @throws PrestaShopException
     */
    private function getCustomTemplate($entityType, $entityId)
    {
        if ($entityId) {
            $query = (new DbQuery())
                ->select('template')
                ->from('thememanager_template')
                ->where('entity_type = \'' . pSQL($entityType) . '\'')
                ->where('id_entity = ' . (int)$entityId);
            return Db::getInstance()->getValue($query);
        }
        return null;
    }

    /**
     * Saves information about associated template
     *
     * @param string $entityType type of entity (product, cms,...)
     * @param int $entityId unique id of entity
     * @param string $template selected template
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function updateTemplate($entityType, $entityId, $template)
    {
        if ($template) {
            $insertData = [
                'entity_type' => $entityType,
                'id_entity' => (int)$entityId,
                'template' => $template,
            ];
            Db::getInstance()->insert('thememanager_template', $insertData, false, true, Db::ON_DUPLICATE_KEY);
        } else {
            Db::getInstance()->delete('thememanager_template', 'entity_type = \'' . pSQL($entityType) . '\' AND id_entity = ' . (int)$entityId);
        }
    }

    /**
     * Displays template form
     *
     * @param string $entityType
     * @return string
     * @throws PrestaShopException
     * @throws SmartyException
     */
    private function displayTemplateForm($entityType)
    {
        $templates = [];
        $directory = _PS_THEME_DIR_ . '/templates/' . $entityType;
        if (is_dir($directory)) {
            $files = @scandir($directory);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $templates[] = str_replace('.tpl', '', $file);
                }
            }
        }

        $this->context->smarty->assign([
            'entity' => $entityType,
            'chosen_template' => $this->getCustomTemplate($entityType, (int)Tools::getValue('id_' . $entityType)),
            'product_templates' => $templates
        ]);

        return $this->display(__FILE__, 'templateSelectForm.tpl');
    }

    /**
     * Creates database tables required by this module
     *
     * @param bool $create true if database tables should be created
     * @return bool
     */
    public function createDatabase($create)
    {
        if ($create) {
            return $this->executeSqlScript('install');
        }
        return true;
    }

    /**
     * Removes database tables
     *
     * @param bool $remove true if database tables should be dropped
     * @return bool
     */
    private function removeDatabase($remove)
    {
        if ($remove) {
            return $this->executeSqlScript('uninstall');
        }
        return true;
    }

    /**
     * Executes sql script from sql directory
     *
     * @param string $script sql script name to run
     * @return bool
     */
    private function executeSqlScript($script)
    {
        $file = dirname(__FILE__) . '/sql/' . $script . '.sql';
        if (!file_exists($file)) {
            return false;
        }
        $sql = file_get_contents($file);
        if (!$sql) {
            return false;
        }
        $sql = str_replace(['PREFIX_', 'ENGINE_TYPE', 'CHARSET_TYPE'], [_DB_PREFIX_, _MYSQL_ENGINE_, 'utf8mb4'], $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);
        foreach ($sql as $statement) {
            $stmt = trim($statement);
            if ($stmt) {
                try {
                    if (!Db::getInstance()->execute($stmt)) {
                        return false;
                    }
                } catch (Exception $e) {
                    return false;
                }
            }
        }
        return true;
    }
}
