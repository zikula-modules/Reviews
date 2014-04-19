{* Purpose of this template: Display reviews within an external context *}
{foreach item='review' from=$items}
    <h3>{$review->getTitleFromDisplayPattern()}</h3>
    <p><a href="{modurl modname='Reviews' type='user' func='display' ot=$objectType id=$review.id slug=$review.slug}">{gt text='Read more'}</a>
    </p>
{/foreach}
