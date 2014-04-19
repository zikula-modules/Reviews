{* Purpose of this template: Display reviews in text mailings *}
{foreach item='review' from=$items}
{$review->getTitleFromDisplayPattern()}
{modurl modname='Reviews' type='user' func='display' ot=$objectType id=$review.id slug=$review.slug fqurl=true}
-----
{foreachelse}
{gt text='No reviews found.'}
{/foreach}
