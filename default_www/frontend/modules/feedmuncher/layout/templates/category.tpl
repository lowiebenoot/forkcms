{*
	variables that are available:
	- {$feedmuncherCategory}: contains data about the category
	- {$feedmuncherArticles}: contains an array with all posts, each element contains data about the post
*}

{option:feedmuncherArticles}
	<div id="feedmuncherCategory">
		{iteration:feedmuncherArticles}
			<div class="mod article">
				<div class="inner">
					<div class="hd">
						<h2><a href="{$feedmuncherArticles.full_url}" title="{$feedmuncherArticles.title}">{$feedmuncherArticles.title}</a></h2>
						<p>{$feedmuncherArticles.publish_on|date:{$dateFormatLong}:{$LANGUAGE}|ucfirst} -
						{option:!feedmuncherArticles.comments}<a href="{$feedmuncherArticles.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!feedmuncherArticles.comments}
						{option:feedmuncherArticles.comments}
							{option:feedmuncherArticles.comments_multiple}<a href="{$feedmuncherArticles.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$feedmuncherArticles.comments_count}}</a>{/option:feedmuncherArticles.comments_multiple}
							{option:!feedmuncherArticles.comments_multiple}<a href="{$feedmuncherArticles.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!feedmuncherArticles.comments_multiple}
						{/option:feedmuncherArticles.comments}
						</p>
					</div>
					<div class="bd content">
						{option:!feedmuncherArticles.introduction}{$feedmuncherArticles.text}{/option:!feedmuncherArticles.introduction}
						{option:feedmuncherArticles.introduction}{$feedmuncherArticles.introduction}{/option:feedmuncherArticles.introduction}
					</div>
					<div class="ft">
						<p>
							{$msgWrittenBy|ucfirst|sprintf:{$feedmuncherArticles.user_id|usersetting:'nickname'}} {$lblInTheCategory}: <a href="{$feedmuncherArticles.category_full_url}" title="{$feedmuncherArticles.category_name}">{$feedmuncherArticles.category_name}</a>. {option:feedmuncherArticles.tags}{$lblTags|ucfirst}: {iteration:feedmuncherArticles.tags}<a href="{$tags.full_url}" rel="tag" title="{$tags.name}">{$tags.name}</a>{option:!tags.last}, {/option:!tags.last}{/iteration:feedmuncherArticles.tags}{/option:feedmuncherArticles.tags}
						</p>
					</div>
				</div>
			</div>
		{/iteration:feedmuncherArticles}
	</div>
	{include:{$FRONTEND_CORE_PATH}/layout/templates/pagination.tpl}
{/option:feedmuncherArticles}
