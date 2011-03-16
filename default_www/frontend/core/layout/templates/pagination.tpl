{option:pagination}
	{option:pagination.multiple_pages}
		<div class="mod pagination">
			<div class="bd">
				<ul>
					<li class="previousPage">
						{option:pagination.show_previous}<a href="{$pagination.previous_url}" rel="prev nofollow" title="{$lblPreviousPage|ucfirst}">{/option:pagination.show_previous}
						{option:!pagination.show_previous}<span>{/option:!pagination.show_previous}
							&lsaquo; {$lblPreviousPage|ucfirst}
						{option:!pagination.show_previous}</span>{/option:!pagination.show_previous}
						{option:pagination.show_previous}</a>{/option:pagination.show_previous}
					</li>

					{option:pagination.first}
						{iteration:pagination.first}<li><a href="{$paginationFirst.url}" rel="nofollow" title="{$lblGoToPage|ucfirst} {$paginationFirst.label}">{$paginationFirst.label}</a></li>{/iteration:pagination.first}
						<li class="ellipsis"><span>&hellip;</span></li>
					{/option:pagination.first}

					{iteration:pagination.pages}
						<li{option:pagination.pages.current} class="currentPage"{/option:pagination.pages.current}>
							{option:!pagination.pages.current}<a href="{$pagination.pages.url}" rel="nofollow" title="{$lblGoToPage|ucfirst} {$pagination.pages.label}">{/option:!pagination.pages.current}
							{option:pagination.pages.current}<span>{/option:pagination.pages.current}
								{$pagination.pages.label}
							{option:pagination.pages.current}</span>{/option:pagination.pages.current}
							{option:!pagination.pages.current}</a>{/option:!pagination.pages.current}
						</li>
					{/iteration:pagination.pages}

					{option:pagination.last}
						<li class="ellipsis"><span>&hellip;</span></li>
						{iteration:pagination.last}<li><a href="{$paginationLast.url}" rel="nofollow" title="{$lblGoToPage|ucfirst} {$paginationLast.label}">{$paginationLast.label}</a></li>{/iteration:pagination.last}
					{/option:pagination.last}

					<li class="nextPage">
						{option:pagination.show_next}<a href="{$pagination.next_url}" rel="next nofollow" title="{$lblNextPage|ucfirst}">{/option:pagination.show_next}
						{option:!pagination.show_next}<span>{/option:!pagination.show_next}
							{$lblNextPage|ucfirst} &#8250;
						{option:!pagination.show_next}</span>{/option:!pagination.show_next}
						{option:pagination.show_next}</a>{/option:pagination.show_next}
					</li>
				</ul>
			</div>
		</div>
	{/option:pagination.multiple_pages}
{/option:pagination}