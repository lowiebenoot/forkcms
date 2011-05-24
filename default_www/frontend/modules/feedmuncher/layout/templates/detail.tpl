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
					<li>
						{* Written by *}
						{$msgWrittenBy|ucfirst|sprintf:{$item.user_id|usersetting:'nickname'}}

						{* Written on *}
						{$lblOn} {$item.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}

						{* Category*}
						{$lblIn} {$lblThe} {$lblCategory} <a href="{$item.category_full_url}" title="{$item.category_title}">{$item.category_title}</a>{option:!item.tags}.{/option:!item.tags}

						{* Tags*}
						{option:item.tags}
							{$lblWith} {$lblThe} {$lblTags}
							{iteration:item.tags}
								<a href="{$item.tags.full_url}" rel="tag" title="{$item.tags.name}">{$item.tags.name}</a>{option:!item.tags.last}, {/option:!item.tags.last}{option:item.tags.last}.{/option:item.tags.last}
							{/iteration:item.tags}
						{/option:item.tags}
					</li>
					<li>
						{* Comments *}
						{option:!comments}<a href="{$item.full_url}#{$actComment}">{$msgFeedmuncherNoComments|ucfirst}</a>{/option:!comments}
						{option:comments}
							{option:feedmuncherCommentsMultiple}<a href="{$item.full_url}#{$actComments}">{$msgFeedmuncherNumberOfComments|sprintf:{$commentsCount}}</a>{/option:feedmuncherCommentsMultiple}
							{option:!feedmuncherCommentsMultiple}<a href="{$item.full_url}#{$actComments}">{$msgFeedmuncherOneComment}</a>{/option:!feedmuncherCommentsMultiple}
						{/option:comments}
					</li>
					<li>{$msgSource|ucfirst|sprintf:{$item.source_name}:{$item.source_url}}</li>
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
					<h3 id="{$actComment}">{$msgComment|ucfirst}</h3>
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