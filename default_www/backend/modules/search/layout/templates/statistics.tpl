{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblSearch|ucfirst}: {$lblStatistics}</h2>
</div>

{option:datagrid}
<div class="datagridHolder">
	<div class="tableHeading">
		<h3>{$lblStatistics|ucfirst}</h3>
	</div>
	{$datagrid}
</div>
{/option:datagrid}

{option:!datagrid}<p>{$msgNoStatistics}</p>{/option:!datagrid}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}