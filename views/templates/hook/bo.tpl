<div class="panel">
	<div class="panel-heading">{l s='Configuration' mod='pschoosetemplate'}</div>
	<form action="" method="POST" class="defaultForm form-horizontal" enctype="multipart/form-data">
		
		<div class="form-group">
			<label class="col-lg-2">
				{l s='Product Template' mod='pschoosetemplate'}
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
					{l s='No Template Found' mod='pschoosetemplate'}
				{/if}
			</div>
			<div class="col-lg-2">
				<button type="submit" class="btn btn-default" name="eraseProductTemplate">{l s='Delete' mod='pschoosetemplate'}</button>
			</div>
		</div>
		

		<div class="form-group">
			<label class="col-lg-2">
				{l s='Category Template' mod='pschoosetemplate'}
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
					{l s='No Template Found' mod='pschoosetemplate'}
				{/if}
			</div>
			<div class="col-lg-2">
				<button type="submit" class="btn btn-default" name="eraseCategoryTemplate">{l s='Delete' mod='pschoosetemplate'}</button>
			</div>

		</div>

		<hr>
		<h4>{l s='Add a new template' mod='pschoosetemplate'}</h4>
		<div class="form-group">
			<label class="col-lg-1">
				{l s='For' mod='pschoosetemplate'}
			</label>
			<div class="col-lg-2">
				<select name="template_entity">
					<option value="product">{l s='Products' mod='pschoosetemplate'}</option>
					<option value="category">{l s='Categories' mod='pschoosetemplate'}</option>
				</select>
			</div>
			<div class="col-lg-2">
				<input type="file" name="new_template">
			</div>
			<div class="col-lg-2">
				<button id="add_new_template" type="submit" class="btn btn-default" name="addNewTemplate">{l s='Add' mod='pschoosetemplate'}</button>
			</div>
		</div>

		<script>
			$(document).ready(function() {
				$('#add_new_template').click(function(e) {
					var confirm_me = confirm("{l s='Any file with the same name will be overwritten. Proceed?' mod='pschoosetemplate'}")
					if(confirm_me == false)
						e.preventDefault();
				});
			});
		</script>

	</form>
	<div class="panel-footer">
		<button id="submit_config"  type="submit" name="submitConfig" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='pschoosetemplate'}</button>
	</div>
</div>