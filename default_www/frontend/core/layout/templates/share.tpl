{option:shareOptions}
	<div class="shareOptions">
		{option:facebook_share_url}
			<div class="shareOption facebook">
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
				<fb:like href="{$facebook_share_url}" show_faces="false" width="300" font=""></fb:like>
			</div>
		{/option:facebook_share_url}

		{option:twitter_share_url}
			<div class="shareOption twitter">
				<p>
					<a href="http://twitter.com?status={$twitter_share_url}" title="{$lblShareOnTwitter}">{$lblShareOnTwitter}</a>
				</p>
			</div>
		{/option:twitter_share_url}

		{option:delicious_share_url}
			<div class="shareOption twitter">
				<p>
					<a href="http://www.delicious.com/save?url={$delicious_share_url}&amp;title={$delicious_share_title}" title="{$lblShareOnDelicious}">{$lblShareOnDelicious}</a>
				</p>
			</div>
		{/option:delicious_share_url}

		{option:stumbleupon_share_url}
			<div class="shareOption stumbleupon">
				<p>
					<a href="http://www.stumbleupon.com/submit?url={$stumbleupon_share_url}" title="{$lblShareOnStumbleupon}">{$lblShareOnStumbleupon}</a>
				</p>
			</div>
		{/option:stumbleupon_share_url}

		{option:linkedin_share_url}
			<div class="shareOption linkedin">
				<p>
					<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={$linkedin_share_url}&amp;title={$linkedin_share_title}" title="{$lblShareOnLinkedin}">{$lblShareOnLinkedin}</a>
				</p>
			</div>
		{/option:linkedin_share_url}

		{option:reddit_share_url}
			<div class="shareOption reddit">
				<p>
					<a href="http://www.reddit.com/submit?url={$reddit_share_url}&amp;title={$reddit_share_title}" title="{$lblShareOnReddit}">{$lblShareOnReddit}</a>
				</p>
			</div>
		{/option:reddit_share_url}

		{option:netlog_share_url}
			<div class="shareOption netlog">
				<p>
					<a href="http://www.netlog.com/go/manage/links/view=save&amp;origin=external&amp;url={$netlog_share_url}&amp;title={$netlog_share_title}" title="{$lblShareOnNetlog}">{$lblShareOnNetlog}</a>
				</p>
			</div>
		{/option:netlog_share_url}

		{option:digg_share_url}
			<div class="shareOption digg">
				<p>
					<a href="http://digg.com/submit?url={$digg_share_url}&amp;title={$digg_share_title}" title="{$lblShareOnDigg}">{$lblShareOnDigg}</a>
				</p>
			</div>
		{/option:digg_share_url}

		{option:tumblr_share_url}
			<div class="shareOption tumblr">
				<p>
					<a href="http://www.tumblr.com/share?v=3&amp;u={$tumblr_share_url}&amp;t={$tumblr_share_title}" title="{$lblShareOnTumblr}">{$lblShareOnTumblr}</a>
				</p>
			</div>
		{/option:tumblr_share_url}

		{option:googlebuzz_share_url}
			<div class="shareOption buzz">
				<p>
					<a href="http://www.google.com/buzz/post?url={$googlebuzz_share_url}&amp;title={$googlebuzz_share_title}" title="{$lblShareOnGoogleBuzz}">{$lblShareOnGoogleBuzz}</a>
				</p>
			</div>
		{/option:googlebuzz_share_url}
	</div>
{/option:shareOptions}