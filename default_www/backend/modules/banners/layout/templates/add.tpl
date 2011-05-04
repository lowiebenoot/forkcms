{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:add}
	<div class="box">
		<div class="heading">
			<h3>{$lblBanners|ucfirst}: {$lblAddBanner}</h3>
		</div>
		<div class="options horizontal">
			<p>
				<label for="name">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtName} {$txtNameError}
			</p>
			<p>
				<label for="url">{$lblURL|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtUrl}
				{$txtUrlError}
				<span class="helpTxt">{$lblTrackerUrl|ucfirst}: <span>{$trackerUrl}<span id="generatedUrl"></span></span></span>
			</p>
			<p>
				<label for="size">{$lblSize|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmSize} {$ddmSizeError}
			</p>
			<p>
				<label for="file">{$lblFile|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$fileFile}
				<span class="helpTxt">{$errJPGGIFPNGAndSWFOnly}</span>
				{$fileFileError}
			</p>
			<p>
				<ul class="inputList pb0">
					<li class="">{$chkShowPermanently} <label for="showPermanently">{$lblShowPermanently|ucfirst}</label></li>
				</ul>
			</p>
			<p>
				<label for="startDate">{$lblStartDate|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtStartDate} <label for="startTime" class="nofloat">{$lblAt}</label> {$txtStartTime}
				{$txtStartDateError}
				{$txtStartTimeError}
			</p>
			<p>
				<label for="endDate">{$lblEndDate|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtEndDate} <label for="endTime" class="nofloat">{$lblAt}</label> {$txtEndTime}
				{$txtEndDateError}
				{$txtEndTimeError}
			</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="addCategory" value="{$lblAddBanner|ucfirst}" />
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}