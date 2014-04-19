{* Purpose of this template: Display reviews within an external context *}
<dl>
    {foreach item='review' from=$items}
        <dt>{$review->getTitleFromDisplayPattern()}</dt>
        {if $review.text}
            <dd>{$review.text|strip_tags|truncate:200:'&hellip;'}</dd>
        {/if}
        <dd><a href="{modurl modname='Reviews' type='user' func='display' ot=$objectType id=$review.id slug=$review.slug}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>
