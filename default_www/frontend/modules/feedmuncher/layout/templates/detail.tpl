{*
	variables that are available:
	- {$feedmuncherArticle}: contains data about the post
	- {$feedmuncherComments}: contains an array with the comments for the post, each element contains data about the comment.
	- {$feedmuncherCommentsCount}: contains a variable with the number of comments for this feedmuncher post.
	- {$feedmuncherNavigation}: contains an array with data for previous and next post
*}

<div id="feedmuncherDetail">
	<div class="mod article">
		<div class="inner">
			<div class="hd">
				<h1>{$feedmuncherArticle.title}</h1>
				<p>
					{$feedmuncherArticle.publish_on|date:{$dateFormatLong}:{$LANGUAGE}} -
					{option:!feedmuncherComments}<a href="{$feedmuncherArticle.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!feedmuncherComments}
					{option:feedmuncherComments}
						{option:feedmuncherCommentsMultiple}<a href="{$feedmuncherArticle.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$feedmuncherCommentsCount}}</a>{/option:feedmuncherCommentsMultiple}
						{option:!feedmuncherCommentsMultiple}<a href="{$feedmuncherArticle.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!feedmuncherCommentsMultiple}
					{/option:feedmuncherComments}
				</p>
			</div>
			<div class="bd content">
				{$feedmuncherArticle.text}
			</div>
			<div class="ft">
				{$msgWrittenBy|ucfirst|sprintf:{$feedmuncherArticle.user_id|usersetting:'nickname'}}
				{$lblInTheCategory}: <a href="{$feedmuncherArticle.category_full_url}" title="{$feedmuncherArticle.category_name}">{$feedmuncherArticle.category_name}</a>.
				{option:feedmuncherArticleTags}
					{$lblTags|ucfirst}:
					{iteration:feedmuncherArticleTags}<a href="{$feedmuncherArticleTags.full_url}" rel="tag" title="{$feedmuncherArticleTags.name}">{$feedmuncherArticleTags.name}</a>{option:!feedmuncherArticleTags.last}, {/option:!feedmuncherArticleTags.last}{/iteration:feedmuncherArticleTags}
				{/option:feedmuncherArticleTags}
				{$msgSource|ucfirst|sprintf:{$feedmuncherArticle.source_name}:{$feedmuncherArticle.source_url}}
			</div>
		</div>
	</div>
	<div id="feedmuncherNavigation" class="mod">
		<div class="inner">
			<div class="bd">
				<ul>
					{option:feedmuncherNavigation.previous}
					<li class="previousLink">
						<a href="{$feedmuncherNavigation.previous.url}" rel="prev">{$lblPreviousArticle|ucfirst}: <em>{$feedmuncherNavigation.previous.title}</em></a>
					</li>
					{/option:feedmuncherNavigation.previous}
					{option:feedmuncherNavigation.next}
					<li class="nextLink">
						<a href="{$feedmuncherNavigation.next.url}" rel="next">{$lblNextArticle|ucfirst}: <em>{$feedmuncherNavigation.next.title}</em></a>
					</li>
					{/option:feedmuncherNavigation.next}
				</ul>
			</div>
		</div>
	</div>

	{option:feedmuncherComments}
	<div id="feedmuncherComments" class="mod">
		<div class="inner">
			<div class="hd">
				<h3 id="{$actComments}">{$lblComments|ucfirst}</h3>
			</div>
			{iteration:feedmuncherComments}
				{* Do not alter the id! It is used as an anchor *}
				<div id="comment-{$feedmuncherComments.id}" class="bd comment">
					<div class="imageHolder">
						{option:feedmuncherComments.website}<a href="{$feedmuncherComments.website}">{/option:feedmuncherComments.website}
							<img src="{$FRONTEND_CORE_URL}/layout/images/default_author_avatar.gif" width="48" height="48" alt="{$feedmuncherComments.author}" class="replaceWithGravatar" data-gravatar-id="{$feedmuncherComments.gravatar_id}" />
						{option:feedmuncherComments.website}</a>{/option:feedmuncherComments.website}
					</div>
					<div class="commentContent">
						<p class="commentAuthor">
							{option:feedmuncherComments.website}<a href="{$feedmuncherComments.website}">{/option:feedmuncherComments.website}{$feedmuncherComments.author}{option:feedmuncherComments.website}</a>{/option:feedmuncherComments.website}
							{$lblWrote}
							{$feedmuncherComments.created_on|timeago}
						</p>
						<div class="commentText content">
							{$feedmuncherComments.text|cleanupplaintext}
						</div>
					</div>
				</div>
			{/iteration:feedmuncherComments}
		</div>
	</div>
	{/option:feedmuncherComments}
	{option:feedmuncherArticle.allow_comments}
		<div id="feedmuncherCommentForm" class="mod">
			<div class="inner">
				<div class="hd">
					<h3>{$msgComment|ucfirst}</h3>
				</div>
				<div class="bd">
					{option:commentIsInModeration}<div class="message warning"><p>{$msgFeedmuncherCommentInModeration}</p></div>{/option:commentIsInModeration}
					{option:commentIsSpam}<div class="message error"><p>{$msgFeedmuncherCommentIsSpam}</p></div>{/option:commentIsSpam}
					{option:commentIsAdded}<div class="message success"><p>{$msgFeedmuncherCommentIsAdded}</p></div>{/option:commentIsAdded}
					{form:comment}
						<p>
							<label for="author">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$txtAuthor} {$txtAuthorError}
						</p>
						<p>
							<label for="email">{$lblEmail|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$txtEmail} {$txtEmailError}
						</p>
						<p>
							<label for="website">{$lblWebsite|ucfirst}</label>
							{$txtWebsite} {$txtWebsiteError}
						</p>
						<p>
							<label for="message">{$lblMessage|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$txtMessage} {$txtMessageError}
						</p>
						<p>
							<input class="inputSubmit" type="submit" name="comment" value="{$msgComment|ucfirst}" />
						</p>
					{/form:comment}
				</div>
			</div>
		</div>
	{/option:feedmuncherArticle.allow_comments}
</div>