{*
	variables that are available:
	- {$feedmuncherCategory}: contains data about the category
	- {$feedmuncherArticles}: contains an array with all posts, each element contains data about the post
*}

{option:feedmuncherArticles}
	<div id="feedmuncherArchive" class="mod">
		<div class="inner">
			<div class="bd content">
				<table class="datagrid" width="100%">
					<thead>
						<tr>
							<th class="date">{$lblDate|ucfirst}</th>
							<th class="title">{$lblTitle|ucfirst}</th>
							<th class="comments">{$lblComments|ucfirst}</th>
						</tr>
					</thead>
					<tbody>
						{iteration:feedmuncherArticles}
							<tr>
								<td class="date">{$feedmuncherArticles.publish_on|date:{$dateFormatShort}:{$LANGUAGE}}</td>
								<td class="title"><a href="{$feedmuncherArticles.full_url}" title="{$feedmuncherArticles.title}">{$feedmuncherArticles.title}</a></td>
								<td class="comments">
									{option:!feedmuncherArticles.comments}<a href="{$feedmuncherArticles.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!feedmuncherArticles.comments}
									{option:feedmuncherArticles.comments}
										{option:feedmuncherArticles.comments_multiple}<a href="{$feedmuncherArticles.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$feedmuncherArticles.comments_count}}</a>{/option:feedmuncherArticles.comments_multiple}
										{option:!feedmuncherArticles.comments_multiple}<a href="{$feedmuncherArticles.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!feedmuncherArticles.comments_multiple}
									{/option:feedmuncherArticles.comments}
								</td>
							</tr>
						{/iteration:feedmuncherArticles}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	{include:{$FRONTEND_CORE_PATH}/layout/templates/pagination.tpl}
{/option:feedmuncherArticles}
