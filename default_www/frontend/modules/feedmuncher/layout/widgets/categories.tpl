{*
	variables that are available:
	- {$widgetFeedmuncherCategories}:
*}

{option:widgetFeedmuncherCategories}
	<div id="feedmuncherCategoriesWidget" class="mod">
		<div class="inner">
			<div class="hd">
				<h3>{$lblCategories|ucfirst}</h3>
			</div>
			<div class="bd">
				<ul>
					{iteration:widgetFeedmuncherCategories}
						<li>
							<a href="{$widgetFeedmuncherCategories.url}">
								{$widgetFeedmuncherCategories.label} ({$widgetFeedmuncherCategories.total})
							</a>
						</li>
					{/iteration:widgetFeedmuncherCategories}
				</ul>
			</div>
		</div>
	</div>
{/option:widgetFeedmuncherCategories}