{capture assign='pageTitle'}{lang}siraca.race.list.title{/lang}{/capture}
{capture assign='contentTitle'}{lang}siraca.race.list.title{/lang}{/capture}

{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="RaceList" link="pageNo=%d"}{/content}
	</div>
{/hascontent}

{include file='_raceList'}

<footer class="contentFooter">
    {hascontent}
        <div class="paginationBottom">
            {content}{@$pagesLinks}{/content}
        </div>
    {/hascontent}

    {hascontent}
        <nav class="contentFooterNavigation">
            <ul>
                {content}
                    {include file="_organizeRaceButton"}
                    
                    {event name='contentFooterNavigation'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}