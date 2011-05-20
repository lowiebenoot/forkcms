{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{option:isDeleted}
	<div class="generalMessage infoMessage content">
		<p class="pb0">{$msgFeedIsDeleted|sprintf:{$restoreURL}}</p>
	</div>
{/option:isDeleted}

{form:editFeed}
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
				<label for="type">{$lblType|ucfirst}</label>
				<span>{$item.feed_type}</span>
			</p>
			{option:txtUrl}
				<p>
					<label for="url">{$lblFeedURL|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
					{$txtUrl} {$txtUrlError}
				</p>
			{/option:txtUrl}
			{option:txtWebsite}
				<p>
					<label for="website">{$lblWebsite|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
					{$txtWebsite} {$txtWebsiteError}
				</p>
			{/option:txtWebsite}
			{option:txtUsername}
				<p>
					<label for="username">{$lblUsername|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
					{$txtUsername} {$txtUsernameError}
				</p>
			{/option:txtUsername}
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
			{option:chkAggregateFeed}
				<ul class="inputList pb0">
					<li>{$chkAutoPublish} <label for="autoPublish">{$lblAutoPublish|ucfirst}</label></li>
					<li>{$chkAggregateFeed} <label for="aggregateFeed" id="aggregateFeedLabel">{$lblAggregateFeed|ucfirst}</label></li>
				</ul>
			{/option:chkAggregateFeed}
			<p id="reoccurrenceWrapper">
				<label for="reoccurrence" class="noFloat">{$lblAggregate|ucfirst}</label>{$ddmReoccurrence}
				<label for="day" class="noFloat">{$lblOn}</label>
				{$ddmDay} <label for="time" class="noFloat">{$lblAt}</label>{$txtTime}
				{$txtTimeError}
				<span class="helpTxt">{$msgHelpReoccurrence}</span>
			</p>
			<ul class="inputList pb0">
				{option:chkLinkToOriginal}<li>{$chkLinkToOriginal} <label for="linkToOriginal">{$lblLinkToOriginal|ucfirst}</label></li>{/option:chkLinkToOriginal}
			</ul>
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
{/form:editFeed}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}