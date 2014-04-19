{* Purpose of this template: Display one certain review within an external context *}
<div id="review{$review.id}" class="reviews-external-review">
{if $displayMode eq 'link'}
    <p class="reviews-external-link">
    <a href="{modurl modname='Reviews' type='user' func='display' ot='review' id=$review.id slug=$review.slug}" title="{$review->getTitleFromDisplayPattern()|replace:"\"":""}">
    {$review->getTitleFromDisplayPattern()|notifyfilters:'reviews.filter_hooks.reviews.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='Reviews::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="reviews-external-title">
            <strong>{$review->getTitleFromDisplayPattern()|notifyfilters:'reviews.filter_hooks.reviews.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="reviews-external-snippet">
        {if $review.coverUpload ne ''}
          <a href="{$review.coverUploadFullPathURL}" title="{$review->getTitleFromDisplayPattern()|replace:"\"":""}"{if $review.coverUploadMeta.isImage} rel="imageviewer[review]"{/if}>
          {if $review.coverUploadMeta.isImage}
              {thumb image=$review.coverUploadFullPath objectid="review-`$review.id`" preset=$reviewThumbPresetCoverUpload tag=true img_alt=$review->getTitleFromDisplayPattern()}
          {else}
              {gt text='Download'} ({$review.coverUploadMeta.size|reviewsGetFileSize:$review.coverUploadFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if}
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="reviews-external-description">
            {if $review.text ne ''}{$review.text}<br />{/if}
            {assignedcategorieslist categories=$review.categories doctrine2=true}
        </p>
    *}
{/if}
</div>
