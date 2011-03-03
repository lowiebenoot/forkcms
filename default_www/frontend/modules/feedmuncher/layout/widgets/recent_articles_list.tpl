{*
	variables that are available:
	- {$widgetFeedmuncherRecentArticlesList}: contains an array with all posts, each element contains data about the post
*}

{option:widgetFeedmuncherRecentArticlesList}
	<div id="feedmuncherRecentArticlesListWidget" class="mod">
		<div class="inner">
			<div class="hd">
				<h3>{$lblRecentArticles|ucfirst}</h3>
			</div>
			<div class="bd">
				<ul>
					{iteration:widgetFeedmuncherRecentArticlesList}
						<li><a href="{$widgetFeedmuncherRecentArticlesList.full_url}" title="{$widgetFeedmuncherRecentArticlesList.title}">{$widgetFeedmuncherRecentArticlesList.title}</a></li>
					{/iteration:widgetFeedmuncherRecentArticlesList}
				</ul>
			</div>
			<div class="ft">
				<p>
					<a href="{$var|geturlforblock:'feedmuncher'}">{$lblFeedmuncherArchive|ucfirst}</a>
					<a id="RSSfeed" href="{$var|geturlforblock:'feedmuncher':'rss'}">{$lblSubscribeToTheRSSFeed|ucfirst}</a>
				</p>
			</div>
		</div>
	</div>
{/option:widgetFeedmuncherRecentArticlesList}