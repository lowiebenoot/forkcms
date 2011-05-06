{* don't delete the bannerWidgetURL class, it is used in javascript *}

{option:item}
	<div class="bannerWidget">
		{option:isSWF}
			<script src="/frontend/modules/banners/js/swfobject.js"></script>
			<script type="text/javascript">
			swfobject.embedSWF("/frontend/files/banners/original/{$item.id}_{$item.file}", "bannerWidgetSWFContent-{$item.id}-{$microtime}", "{$item.width}", "{$item.height}", "9.0.0");
			</script>
			<a class="bannerWidgetURL linkedImage" href="{$var|geturlforblock:'banners':'tracker'}?id={$item.id}&url={$item.url|urlencode}" title="{$item.url}" title="{$item.url}" data-id="{$item.id}" data-url="{$item.url}">
				{* the flash overlay is a dirty little 'hack' that makes it possible to click on the parent link when the swf contains a click action. *}
				<div class="flashOverlay" style="width: {$item.width}px; height: {$item.height}px; position: absolute;"></div>
				<div id="bannerWidgetSWFContent-{$item.id}-{$microtime}"></div>
			</a>
		{/option:isSWF}

		{option:!isSWF}
			<a class="bannerWidgetURL linkedImage" href="{$trackerURL}&id={$item.id}&url={$item.url|urlencode}" title="{$item.url}" data-id="{$item.id}" data-url="{$item.url}"><img src="/frontend/files/banners/resized/{$item.id}_{$item.file}" alt="{$item.url}" width="{$item.width}" height="{$item.height}" /></a>
		{/option:!isSWF}
	</div>
{/option:item}