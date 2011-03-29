{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblFeedmuncher|ucfirst}: {$lblArticles}</h2>
</div>

<div id="tabs" class="tabs">
	<ul>
		<li><a href="#tabFeedmuncher">{$lblPublishedInFeedmuncher|ucfirst}  ({$numPublishedInFeedmuncher})</a></li>
		{option:blogIsInstalled}<li><a href="#tabBlog">{$lblPublishedInBlog|ucfirst} ({$numPublishedInBlog})</a></li>{/option:blogIsInstalled}
		<li><a href="#tabNotPublished">{$lblNotPublished|ucfirst} ({$numNotPublished})</a></li>
		<li><a href="#tabDrafts">{$lblDrafts|ucfirst} ({$numDrafts})</a></li>
	</ul>


	<div id="tabFeedmuncher">
		{option:dgFeedmuncherPosts}
			<div class="datagridHolder">
				{$dgFeedmuncherPosts}
			</div>
		{/option:dgFeedmuncherPosts}

		{option:!dgFeedmuncherPosts}<p>{$msgNoItems|sprintf:{$var|geturl:'add'}}</p>{/option:!dgFeedmuncherPosts}
	</div>

	{option:blogIsInstalled}
		<div id="tabBlog">
			{option:dgBlogPosts}
				<div class="datagridHolder">
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
		{option:!dgNotPublished}
			<p>{$msgNoItems|sprintf:{$var|geturl:'add'}}</p>
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

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}