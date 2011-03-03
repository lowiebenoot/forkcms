{*
	variables that are available:
	- {$widgetFeedmuncherRecentArticlesFull}: contains an array with all posts, each element contains data about the post
*}

{option:widgetFeedmuncherRecentArticlesFull}
	<div id="feedmuncherFeedmuncherRecentArticlesFullWidget" class="mod">
		<div class="inner">
			<div class="hd">
				<h3>{$lblRecentArticles|ucfirst}</h3>
			</div>
			<div class="bd">
				{iteration:widgetFeedmuncherRecentArticlesFull}
					<div class="mod article">
						<div class="inner">
							<div class="hd">
								<h4><a href="{$widgetFeedmuncherRecentArticlesFull.full_url}" title="{$widgetFeedmuncherRecentArticlesFull.title}">{$widgetFeedmuncherRecentArticlesFull.title}</a></h4>
								<p>{$widgetFeedmuncherRecentArticlesFull.publish_on|date:{$dateFormatLong}:{$LANGUAGE}|ucfirst} -
								{option:!widgetFeedmuncherRecentArticlesFull.comments}<a href="{$widgetFeedmuncherRecentArticlesFull.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!widgetFeedmuncherRecentArticlesFull.comments}
								{option:widgetFeedmuncherRecentArticlesFull.comments}
									{option:widgetFeedmuncherRecentArticlesFull.comments_multiple}<a href="{$widgetFeedmuncherRecentArticlesFull.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$widgetFeedmuncherRecentArticlesFull.comments_count}}</a>{/option:widgetFeedmuncherRecentArticlesFull.comments_multiple}
									{option:!widgetFeedmuncherRecentArticlesFull.comments_multiple}<a href="{$widgetFeedmuncherRecentArticlesFull.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!widgetFeedmuncherRecentArticlesFull.comments_multiple}
								{/option:widgetFeedmuncherRecentArticlesFull.comments}
								</p>
							</div>
							<div class="bd content">
								{option:!widgetFeedmuncherRecentArticlesFull.introduction}{$widgetFeedmuncherRecentArticlesFull.text}{/option:!widgetFeedmuncherRecentArticlesFull.introduction}
								{option:widgetFeedmuncherRecentArticlesFull.introduction}{$widgetFeedmuncherRecentArticlesFull.introduction}{/option:widgetFeedmuncherRecentArticlesFull.introduction}
							</div>
							<div class="ft">
								<p>
									{$msgWrittenBy|ucfirst|sprintf:{$widgetFeedmuncherRecentArticlesFull.user_id|usersetting:'nickname'}} {$lblInTheCategory}: <a href="{$widgetFeedmuncherRecentArticlesFull.category_full_url}" title="{$widgetFeedmuncherRecentArticlesFull.category_name}">{$widgetFeedmuncherRecentArticlesFull.category_name}</a>. {option:widgetFeedmuncherRecentArticlesFull.tags}{$lblTags|ucfirst}: {iteration:widgetFeedmuncherRecentArticlesFull.tags}<a href="{$tags.full_url}" rel="tag" title="{$tags.name}">{$tags.name}</a>{option:!tags.last}, {/option:!tags.last}{/iteration:widgetFeedmuncherRecentArticlesFull.tags}{/option:widgetFeedmuncherRecentArticlesFull.tags}
									{$msgSource|ucfirst|sprintf:{$widgetFeedmuncherRecentArticlesFull.source_name}:{$widgetFeedmuncherRecentArticlesFull.source_url}}
								</p>
							</div>
						</div>
					</div>
				{/iteration:widgetFeedmuncherRecentArticlesFull}
			</div>
			<div class="ft">
				<p>
					<a href="{$var|geturlforblock:'feedmuncher'}">{$lblFeedmuncherArchive|ucfirst}</a>
					<a id="RSSfeed" href="{$var|geturlforblock:'feedmuncher':'rss'}">{$lblSubscribeToTheRSSFeed|ucfirst}</a>
				</p>
			</div>
		</div>
	</div>
{/option:widgetFeedmuncherRecentArticlesFull}