{if $__wcf->user->userID && $__wcf->session->getPermission('mod.siraca.canManageRace')}
    <li>
        <a href="{if $startTimestamp|isset}
                {link controller='RaceAdd' startTimestamp=$startTimestamp}{/link}
                {else}
                {link controller='RaceAdd'}{/link}{/if}" class="button siracaAdminButton">
            <span class="icon icon16 fa-plus"></span> <span>{lang}siraca.race.add.link{/lang}</span>
        </a>
    </li>
{/if}