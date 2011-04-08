{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:edit}
	{option:isOnlyMemberOfAGroup}
		<div class="generalMessage infoMessage content">
			<span>{$msgIsOnlyMemberOfAGroup}</span>
		</div>
	{/option:isOnlyMemberOfAGroup}
	<div class="box">
		<div class="heading">
			<h3>{$lblBanners|ucfirst}: {$lblEditBanner}</h3>
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
				<label>{$lblSize|ucfirst}</label>
				<span>{$standard.name} - {$standard.width}x{$standard.height}</span>
			</p>
			<p>
				<label for="file">{$lblFile|ucfirst}</label>
				{$fileFile}
				<span> - <a href="/frontend/files/banners/{option:isSWF}original{/option:isSWF}{option:!isSWF}resized{/option:!isSWF}/{$item.id}_{$item.file}" title="{$lblCurrentFile}">{$lblCurrentFile}</a></span>
				{option:!fileFileError}<span class="helpTxt">{$errJPGGIFPNGAndSWFOnly}</span>{/option:!fileFileError}
				{$fileFileError}
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

		{option:groups}
			<div class="content groupmembers">
					<p>{$lblIsMemberOf|ucfirst}:</p>
					<ul>
						{iteration:groups}
							<li><a href="{$var|geturl:'edit_group'}&id={$groups.id}">{$groups.name}</a></li>
						{/iteration:groups}
					</ul>
			</div>
		{/option:groups}
	</div>

	<div class="fullwidthOptions">
		{option:!isOnlyMemberOfAGroup}
			<a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
				<span>{$lblDelete|ucfirst}</span>
			</a>
		{/option:!isOnlyMemberOfAGroup}

		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="editBanner" value="{$lblSave|ucfirst}" />
		</div>
	</div>

	{option:!isOnlyMemberOfAGroup}
	<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
		<p>
			{$msgConfirmDeleteBanner|sprintf:{$item.name}}
		</p>
	</div>
	{/option:!isOnlyMemberOfAGroup}
{/form:edit}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}