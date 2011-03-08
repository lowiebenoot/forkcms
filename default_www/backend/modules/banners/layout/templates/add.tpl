{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:add}
	<div class="box">
		<div class="heading">
			<h3>{$lblBanners|ucfirst}: {$lblBannersAddBanner}</h3>
		</div>
		<div class="options horizontal">
			<p>
				<label for="name">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtName} {$txtNameError}
			</p>
			<p>
				<label for="url">{$lblURL|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtUrl} {$txtUrlError}
			</p>
			<p>
				<label for="size">{$lblSize|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmSize} {$ddmSizeError}
			</p>
			<p>
				<label for="image">{$lblImage|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$fileImage} {$fileImageError}
			</p>
			<p>
				<label for="startDate">{$lblStartDate|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtStartDate} {$txtStartDateError} <label for="startTime" class="nofloat">{$lblAt}<abbr title="{$lblRequiredField}">*</abbr></label> {$txtStartTime} {$txtStartTimeError}
			</p>
			<p>
				<label for="endDate">{$lblEndDate|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtEndDate} {$txtEndDateError} <label for="endTime" class="nofloat">{$lblAt}<abbr title="{$lblRequiredField}">*</abbr></label> {$txtEndTime} {$txtEndTimeError}
			</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="addCategory" value="{$lblAddCategory|ucfirst}" />
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}