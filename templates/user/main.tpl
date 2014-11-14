{* purpose of this template: files main view in user area *}
{*  $Id: reviews_user_main.htm 416 2010-06-14 04:52:19Z drak $  *}
{gt text='Reviews index' assign=templatetitle}
{include file='user/header.tpl'}

<div id="reviews-popular">
    <h3>{gt text='10 Most Popular Reviews'}</h3>
    <ol>
        {foreach from=$popularreviews item='review'}
        {if $shorturls && $addcategorytitletopermalink}
        <li><a href="{modurl modname='Reviews' type='user' func='display' id=$review.id cat=$review.categories.category.name}">{$review.title}</a></li>
        {else}
        <li><a href="{modurl modname='Reviews' type='user' func='display' id=$review.id}">{$review.title}</a></li>
        {/if}
        {/foreach}
    </ol>
</div>

<div id="reviews-recent">
    <h3>{gt text='10 Most Recent Reviews'}</h3>
    <ol>
        {foreach from=$recentreviews item='review2'}
        {if $shorturls && $addcategorytitletopermalink}
        <li><a href="{modurl modname='Reviews' type='user' func='display' id=$review2.id cat=$review2.categories.category.name}">{$review2.title}</a></li>
        {else}
        <li><a href="{modurl modname='Reviews' type='user' func='display' id=$review2.id}">{$review2.title}</a></li>
        {/if}
        {/foreach}
    </ol>
</div>
