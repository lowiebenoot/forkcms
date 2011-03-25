{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:add}
	<div class="box">
		<div class="heading">
			<h3>{$lblBanners|ucfirst}: {$lblAddGroup}</h3>
		</div>
		<div class="options horizontal">
			<p>
				<label for="name">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtName} {$txtNameError}
			</p>
			<p>
				<label for="size">{$lblSize|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmSize} {$ddmSizeError}
			</p>

			{option:dgBanners}
			<div class="datagridHolder">
				{$dgBanners}
			</div>
			{/option:dgBanners}

			{option:formErrors}
				<p class="formError"><span>{$formErrors}</span></p>
			{/option:formErrors}
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="addGroup" value="{$lblAddGroup|ucfirst}" />
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}