
{if $entity == 'product'}
<div id="product-modulepschoosetemplate" class="panel product-tab">

	<input type="hidden" name="submitted_tabs[]" value="ModulePschoosetemplate">
	<h3>{l s='Choose a template for this' mod='pschoosetemplate'} {$entity}</h3>
{/if}
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Template' mod='pschoosetemplate'}</label>
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
