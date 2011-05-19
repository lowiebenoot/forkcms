{*
	variables that are available:
	- {$widgetFeedmuncherRecentArticlesFull}: contains an array with all posts, each element contains data about the post
*}

{option:widgetFeedmuncherRecentArticlesFull}
	<section id="feedmuncherFeedmuncherRecentArticlesFullWidget" class="mod">
		<div class="inner">
			<header class="hd">
				<h3>{$lblRecentArticles|ucfirst}</h3>
			</header>
			<div class="bd">
				{iteration:widgetFeedmuncherRecentArticlesFull}
					<article class="mod article">
						<div class="inner">
							<header class="hd">
								<h4><a href="{$widgetFeedmuncherRecentArticlesFull.full_url}" {option:widgetFeedmuncherRecentArticlesFull.link_to_original}class="linkToOriginal"{/option:widgetFeedmuncherRecentArticlesFull.link_to_original} title="{$widgetFeedmuncherRecentArticlesFull.title}">{$widgetFeedmuncherRecentArticlesFull.title}</a></h4>
								<ul>
									<li>{$msgWrittenBy|ucfirst|sprintf:{$widgetFeedmuncherRecentArticlesFull.user_id|usersetting:'nickname'}} {$lblOn} {$widgetFeedmuncherRecentArticlesFull.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}</li>
									{option:!widgetFeedmuncherRecentArticlesFull.link_to_original}
										<li>
											{option:!widgetFeedmuncherRecentArticlesFull.comments}<a href="{$widgetFeedmuncherRecentArticlesFull.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!widgetFeedmuncherRecentArticlesFull.comments}
											{option:widgetFeedmuncherRecentArticlesFull.comments}
												{option:widgetFeedmuncherRecentArticlesFull.comments_multiple}<a href="{$widgetFeedmuncherRecentArticlesFull.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$widgetFeedmuncherRecentArticlesFull.comments_count}}</a>{/option:widgetFeedmuncherRecentArticlesFull.comments_multiple}
												{option:!widgetFeedmuncherRecentArticlesFull.comments_multiple}<a href="{$widgetFeedmuncherRecentArticlesFull.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!widgetFeedmuncherRecentArticlesFull.comments_multiple}
											{/option:widgetFeedmuncherRecentArticlesFull.comments}
										</li>
									{/option:!widgetFeedmuncherRecentArticlesFull.link_to_original}
									<li><a href="{$widgetFeedmuncherRecentArticlesFull.category_full_url}" title="{$widgetFeedmuncherRecentArticlesFull.category_title}">{$widgetFeedmuncherRecentArticlesFull.category_title}</a></li>
									<li>{$msgSource|ucfirst|sprintf:{$widgetFeedmuncherRecentArticlesFull.source_name}:{$widgetFeedmuncherRecentArticlesFull.source_url}}</li>
								</ul>
							</header>
							<div class="bd content">
								{option:!widgetFeedmuncherRecentArticlesFull.introduction}{$widgetFeedmuncherRecentArticlesFull.text}{/option:!widgetFeedmuncherRecentArticlesFull.introduction}
								{option:widgetFeedmuncherRecentArticlesFull.introduction}{$widgetFeedmuncherRecentArticlesFull.introduction}{/option:widgetFeedmuncherRecentArticlesFull.introduction}
							</div>
						</div>
					</article>
				{/iteration:widgetFeedmuncherRecentArticlesFull}
			</div>
			<footer class="ft">
				<p>
					<a href="{$var|geturlforblock:'feedmuncher'}">{$lblArchive|ucfirst}</a>
					<a id="RSSfeed" href="{$var|geturlforblock:'feedmuncher':'rss'}">{$lblSubscribeToTheRSSFeed|ucfirst}</a>
				</p>
			</footer>
		</div>
	</section>
{/option:widgetFeedmuncherRecentArticlesFull}