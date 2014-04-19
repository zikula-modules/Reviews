{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="review{$review.id}">
<dt>{$review->getTitleFromDisplayPattern()|notifyfilters:'reviews.filter_hooks.reviews.filter'|htmlentities}</dt>
<dd>{if $review.coverUpload ne ''}
  <a href="{$review.coverUploadFullPathURL}" title="{$review->getTitleFromDisplayPattern()|replace:"\"":""}"{if $review.coverUploadMeta.isImage} rel="imageviewer[review]"{/if}>
  {if $review.coverUploadMeta.isImage}
      {thumb image=$review.coverUploadFullPath objectid="review-`$review.id`" preset=$reviewThumbPresetCoverUpload tag=true img_alt=$review->getTitleFromDisplayPattern()}
  {else}
      {gt text='Download'} ({$review.coverUploadMeta.size|reviewsGetFileSize:$review.coverUploadFullPath:false:false})
  {/if}
  </a>
{else}&nbsp;{/if}
</dd>
{if $review.text ne ''}<dd>{$review.text}</dd>{/if}
<dd>{assignedcategorieslist categories=$review.categories doctrine2=true}</dd>
</dl>
