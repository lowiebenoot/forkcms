{*
	variables that are available:
	- {$widgetFeedmuncherArchive}:
*}

{cache:{$LANGUAGE}_feedmuncherWidgetArchiveCache}
	{option:widgetFeedmuncherArchive}
		<section id="feedmuncherArchiveWidget" class="mod">
			<div class="inner">
				<header class="hd">
					<h3>{$lblArchive|ucfirst}</h3>
				</header>
				<div class="bd content">
					<ul>
						{iteration:widgetFeedmuncherArchive}
							<li>
								{option:widgetFeedmuncherArchive.url}<a href="{$widgetFeedmuncherArchive.url}">{/option:widgetFeedmuncherArchive.url}
									{$widgetFeedmuncherArchive.label}
									{option:widgetFeedmuncherArchive.url}({$widgetFeedmuncherArchive.total}){/option:widgetFeedmuncherArchive.url}
								{option:widgetFeedmuncherArchive.url}</a>{/option:widgetFeedmuncherArchive.url}

								{option:widgetFeedmuncherArchive.months}
									<ul>
										{iteration:widgetFeedmuncherArchive.months}
											<li>
												{option:widgetFeedmuncherArchive.months.url}<a href="{$widgetFeedmuncherArchive.months.url}">{/option:widgetFeedmuncherArchive.months.url}
													{$widgetFeedmuncherArchive.months.label|date:'F':{$LANGUAGE}}
													{option:widgetFeedmuncherArchive.months.url}({$widgetFeedmuncherArchive.months.total}){/option:widgetFeedmuncherArchive.months.url}
												{option:widgetFeedmuncherArchive.months.url}</a>{/option:widgetFeedmuncherArchive.months.url}
											</li>
										{/iteration:widgetFeedmuncherArchive.months}
									</ul>
								{/option:widgetFeedmuncherArchive.months}
							</li>
						{/iteration:widgetFeedmuncherArchive}
					</ul>
				</div>
			</div>
		</section>
	{/option:widgetFeedmuncherArchive}
{/cache:{$LANGUAGE}_feedmuncherWidgetArchiveCache}