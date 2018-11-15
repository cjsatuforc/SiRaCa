
<section class="box">
    <h2 class="boxTitle">{lang}siraca.participation.titleShort{/lang}</h2>
    <div class="boxContent">
        <small>
            {if !$__wcf->user->userID}
                {lang}siraca.participation.deniedReason.guest{/lang}
            {elseif !$__wcf->getSession()->getPermission('user.siraca.canParticipateRace')}
                {lang}siraca.participation.deniedReason.noPermission{/lang}
            {else}
                {include file="_participationSummary"}
            {/if}
        </small>
    </div>
            
    <div class="boxContent">
        <a href="{link controller='Participation' object=$race}{/link}"class="button
                {if !$__wcf->user->userID
                || !$__wcf->getSession()->getPermission('user.siraca.canParticipateRace')}disabled{/if}">
                
            <span class="icon icon16 fa-sign-in"></span> <span>{lang}siraca.participation.link{/lang}</span>
        </a>
    </div>
</section>