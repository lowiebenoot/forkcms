{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblLocation|ucfirst}: {$lblEdit}</h2>
</div>

{form:edit}
	<p>
		<label for="title">{$lblTitle|ucfirst}</label>
		{$txtTitle} {$txtTitleError}
	</p>

	<table width="100%">
		<tr>
			<td id="leftColumn">
				<div class="box">
					<div class="heading">
						<h3>{$lblMap|ucfirst}</h3>
					</div>

					{* Map *}
					<div class="options">
						<div id="map" style="height: {$settings.height}px; width: {$settings.width}px;">
						</div>
					</div>
				</div>
			</td>

			{form:settings}
			<td id="rightColumn" style="width: 300px; padding-left: 10px;">
				<div class="box">
					<div class="heading">
						<h3>{$lblSettings|ucfirst}</h3>
					</div>

					{* Zoom level *}
					<div class="options">
						<p>
							<label for="zoomLevel">{$lblZoomLevel|ucfirst}</label>
							{$ddmZoomLevel} {$ddmZoomLevelError}
						</p>
					</div>

					{* Map width *}
					<div class="options">
						<p>
							<label for="width">{$lblWidth|ucfirst}</label>
							{$txtWidth} {$txtWidthError}
						</p>
					</div>

					{* Map height *}
					<div class="options">
						<p>
							<label for="height">{$lblHeight|ucfirst}</label>
							{$txtHeight} {$txtHeightError}
						</p>
					</div>

					{* Map type *}
					<div class="options">
						<p>
							<label for="mapType">{$lblMapType|ucfirst}</label>
							{$ddmMapType} {$ddmMapTypeError}
						</p>
					</div>

					{* Show the full url link or not *}
					<div class="options">
						<p>
							<label for="fullUrl">{$chkFullUrl} {$msgShowMapUrl|ucfirst}</label>
						</p>
					</div>

					{* Show directions form or not *}
					<div class="options">
						<p>
							<label for="directions">{$chkDirections} {$msgShowDirections|ucfirst}</label>
						</p>
					</div>

					{* Save button *}
					<div class="options">
						<div class="buttonHolderRight">
							<a href="#" id="saveLiveData" class="button inputButton button mainButton">
								<span>{$lblSave|ucfirst}</span>
							</a>
						</div>
					</div>
				</div>
			</td>
			{/form:settings}
		</tr>
	</table>

	<div class="box horizontal">
		<div class="heading">
			<h3>{$lblAddress|ucfirst}</h3>
		</div>
		<div class="options">
			<p>
				<label for="street">{$lblStreet|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtStreet} {$txtStreetError}
			</p>
			<p>
				<label for="number">{$lblNumber|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtNumber} {$txtNumberError}
			</p>
			<p>
				<label for="zip">{$lblZip|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtZip} {$txtZipError}
			</p>
			<p>
				<label for="city">{$lblCity|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtCity} {$txtCityError}
			</p>
			<p>
				<label for="country">{$lblCountry|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmCountry} {$ddmCountryError}
			</p>
			{$hidMapId}
		</div>
	</div>

	<div class="fullwidthOptions">
		{option:showLocationDelete}
		<a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
			<span>{$lblDelete|ucfirst}</span>
		</a>
		<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
			<p>
				{$msgConfirmDelete|sprintf:{$item.title}}
			</p>
		</div>
		{/option:showLocationDelete}

		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:edit}

<script type="text/javascript">
	var mapOptions = {
		zoom: {$settings.zoom_level},
		type: '{$settings.map_type}',
		center: {
			lat: {$settings.center.lat},
			lng: {$settings.center.lng}
		}
	};
	var markers = [];
	{option:item.lat}
		{option:item.lng}
			markers.push({
				lat: {$item.lat},
				lng: {$item.lng},
				title: '{$item.title}',
				text: '<p>{$item.street} {$item.number}</p><p>{$item.zip} {$item.city}</p>',
				dragable: true
			});
		{/option:item.lng}
	{/option:item.lat}
</script>

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}