{* purpose of this template: header for user area *}
{pageaddvar name='javascript' value='prototype'}
{pageaddvar name='javascript' value='validation'}
{pageaddvar name='javascript' value='zikula'}
{pageaddvar name='javascript' value='livepipe'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='zikula.imageviewer'}
{pageaddvar name='javascript' value='modules/Reviews/javascript/Reviews.js'}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <div class="z-frontendbox">
        <h2>{gt text='Reviews' comment='This is the title of the header template'}</h2>
        {modulelinks modname='Reviews' type='user'}
    </div>
    {nocache}
        {reviewsModerationObjects assign='moderationObjects'}
        {if count($moderationObjects) gt 0}
            {foreach item='modItem' from=$moderationObjects}
                <p class="z-informationmsg z-center">
                    <a href="{modurl modname='Reviews' type='admin' func='view' ot=$modItem.objectType workflowState=$modItem.state}" class="z-bold">{$modItem.message}</a>
                </p>
            {/foreach}
        {/if}
    {/nocache}
{/if}
{insert name='getstatusmsg'}
