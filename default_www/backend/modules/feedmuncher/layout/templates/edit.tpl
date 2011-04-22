{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:addFeed}
	<div class="box">
		<div class="heading">
			<h3>{$lblFeeds|ucfirst}: {$msgEditFeed|sprintf:{$item.name}}</h3>
		</div>
		<div class="options horizontal">
			<p>
				<label for="name">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtName} {$txtNameError}
			</p>
			<p>
				<label for="url">{$lblFeedURL|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtUrl} {$txtUrlError}
			</p>
			<p>
				<label for="website">{$lblWebsite|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtWebsite} {$txtWebsiteError}
			</p>
			{option:blogIsInstalled}
				<ul class="inputList">
					{iteration:target}
					<li>
						{$target.rbtTarget} <label for="{$target.id}">{$target.label|ucfirst}</label>
					</li>
					{/iteration:target}
				</ul>
			{/option:blogIsInstalled}
			<p>
				<label for="category">{$lblCategory|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmCategory} {option:blogIsInstalled}{$ddmCategoryBlog}{/option:blogIsInstalled} {$ddmCategoryError}
			</p>
			<p>
				<label for="author">{$lblAuthor|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmAuthor} {$ddmAuthorError}
			</p>
			<p>
				<ul class="inputList">
					<li>{$chkAutoPublish} <label for="autoPublish">{$lblAutoPublish|ucfirst}</label></li>
					<li>{$chkLinkToOriginal} <label for="linkToOriginal">{$lblLinkToOriginal|ucfirst}</label></li>
				</ul>
			</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		<a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
			<span>{$lblDelete|ucfirst}</span>
		</a>
		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="editFeed" value="{$lblSave|ucfirst}" />
		</div>
	</div>

	<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
		<p>
			{$msgConfirmDeleteFeed|sprintf:{$item.name}}
		</p>
	</div>

	<div id="addCategoryDialog" class="forkForms" title="{$lblAddCategory|ucfirst}" style="display: none;">
		<div id="templateList">
			<p>
				<label for="categoryTitle">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				<input type="text" name="categoryTitle" id="categoryTitle" class="inputText" maxlength="255" />
				<span class="formError" id="categoryTitleError" style="display: none;">{$errFieldIsRequired|ucfirst}</span>
			</p>
		</div>
	</div>
{/form:addFeed}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}