{*
	variables that are available:
	- {$items}: contains an array with all posts, each element contains data about the post
*}

{option:!items}
	<div id="feedmuncherIndex">
		<section class="mod">
			<div class="inner">
				<div class="bd content">
					<p>{$msgFeedmuncherNoItems}</p>
				</div>
			</div>
		</section>
	</div>
{/option:!items}
{option:items}
	<div id="feedmuncherIndex">
		{iteration:items}
			<article class="mod">
				<div class="inner">
					<header class="hd">
						<h3><a href="{$items.full_url}" {option:items.link_to_original}class="linkToOriginal"{/option:items.link_to_original} title="{$items.title}">{$items.title}</a></h3>
						<ul>
							<li>
								{* Written by *}
								{$msgWrittenBy|ucfirst|sprintf:{$items.user_id|usersetting:'nickname'}}

								{* Written on *}
								{$lblOn} {$items.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}

								{* Category*}
								{$lblIn} {$lblThe} {$lblCategory} <a href="{$items.category_full_url}" title="{$items.category_title}">{$items.category_title}</a>{option:!items.tags}.{/option:!items.tags}

								{* Tags*}
								{option:items.tags}
									{$lblWith} {$lblThe} {$lblTags}
									{iteration:items.tags}
										<a href="{$items.tags.full_url}" rel="tag" title="{$items.tags.name}">{$items.tags.name}</a>{option:!items.tags.last}, {/option:!items.tags.last}{option:items.tags.last}.{/option:items.tags.last}
									{/iteration:items.tags}
								{/option:items.tags}
							</li>
							{option:!items.link_to_original}
							<li>
								{* Comments *}
								{option:!items.comments}<a href="{$items.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!items.comments}
								{option:items.comments}
									{option:items.comments_multiple}<a href="{$items.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$items.comments_count}}</a>{/option:items.comments_multiple}
									{option:!items.comments_multiple}<a href="{$items.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!items.comments_multiple}
								{/option:items.comments}
							</li>
							{/option:!items.link_to_original}
							<li>
								{* Source *}
								{$msgSource|ucfirst|sprintf:{$items.source_name}:{$items.source_url}}
							</li>
						</ul>
					</header>
					<div class="bd content">
						{option:!items.introduction}{$items.text}{/option:!items.introduction}
						{option:items.introduction}{$items.introduction}{/option:items.introduction}
					</div>
				</div>
			</article>
		{/iteration:items}
	</div>
	{include:core/layout/templates/pagination.tpl}
{/option:items}
