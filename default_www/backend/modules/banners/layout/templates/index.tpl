{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblBanners|ucfirst}</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAddBanner|ucfirst}">
			<span>{$lblAddBanner|ucfirst}</span>
		</a>
	</div>
</div>

{option:dgBanners}
	<div class="datagridHolder">
		{$dgBanners}
	</div>
{/option:dgBanners}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}