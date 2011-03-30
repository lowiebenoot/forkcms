<div id="shareOptions" class="subtleBox">
	<div class="heading">
		<h3>{$lblShare|ucfirst}</h3>
	</div>

	<div class="options">
		<p>{$msgSelectServices}</p>
		{option:services}
			<ul class="inputList">
				{iteration:services}<li>{$services.chkServices} <label for="{$services.id}">{$services.label|ucfirst}</label></li>{/iteration:services}
			</ul>
		{/option:services}

		<p>
			<label for="shareMessage">{$lblShareMessage|ucfirst}</label>
			<span class="helpTxt">{$msgHelpShareMessage}</span>
		</p>
		<p>
			{$txtShareMessage} {$txtShareMessageError}
		</p>
	</div>
</div>