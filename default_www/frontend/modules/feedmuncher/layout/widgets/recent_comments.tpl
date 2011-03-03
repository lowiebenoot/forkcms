{*
	variables that are available:
	- {$widgetFeedmuncherRecentComments}: contains an array with the recent comments. Each element contains data about the comment.
*}

{option:widgetFeedmuncherRecentComments}
	<div id="feedmuncherRecentCommentsWidget" class="mod">
		<div class="inner">
			<div class="hd">
				<h3>{$lblRecentComments|ucfirst}</h3>
			</div>
			<div class="bd">
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
	</div>
{/option:widgetFeedmuncherRecentComments}