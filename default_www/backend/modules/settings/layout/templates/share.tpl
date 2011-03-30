{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblServices|ucfirst}</h2>
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
					{iteration:services}<li>{$services.chkServices} <label for="{$securityGroups.id}">{$services.label|ucfirst}</label></li>{/iteration:services}
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
			<div class="datagridHolder">
				<table border="0" cellspacing="0" cellpadding="0" class="datagrid">
					<tr>
						<th><span>{$lblModule|ucfirst}</span></th>
						<th>
							<span>
								<div class="oneLiner">
									<p>{$lblMessage|ucfirst}</p>
									<abbr class="help">(?)</abbr>
									<div class="tooltip" style="display: none;">
										<p>{$msgShareMessages}</p>
									</div>
								</div>
							</span>
						</th>
					</tr>
					{iteration:modules}
						<tr class="{cycle:odd:even}">
							<td><label for="{$modules.id}">{$modules.module}</label></td>
							<td class="serviceMessage" data-id="{$modules.id}">{$modules.message}</td>
						</tr>
					{/iteration:modules}
				</table>
			</div>
		</div>
	</div>

	{* URL shortener *}
	<div class="box">
		<div class="heading">
			<h3>{$lblURLShortener|ucfirst}</h3>
		</div>
		<div class="options">
			<p>
				<label for="shorten">{$chkShorten} {$msgShortenURLs}</label>
			</p>
			<div id="shorteners">
				<p>{$msgShorteners}</p>
				<ul class="inputList">
					{iteration:shortener}
					<li>
						{$shortener.rbtShortener}
						<label for="{$shortener.id}">{$shortener.label}</label>
					</li>
					{/iteration:shortener}
				</ul>
			</div>
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