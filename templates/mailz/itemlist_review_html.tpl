{* Purpose of this template: Display reviews in html mailings *}
{*
<ul>
{foreach item='review' from=$items}
    <li>
        <a href="{modurl modname='Reviews' type='user' func='display' ot=$objectType id=$review.id slug=$review.slug fqurl=true}">{$review->getTitleFromDisplayPattern()}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No reviews found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_review_display_description.tpl'}
