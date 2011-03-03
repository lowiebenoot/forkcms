{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblFeedmuncher|ucfirst}: {$lblFeedmuncherFeeds}</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAddFeed|ucfirst}">
			<span>{$lblAddFeed|ucfirst}</span>
		</a>
	</div>
</div>

{option:dgFeeds}
	<div class="datagridHolder">
		{$dgFeeds}
	</div>
{/option:dgFeeds}

{option:!dgFeeds}<p>{$msgNoFeeds|sprintf:{$var|geturl:'add'}}</p>{/option:!dgFeeds}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}