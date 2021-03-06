{* purpose of this template: inclusion template for display of related reviews in admin area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
{if isset($items) && $items ne null && count($items) gt 0}
<ul class="reviews-related-item-list review">
{foreach name='relLoop' item='item' from=$items}
    <li>
{strip}
{if !$nolink}
    <a href="{modurl modname='Reviews' type='admin' func='display' ot='review' id=$item.id slug=$item.slug}" title="{$item->getTitleFromDisplayPattern()|replace:"\"":""}">
{/if}
    {$item->getTitleFromDisplayPattern()}
{if !$nolink}
    </a>
    <a id="reviewItem{$item.id}Display" href="{modurl modname='Reviews' type='admin' func='display' ot='review' id=$item.id slug=$item.slug theme='Printer'}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        reviewsInitInlineWindow($('reviewItem{{$item.id}}Display'), '{{$item->getTitleFromDisplayPattern()|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}
<br />
{if $item.coverUpload ne '' && isset($item.coverUploadFullPath) && $item.coverUploadMeta.isImage}
    {thumb image=$item.coverUploadFullPath objectid="review-`$item.id`" preset=$relationThumbPreset tag=true img_alt=$item->getTitleFromDisplayPattern()}
{/if}
    </li>
{/foreach}
</ul>
{/if}
