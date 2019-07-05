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

<div class="panel">
	<div class="panel-heading">{l s='Configuration' mod='thememanager'}</div>
	<form action="" method="POST" class="defaultForm form-horizontal" enctype="multipart/form-data">

		<div class="form-group">
			<label class="col-lg-2">
				{l s='Product Template' mod='thememanager'}
			</label>
			<div class="col-lg-4">
				{if $templates.products|count > 0}
					<select name="product_template">
						<option value="0">--</option>
						{foreach from=$templates.products item=template}
							<option value="{$template}">{$template}</option>
						{/foreach}
					</select>
				{else}
					{l s='No Template Found' mod='thememanager'}
				{/if}
			</div>
			<div class="col-lg-2">
				<button type="submit" class="btn btn-default" name="eraseProductTemplate">{l s='Delete' mod='thememanager'}</button>
			</div>
		</div>


		<div class="form-group">
			<label class="col-lg-2">
				{l s='Category Template' mod='thememanager'}
			</label>
			<div class="col-lg-4">
				{if $templates.categories|count > 0}
					<select name="category_template">
						<option value="0">--</option>
						{foreach from=$templates.categories item=template}
							<option value="{$template}">{$template}</option>
						{/foreach}
					</select>
				{else}
					{l s='No Template Found' mod='thememanager'}
				{/if}
			</div>
			<div class="col-lg-2">
				<button type="submit" class="btn btn-default" name="eraseCategoryTemplate">{l s='Delete' mod='thememanager'}</button>
			</div>

		</div>

		<hr>
		<h4>{l s='Add a new template' mod='thememanager'}</h4>
		<div class="form-group">
			<label class="col-lg-1">
				{l s='For' mod='thememanager'}
			</label>
			<div class="col-lg-2">
				<select name="template_entity">
					<option value="product">{l s='Products' mod='thememanager'}</option>
					<option value="category">{l s='Categories' mod='thememanager'}</option>
				</select>
			</div>
			<div class="col-lg-2">
				<input type="file" name="new_template">
			</div>
			<div class="col-lg-2">
				<button id="add_new_template" type="submit" class="btn btn-default" name="addNewTemplate">{l s='Add' mod='thememanager'}</button>
			</div>
		</div>

		<script>
			$(document).ready(function() {
				$('#add_new_template').click(function(e) {
					var confirm_me = confirm("{l s='Any file with the same name will be overwritten. Proceed?' mod='thememanager'}")
					if(confirm_me == false)
						e.preventDefault();
				});
			});
		</script>

	</form>
	<div class="panel-footer">
		<button id="submit_config"  type="submit" name="submitConfig" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='thememanager'}</button>
	</div>
</div>
