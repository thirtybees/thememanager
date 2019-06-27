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

	protected $_errors = array();
	protected $_html = '';

	/* Set default configuration values here */
	protected $_config = array(
		'PSCHOOSEPRODUCTTEMPLATE' => '',
		'PSCHOOSECATEGORYTEMPLATE' => '',
		);


	public function __construct()
	{
		$this->name = 'thememanager';
		$this->tab = 'front_office_features';
		$this->version = '1.0.2';
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
	
	public function install()
	{
		if (!parent::install() OR
			!$this->alterTable() OR
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
	
	public function uninstall()
	{
		if (!parent::uninstall() OR
			!$this->alterTable('remove')
			)
			return false;
		return true;
	}

	public function alterTable($method = 'add')
	{
		if($method == 'add')
		{
			$sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'category ADD `template` VARCHAR(64) NOT NULL';
			$sql2 = 'ALTER TABLE ' . _DB_PREFIX_ . 'product ADD `template` VARCHAR(64) NOT NULL';
			$sql3 = 'ALTER TABLE ' . _DB_PREFIX_ . 'cms ADD `template` VARCHAR(64) NOT NULL';
		} else {
			$sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'category DROP COLUMN `template`';
			$sql2 = 'ALTER TABLE ' . _DB_PREFIX_ . 'product DROP COLUMN `template`';
			$sql3 = 'ALTER TABLE ' . _DB_PREFIX_ . 'cms DROP COLUMN `template`';
		}
			

		if(!Db::getInstance()->Execute($sql) || !Db::getInstance()->Execute($sql2) || !Db::getInstance()->Execute($sql3))
			return false;
		return true;
	}


	private function _getCustomTemplate($entity, $id)
	{
		return Db::getInstance()->getValue('SELECT template FROM '._DB_PREFIX_.$entity.' WHERE id_'.$entity.' = ' . (int)$id);
	}


	public function hookDisplayBackOfficeCategory($params)
	{
		return $this->displayTemplateForm('category');
	}

	public function hookCategoryUpdate($params)
	{

		if(!Db::getInstance()->update('category', array('template' => Tools::getValue('product_template')), 'id_category = ' . (int)$params['category']->id))
				$this->controller->errors[] = $this->l('Error: ').mysql_error();
	}

	public function hookActionProductUpdate($params)
	{
		if(Tools::isSubmit('product_template'))
		{
			if(!Db::getInstance()->update('product', array('template' => Tools::getValue('product_template')), 'id_product = ' . (int)$params['id_product']))
				$this->controller->errors[] = $this->l('Error: ').mysql_error();
		}
		
	}

	public function hookDisplayAdminProductsExtra($params)
	{

		return $this->displayTemplateForm('product');

	}

	public function hookDisplayAdminCmsContentForm()
	{
		return $this->displayTemplateForm('cms');
	}

	public function hookActionObjectCmsUpdateAfter($params)
	{
		if(!Db::getInstance()->update('cms', array('template' => Tools::getValue('product_template')), 'id_cms = ' . (int)$params['object']->id))
				$this->controller->errors[] = $this->l('Error: ').mysql_error();
		
	}

	private function displayTemplateForm($entity)
	{
		$templates = array();
		$overrides = scandir(_PS_THEME_DIR_ .'/templates/'.$entity);

		foreach ($overrides as $toverride) {
			if($toverride != '..' && $toverride != '.')
			{
				$toverride = str_replace('.tpl', '', $toverride);
				$templates[] = $toverride;
			}
		}

		$this->context->smarty->assign(array(
			'entity' => $entity,
			'chosen_template' => $this->_getCustomTemplate($entity, Tools::getValue('id_'.$entity)),
			'product_templates' => $templates
		));

		return $this->display(__FILE__, 'templateSelectForm.tpl');
	}

	public function hookDisplayOverrideTemplate($params)
	{

		$modifiedPages = ['product', 'category', 'cms'];

		if(isset($params['controller']->php_self))
		{

			if(in_array($params['controller']->php_self, $modifiedPages))	
			{
				$controller = $params['controller']->php_self;
				// try to get a specific product template
				$chosen_template = $this->_getCustomTemplate($controller, Tools::getValue('id_'.$controller));
				if($chosen_template)
				{
					// check that the file exists, and if so override the template				
					if(file_exists(_PS_THEME_DIR_.'/templates/'.$controller.'/'.$chosen_template.'.tpl'))
						return _PS_THEME_DIR_ .'/templates/'.$controller.'/'.$chosen_template.'.tpl';
				}
			}
		}
	}
}