{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblFeedmuncher|ucfirst}: {$lblArticles}</h2>
</div>

<div id="tabs" class="tabs">
	<ul>
		<li><a href="#tabFeedmuncher">{$lblFeedmuncherPublishedInFeedmuncher|ucfirst}</a></li>
		{option:blogIsInstalled}<li><a href="#tabBlog">{$lblFeedmuncherPublishedInBlog|ucfirst}</a></li>{/option:blogIsInstalled}
		<li><a href="#tabNotPublished">{$lblFeedmuncherNotPublished|ucfirst}</a></li>
	</ul>


	<div id="tabFeedmuncher">
		{option:dgFeedmuncherDrafts}
			<div class="datagridHolder">
				<div class="tableHeading">
					<h3>{$lblDrafts|ucfirst}</h3>
				</div>
				{$dgFeedmuncherDrafts}
			</div>
		{/option:dgFeedmuncherDrafts}

		{option:dgFeedmuncherPosts}
			<div class="datagridHolder">
				<div class="tableHeading">
					<h3>{$lblPublishedArticles|ucfirst}</h3>
				</div>
				{$dgFeedmuncherPosts}
			</div>
		{/option:dgFeedmuncherPosts}

		{option:!dgFeedmuncherPosts}<p>{$msgNoItems|sprintf:{$var|geturl:'add'}}</p>{/option:!dgFeedmuncherPosts}
	</div>

	{option:blogIsInstalled}
		<div id="tabBlog">
			{option:dgBlogPosts}
				<div class="datagridHolder">
					<div class="tableHeading">
						<h3>{$lblPublishedArticles|ucfirst}</h3>
					</div>
					{$dgBlogPosts}
				</div>
			{/option:dgBlogPosts}

			{option:!dgBlogPosts}<p>{$msgNoItems|sprintf:{$var|geturl:'add'}}</p>{/option:!dgBlogPosts}
		</div>
	{/option:blogIsInstalled}

	<div id="tabNotPublished">
		{option:dgNotPublished}
			<div class="datagridHolder">
				<form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massFeedmuncherAction">
					{$dgNotPublished}
				</form>
			</div>
		{/option:dgNotPublished}
	</div>

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}