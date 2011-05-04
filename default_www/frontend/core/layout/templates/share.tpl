{option:shareOptions}
	<div class="shareOptions">
		{option:facebook_share_url}
			<div class="shareOption facebook">
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
				<fb:like href="http://lowiebenoot.be/blog/detail/feedmuncher-banners-translations-en-share" show_faces="false" width="300" font=""></fb:like>
			</div>
		{/option:facebook_share_url}

		{option:twitter_share_url}
			<div class="shareOption twitter">
				<p>
					<a href="http://twitter.com/?status={$twitter_share_url}" title="{$lblShareOnTwitter}">{$lblShareOnTwitter}</a>
				</p>
			</div>
		{/option:twitter_share_url}

		{option:delicious_share_url}
			<div class="shareOption twitter">
				<p>
					<a href="http://www.delicious.com/save?url={$delicious_share_url}" title="{$lblShareOnDelicious}">{$lblShareOnDelicious}</a>
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
	</div>
{/option:shareOptions}