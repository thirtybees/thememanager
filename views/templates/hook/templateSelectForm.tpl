{**
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
 *}

{if $entity == 'product'}
<div id="product-modulethememanager" class="panel product-tab">

	<input type="hidden" name="submitted_tabs[]" value="ModuleThemeManager">
	<h3>{l s='Choose a template for this' mod='thememanager'} {$entity}</h3>
{/if}
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Template' mod='thememanager'}</label>
		<div class="col-lg-9">
			<select name="product_template">
				<option value="">--</option>
				{foreach from=$product_templates item=template}
					<option value="{$template}" {if $template == $chosen_template}selected="selected"{/if}>{$template}</option>
				{/foreach}
			</select>
		</div>
	</div>
{if $entity == 'product'}
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
	</div>

</div>
{/if}
