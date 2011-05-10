{option:shareOptions}
	<div class="shareOptions">
		{option:facebook_share_url}
			<div class="shareOption facebook">
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
				<fb:like href="{$facebook_share_url}" ref="{$facebook_share_id}" show_faces="false" width="300"></fb:like>
			</div>
		{/option:facebook_share_url}

		{option:twitter_share_url}
			<div class="shareOption twitter">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$twitter_share_id}&amp;url={$twitter_share_url|urlencode}" data-url="{$twitter_share_url}" title="{$lblShareOnTwitter}">{$lblShareOnTwitter}</a>
				</p>
			</div>
		{/option:twitter_share_url}

		{option:delicious_share_url}
			<div class="shareOption twitter">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$delicious_share_id}&amp;url={$delicious_share_url}" data-url="{$delicious_share_url}" title="{$lblShareOnDelicious}">{$lblShareOnDelicious}</a>
				</p>
			</div>
		{/option:delicious_share_url}

		{option:stumbleupon_share_url}
			<div class="shareOption stumbleupon">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$stumbleupon_share_id}&amp;url={$stumbleupon_share_url}" data-url="{$stumbleupon_share_url}" title="{$lblShareOnStumbleupon}">{$lblShareOnStumbleupon}</a>
				</p>
			</div>
		{/option:stumbleupon_share_url}

		{option:linkedin_share_url}
			<div class="shareOption linkedin">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$linkedin_share_id}&amp;url={$linkedin_share_url}" data-url="{$linkedin_share_url}" title="{$lblShareOnLinkedin}">{$lblShareOnLinkedin}</a>
				</p>
			</div>
		{/option:linkedin_share_url}

		{option:reddit_share_url}
			<div class="shareOption reddit">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$reddit_share_id}&amp;url={$reddit_share_url}" data-url="{$reddit_share_url}" title="{$lblShareOnReddit}">{$lblShareOnReddit}</a>
				</p>
			</div>
		{/option:reddit_share_url}

		{option:netlog_share_url}
			<div class="shareOption netlog">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$netlog_share_id}&amp;url={$netlog_share_url}" data-url="{$netlog_share_url}" title="{$lblShareOnNetlog}">{$lblShareOnNetlog}</a>
				</p>
			</div>
		{/option:netlog_share_url}

		{option:digg_share_url}
			<div class="shareOption digg">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$digg_share_id}&amp;url={$digg_share_url}" data-url="{$digg_share_url}" title="{$lblShareOnDigg}">{$lblShareOnDigg}</a>
				</p>
			</div>
		{/option:digg_share_url}

		{option:tumblr_share_url}
			<div class="shareOption tumblr">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$tumblr_share_id}&amp;url={$tumblr_share_url}" data-url="{$tumblr_share_url}" title="{$lblShareOnTumblr}">{$lblShareOnTumblr}</a>
				</p>
			</div>
		{/option:tumblr_share_url}

		{option:googlebuzz_share_url}
			<div class="shareOption buzz">
				<p>
					<a href="/frontend/ajax.php?module=share&amp;action=tracker&amp;&amp;language={$FRONTEND_LANGUAGE}&amp;id={$googlebuzz_share_id}&amp;url={$googlebuzz_share_url}" data-url="{$googlebuzz_share_url}" title="{$lblShareOnGoogleBuzz}">{$lblShareOnGoogleBuzz}</a>
				</p>
			</div>
		{/option:googlebuzz_share_url}
	</div>
{/option:shareOptions}