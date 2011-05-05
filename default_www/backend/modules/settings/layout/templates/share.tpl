{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblShare|ucfirst}</h2>
</div>

{form:share}
	{* Services *}
	<div class="box">
		<div class="heading">
			<h3>{$lblServices|ucfirst}</h3>
		</div>
		<div class="options">
			<p>{$msgSelectServices}</p>
			{option:services}
				<ul class="inputList">
					{iteration:services}<li>{$services.chkServices} <label for="{$services.id}">{$services.label|ucfirst}</label></li>{/iteration:services}
				</ul>
			{/option:services}
		</div>
	</div>

	{* Modules *}
	<div class="box">
		<div class="heading">
			<h3>{$lblModules|ucfirst}</h3>
		</div>
		<div class="options" id="shareableModules">
			{option:dgModules}
				<div class="datagridHolder">
					{$dgModules}
				</div>
			{/option:dgModules}
			{option:!dgModules}
				<p>{$msgNoShareableModules}</p>
			{/option:!dgModules}
		</div>
	</div>

	{* URL shortener *}
	<div class="box">
		<div class="heading">
			<h3>{$lblShortenURLs|ucfirst}</h3>
		</div>
		<div class="options">
			<p>
				<label for="shorten">{$chkShorten} {$lblShortenURLs}</label>
			</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:share}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}