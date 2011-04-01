<div class="shareOptions">
	{option:facebook}
		<div class="shareOption facebook">
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<fb:like href="{$facebook_share_url}" show_faces="false" width="300" font=""></fb:like>
		</div>
	{/option:facebook}

	{option:twitter}
		<div class="shareOption twitter">
			<p>
				<a href="http://twitter.com/?status={$twitter_share_url}" title="{$lblShareOnTwitter}">{$lblShareOnTwitter}</a>
			</p>
		</div>
	{/option:twitter}

	{option:delicious}
		<div class="shareOption twitter">
			<p>
				<a href="http://www.delicious.com/save?url={$delicious_share_url}" title="{$lblShareOnDelicious}">{$lblShareOnDelicious}</a>
			</p>
		</div>
	{/option:delicious}
</div>