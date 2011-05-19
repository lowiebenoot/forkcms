{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblFeedmuncher|ucfirst}: {$lblArticles}</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'loading'}" id="getArticles" class="submitButton button inputButton mainButton icon iconRefresh"><span>{$lblGetArticlesNow|ucfirst}</span></a>
	</div>
</div>

<div id="tabs" class="tabs">
	<ul>
		<li><a href="#tabFeedmuncher">{$lblPublishedInFeedmuncher|ucfirst}  ({$numPublishedInFeedmuncher})</a></li>
		{option:blogIsInstalled}<li><a href="#tabBlog">{$lblPublishedInBlog|ucfirst} ({$numPublishedInBlog})</a></li>{/option:blogIsInstalled}
		<li><a href="#tabNotPublished">{$lblNotPublished|ucfirst} ({$numNotPublished})</a></li>
		<li><a href="#tabDrafts">{$lblDrafts|ucfirst} ({$numDrafts})</a></li>
	</ul>

	{form:filter}
		<div id="tabFeedmuncher">
			{option:ddmFeedmuncherCategory}
				<p class="oneLiner">
					<label for="feedmuncherCategory">{$msgShowOnlyItemsInCategory}</label>
					&nbsp;{$ddmFeedmuncherCategory} {$ddmFeedmuncherCategoryError}
				</p>
			{/option:ddmFeedmuncherCategory}

			{option:dgFeedmuncherPosts}
				<div class="datagridHolder">
					{$dgFeedmuncherPosts}
				</div>
			{/option:dgFeedmuncherPosts}

			{option:!dgFeedmuncherPosts}
				<p>
					{option:noFeeds}{$msgNoFeeds|sprintf:{$var|geturl:'add'}}{/option:noFeeds}
					{option:!noFeeds}{$msgNoItems|sprintf:{$var|geturl:'add'}}{/option:!noFeeds}
				</p>
			{/option:!dgFeedmuncherPosts}
		</div>

		{option:blogIsInstalled}
			<div id="tabBlog">
				{option:ddmBlogCategory}
					<p class="oneLiner">
						<label for="blogCategory">{$msgShowOnlyItemsInCategory}</label>
						&nbsp;{$ddmBlogCategory} {$ddmBlogCategoryError}
					</p>
				{/option:ddmBlogCategory}

				{option:dgBlogPosts}
					<div class="datagridHolder">
						{$dgBlogPosts}
					</div>
				{/option:dgBlogPosts}
				{option:!dgBlogPosts}
					<p>
						{option:noFeeds}{$msgNoFeeds|sprintf:{$var|geturl:'add'}}{/option:noFeeds}
						{option:!noFeeds}{$msgNoItems|sprintf:{$var|geturl:'add'}}{/option:!noFeeds}
					</p>
				{/option:!dgBlogPosts}
			</div>
		{/option:blogIsInstalled}
	{/form:filter}

	<div id="tabNotPublished">
		{option:dgNotPublished}
			<form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massFeedmuncherAction">
				<div class="datagridHolder">
					{$dgNotPublished}
				</div>
			</form>
		{/option:dgNotPublished}
		{option:!dgNotPublished}
			<p>
				{option:noFeeds}{$msgNoFeeds|sprintf:{$var|geturl:'add'}}{/option:noFeeds}
				{option:!noFeeds}{$msgNoItems|sprintf:{$var|geturl:'add'}}{/option:!noFeeds}
			</p>
		{/option:!dgNotPublished}
	</div>

	<div id="tabDrafts">
		{option:dgDrafts}
			<div class="datagridHolder">
				{$dgDrafts}
			</div>
		{/option:dgDrafts}
		{option:!dgDrafts}
			<p>{$msgCoreNoItems}</p>
		{/option:!dgDrafts}
	</div>

</div>

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}