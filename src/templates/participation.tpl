{capture assign='pageTitle'}{lang}siraca.participation.title{/lang}{$race->title}{/capture}

{capture assign='contentTitle'}{lang}siraca.participation.title{/lang}{$race->title}{/capture}

{include file='header'}

{include file='formError'}

<div class="section">
    <h2 class="sectionTitle">{lang}siraca.participation.form.presence{/lang}</h2>

    <form id="participationForm" method="post" action="{link controller='Participation' object=$race}{/link}">
        <div class="section">
            {include file="_participationSummary"}

            <dl {if $errorField == 'participationType'} class="formError"{/if}>
                <dt></dt>
                <dd>
                    <select name="participationType" id="participationType">
                        {foreach from=$participationTypes item=participationType}
                            <option value="{$participationType->type}"{if $participation->type == $participationType->type} selected{/if}>{lang}{$participationType->typeLangId}{/lang}</option>
                        {/foreach}
                    </select>
                    {if $errorField == 'participationType'}
                        <small class="innerError">
                            {if $errorType == 'noChange'}
                                {lang}siraca.participation.form.error.noChange{/lang}
                            {/if}
                        </small>
                    {/if}
                </dd>
            </dl>
            {event name='dataFields'}
        </div>

        <div class="section">
            <h2 class="sectionTitle">{lang}siraca.participation.estimation.title{/lang}</h2>
            <ol> 
                {foreach from=$estimatedPositions key=participationType item=estimation}
                    <li>
                        {lang}siraca.participation.type.{$participationType}{/lang}
                        {if $estimation->listType == 1}
                            {lang}siraca.participation.estimation.titular{/lang}
                        {else}
                            {lang}siraca.participation.estimation.waiting{/lang}
                        {/if}
                    </li>
                {/foreach}
            </ol>
        </div>
        
        {event name='sections'}

        <div class="formSubmit">
            <input class="submitFormButton" data-full-list-if-leaving-titular="{@$isTitularFullIfSwitchingToUnconfirmed}" type="button" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
            {@SECURITY_TOKEN_INPUT_TAG}
        </div>
    </form>
</div>

<script data-relocate="true">
    $(function() {
        //elById("submitButton").addEventListener("click", submitButtonClickHandler);
        
        $('.submitFormButton').click(
        function (event) {
            var selectedParticipationType = elById("participationType").value;
            var $target = $(event.currentTarget);
            
            if($target.data('fullListIfLeavingTitular') && selectedParticipationType == {$unconfirmedPresenceType}) {
                WCF.System.Confirmation.show("{lang}siraca.participation.estimation.leavingFullTitularWarning{/lang}", function(action) {
                    if (action === 'confirm') {
                        elById("participationForm").submit();
                    }
                });
            } else {
                elById("participationForm").submit();
            }
        })
    });
</script>

{include file='footer'}
