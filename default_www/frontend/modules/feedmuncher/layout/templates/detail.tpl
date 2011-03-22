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




{*
	variables that are available:
	- {$item}: contains data about the post
	- {$comments}: contains an array with the comments for the post, each element contains data about the comment.
	- {$commentsCount}: contains a variable with the number of comments for this post.
	- {$navigation}: contains an array with data for previous and next post
*}
<div id="feedmuncherDetail">
	<article class="mod article">
		<div class="inner">
			<header class="hd">
				<h1>{$item.title}</h1>
				<ul>
					<li>{$msgWrittenBy|ucfirst|sprintf:{$item.user_id|usersetting:'nickname'}} {$lblOn} {$item.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}</li>
					<li>
						{option:!comments}<a href="{$item.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!comments}
						{option:comments}
							{option:feedmuncherCommentsMultiple}<a href="{$item.full_url}#{$actComments}">{$msgBlogNumberOfComments|sprintf:{$commentsCount}}</a>{/option:feedmuncherCommentsMultiple}
							{option:!feedmuncherCommentsMultiple}<a href="{$item.full_url}#{$actComments}">{$msgBlogOneComment}</a>{/option:!feedmuncherCommentsMultiple}
						{/option:comments}
					</li>
					<li><a href="{$item.category_full_url}" title="{$item.category_title}">{$item.category_title}</a></li>
				</ul>
			</header>
			<div class="bd content">
				{$item.text}
			</div>
			<footer class="ft">
				<ul class="pageNavigation">
					{option:navigation.previous}
						<li class="previousLink">
							<a href="{$navigation.previous.url}" rel="prev">{$lblPreviousArticle|ucfirst}: {$navigation.previous.title}</a>
						</li>
					{/option:navigation.previous}
					{option:navigation.next}
						<li class="nextLink">
							<a href="{$navigation.next.url}" rel="next">{$lblNextArticle|ucfirst}: {$navigation.next.title}</a>
						</li>
					{/option:navigation.next}
				</ul>
			</footer>
		</div>
	</article>

	{option:comments}
		<section id="feedmuncherComments" class="mod">
			<div class="inner">
				<header class="hd">
					<h3 id="{$actComments}">{$lblComments|ucfirst}</h3>
				</header>
				<div class="bd content">
					{iteration:comments}
						{* Do not alter the id! It is used as an anchor *}
						<div id="comment-{$comments.id}" class="comment">
							<div class="imageHolder">
								{option:comments.website}<a href="{$comments.website}">{/option:comments.website}
									<img src="{$FRONTEND_CORE_URL}/layout/images/default_author_avatar.gif" width="48" height="48" alt="{$comments.author}" class="replaceWithGravatar" data-gravatar-id="{$comments.gravatar_id}" />
								{option:comments.website}</a>{/option:comments.website}
							</div>
							<div class="commentContent">
								<p class="commentAuthor">
									{option:comments.website}<a href="{$comments.website}">{/option:comments.website}{$comments.author}{option:comments.website}</a>{/option:comments.website}
									{$lblWrote}
									{$comments.created_on|timeago}
								</p>
								<div class="commentText content">
									{$comments.text|cleanupplaintext}
								</div>
							</div>
						</div>
					{/iteration:comments}
				</div>
			</div>
		</section>
	{/option:comments}
	{option:item.allow_comments}
		<section id="feedmuncherCommentForm" class="mod">
			<div class="inner">
				<header class="hd">
					<h3>{$msgComment|ucfirst}</h3>
				</header>
				<div class="bd">
					{option:commentIsInModeration}<div class="message warning"><p>{$msgFeedmuncherCommentInModeration}</p></div>{/option:commentIsInModeration}
					{option:commentIsSpam}<div class="message error"><p>{$msgFeedmuncherCommentIsSpam}</p></div>{/option:commentIsSpam}
					{option:commentIsAdded}<div class="message success"><p>{$msgFeedmuncherCommentIsAdded}</p></div>{/option:commentIsAdded}
					{form:comment}
						<div class="alignBlocks">
							<p {option:txtAuthorError}class="errorArea"{/option:txtAuthorError}>
								<label for="author">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
								{$txtAuthor} {$txtAuthorError}
							</p>
							<p {option:txtEmailError}class="errorArea"{/option:txtEmailError}>
								<label for="email">{$lblEmail|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
								{$txtEmail} {$txtEmailError}
							</p>
						</div>
						<p class="bigInput{option:txtWebsiteError} errorArea{/option:txtWebsiteError}">
							<label for="website">{$lblWebsite|ucfirst}</label>
							{$txtWebsite} {$txtWebsiteError}
						</p>
						<p class="bigInput{option:txtMessageError} errorArea{/option:txtMessageError}">
							<label for="message">{$lblMessage|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$txtMessage} {$txtMessageError}
						</p>
						<p>
							<input class="inputSubmit" type="submit" name="comment" value="{$msgComment|ucfirst}" />
						</p>
					{/form:comment}
				</div>
			</div>
		</section>
	{/option:item.allow_comments}
</div>