{* don't delete the bannerWidgetURL class, it is used in javascript *}

{option:item}
	<div class="bannerWidget">
		{option:isSWF}
			<script src="/frontend/modules/banners/js/swfobject.js"></script>
			<script type="text/javascript">
			swfobject.embedSWF("/frontend/files/banners/{$item.id}/original/{$item.file}", "bannerWidgetSWFContent", "{$item.width}", "{$item.height}", "9.0.0");
			</script>
			<a class="bannerWidgetURL" href="{$item.url}" title="{$item.url}" data-id="{$item.id}"><div id="bannerWidgetSWFContent"></div></a>
		{/option:isSWF}
		{option:!isSWF}
			<a class="bannerWidgetURL" href="{$item.url}" title="{$item.url}" data-id="{$item.id}"><img src="/frontend/files/banners/{$item.id}/resized/{$item.file}" alt="{$item.url}" width="{$item.width}" height="{$item.height}" /></a>
		{/option:!isSWF}
	</div>
{/option:item}