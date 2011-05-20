{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:addFeed}
	<div class="box">
		<div class="heading">
			<h3>{$lblFeeds|ucfirst}: {$lblAddFeed}</h3>
		</div>
		<div class="options horizontal">
			<p>
				<label for="name">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtName} {$txtNameError}
			</p>
			<p>
				<label for="type">{$lblType|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmType} {$ddmTypeError}
			</p>
			<p>
				<label for="url">{$lblFeedURL|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtUrl} {$txtUrlError}
			</p>
			<p>
				<label for="website">{$lblWebsite|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtWebsite} {$txtWebsiteError}
			</p>
			<p>
				<label for="username">{$lblUsername|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtUsername} {$txtUsernameError}
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
			<ul class="inputList pb0">
				<li>{$chkAutoPublish} <label for="autoPublish">{$lblAutoPublish|ucfirst}</label></li>
				<li>{$chkAggregateFeed} <label for="aggregateFeed" id="aggregateFeedLabel">{$lblAggregateFeed|ucfirst}</label></li>
			</ul>
			<p id="reoccurrenceWrapper">
				<label for="reoccurrence" class="noFloat">{$lblAggregate|ucfirst}</label>{$ddmReoccurrence}
				<label for="day" class="noFloat">{$lblOn}</label>
				{$ddmDay} <label for="time" class="noFloat">{$lblAt}</label>{$txtTime}
				{$txtTimeError}
				<span class="helpTxt">{$msgHelpReoccurrence}</span>
			</p>
			<ul class="inputList pb0">
				<li>{$chkLinkToOriginal} <label for="linkToOriginal">{$lblLinkToOriginal|ucfirst}</label></li>
			</ul>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="addFeed" value="{$lblAddFeed|ucfirst}" />
		</div>
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