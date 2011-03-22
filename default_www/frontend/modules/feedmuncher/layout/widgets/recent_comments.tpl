{*
	variables that are available:
	- {$widgetFeedmuncherRecentComments}: contains an array with the recent comments. Each element contains data about the comment.
*}

{option:widgetFeedmuncherRecentComments}
	<section id="feedmuncherRecentCommentsWidget" class="mod">
		<div class="inner">
			<header class="hd">
				<h3>{$lblRecentComments|ucfirst}</h3>
			</header>
			<div class="bd content">
				<ul>
					{iteration:widgetFeedmuncherRecentComments}
						<li>
							{option:widgetFeedmuncherRecentComments.website}<a href="{$widgetFeedmuncherRecentComments.website}" rel="nofollow">{/option:widgetFeedmuncherRecentComments.website}
								{$widgetFeedmuncherRecentComments.author}
							{option:widgetFeedmuncherRecentComments.website}</a>{/option:widgetFeedmuncherRecentComments.website}
							{$lblCommentedOn} <a href="{$widgetFeedmuncherRecentComments.full_url}">{$widgetFeedmuncherRecentComments.post_title}</a>
						</li>
					{/iteration:widgetFeedmuncherRecentComments}
				</ul>
			</div>
		</div>
	</section>
{/option:widgetFeedmuncherRecentComments}