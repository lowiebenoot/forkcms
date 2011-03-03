{*
	variables that are available:
	- {$widgetFeedmuncherArchive}:
*}

{cache:{$LANGUAGE}_feedmuncherWidgetArchiveCache}
	{option:widgetFeedmuncherArchive}
		<div id="feedmuncherArchiveWidget" class="mod">
			<div class="inner">
				<div class="hd">
					<h3>{$lblArchive|ucfirst}</h3>
				</div>
				<div class="bd">
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
												{option:months.url}<a href="{$months.url}">{/option:months.url}
													{$months.label|date:'F':{$LANGUAGE}}
													{option:months.url}({$months.total}){/option:months.url}
												{option:months.url}</a>{/option:months.url}
											</li>
										{/iteration:widgetFeedmuncherArchive.months}
									</ul>
								{/option:widgetFeedmuncherArchive.months}
							</li>
						{/iteration:widgetFeedmuncherArchive}
					</ul>
				</div>
			</div>
		</div>
	{/option:widgetFeedmuncherArchive}
{/cache:{$LANGUAGE}_feedmuncherWidgetArchiveCache}