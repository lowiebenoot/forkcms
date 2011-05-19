{*
	variables that are available:
	- {$widgetFeedmuncherRecentArticlesList}: contains an array with all posts, each element contains data about the post
*}

{option:widgetFeedmuncherRecentArticlesList}
	<section id="feedmuncherRecentArticlesListWidget" class="mod">
		<div class="inner">
			<header class="hd">
				<h3>{$lblRecentArticles|ucfirst}</h3>
			</header>
			<div class="bd content">
				<ul>
					{iteration:widgetFeedmuncherRecentArticlesList}
						<li><a href="{$widgetFeedmuncherRecentArticlesList.full_url}" {option:widgetFeedmuncherRecentArticlesList.link_to_original}class="linkToOriginal"{/option:widgetFeedmuncherRecentArticlesList.link_to_original} title="{$widgetFeedmuncherRecentArticlesList.title}">{$widgetFeedmuncherRecentArticlesList.title}</a></li>
					{/iteration:widgetFeedmuncherRecentArticlesList}
				</ul>
			</div>
			<footer class="ft">
				<p>
					<a href="{$var|geturlforblock:'feedmuncher'}">{$lblArchive|ucfirst}</a>
					<a id="RSSfeed" href="{$var|geturlforblock:'feedmuncher':'rss'}">{$lblSubscribeToTheRSSFeed|ucfirst}</a>
				</p>
			</footer>
		</div>
	</section>
{/option:widgetFeedmuncherRecentArticlesList}