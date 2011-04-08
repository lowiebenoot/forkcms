{* don't delete the bannerWidgetURL class, it is used in javascript *}

{option:item}
	<div class="bannerWidget">
		{option:isSWF}
			<script src="/frontend/modules/banners/js/swfobject.js"></script>
			<script type="text/javascript">
			swfobject.embedSWF("/frontend/files/banners/original/{$item.id}_{$item.file}", "bannerWidgetSWFContent", "{$item.width}", "{$item.height}", "9.0.0");
			</script>
			<a class="bannerWidgetURL linkedImage" href="{$item.url}" title="{$item.url}" data-id="{$item.id}"><div id="bannerWidgetSWFContent"></div></a>
		{/option:isSWF}

		{option:!isSWF}
			<a class="bannerWidgetURL linkedImage" href="{$item.url}" title="{$item.url}" data-id="{$item.id}"><img src="/frontend/files/banners/resized/{$item.id}_{$item.file}" alt="{$item.url}" width="{$item.width}" height="{$item.height}" /></a>
		{/option:!isSWF}
	</div>
{/option:item}