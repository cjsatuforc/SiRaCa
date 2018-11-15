{capture assign='pageTitle'}{lang}siraca.race.list.title{/lang}{/capture}

{capture assign='contentTitle'}{lang}siraca.race.list.title{/lang}{/capture}

{include file='header'}

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="RaceList" link="pageNo=%d"}{/content}
	</div>
{/hascontent}

{if $items}
    {include file='_raceList'}
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

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
                    {if $__wcf->user->userID && $__wcf->session->getPermission('mod.siraca.canManageRace')}
                        <li>
                            <a href="{link controller='RaceAdd'}{/link}" class="button buttonPrimary">
                                <span class="icon icon16 fa-plus"></span> <span>{lang}siraca.race.add.link{/lang}</span>
                            </a>
                        </li>
                    {/if}
                    {event name='contentFooterNavigation'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}