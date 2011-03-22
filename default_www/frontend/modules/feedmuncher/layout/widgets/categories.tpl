{*
	variables that are available:
	- {$widgetFeedmuncherCategories}:
*}

{option:widgetFeedmuncherCategories}
	<section id="feedmuncherCategoriesWidget" class="mod">
		<div class="inner">
			<header class="hd">
				<h3>{$lblCategories|ucfirst}</h3>
			</header>
			<div class="bd content">
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
	</section>
{/option:widgetFeedmuncherCategories}