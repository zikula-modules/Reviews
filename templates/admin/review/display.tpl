{* purpose of this template: reviews display view in admin area *}
{include file='admin/header.tpl'}
<div class="reviews-review reviews-display">
    {gt text='Review' assign='templateTitle'}
    {assign var='templateTitle' value=$review->getTitleFromDisplayPattern()|default:$templateTitle}
    {pagesetvar name='title' value=$templateTitle|@html_entity_decode}
    <div class="z-admin-content-pagetitle">
        {icon type='display' size='small' __alt='Details'}
        <h3>{$templateTitle|notifyfilters:'reviews.filter_hooks.reviews.filter'} <small>({$review.workflowState|reviewsObjectState:false|lower})</small>{icon id='itemActionsTrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
    </div>

    <dl>
       {* <dt>{gt text='State'}</dt>
        <dd>{$review.workflowState|reviewsGetListEntry:'review':'workflowState'|safetext}</dd>
        <dt>{gt text='Title'}</dt>
        <dd>{$review.title}</dd> *}
        {if $review.cover ne '' && $review.coverUpload eq ''}
          <dt>{gt text='Cover'}</dt>
          <dd><img src="/modules/Reviews/images/{$review.cover}" /></dd>
        {/if} 
        {if $review.coverUpload ne ''}
          <a href="{$review.coverUploadFullPathURL}" title="{$review->getTitleFromDisplayPattern()|replace:"\"":""}"{if $review.coverUploadMeta.isImage} rel="imageviewer[review]"{/if}>
          {if $review.coverUploadMeta.isImage}
              {thumb image=$review.coverUploadFullPath objectid="review-`$review.id`" preset=$reviewThumbPresetCoverUpload tag=true img_alt=$review->getTitleFromDisplayPattern()}
          {else}
              {gt text='Download'} ({$review.coverUploadMeta.size|reviewsGetFileSize:$review.coverUploadFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if}
        <dt>{gt text='Text'}</dt>
        <dd>{$review.text}</dd>
        <dt>{gt text='Language'}</dt>
        <dd>{$review.zlanguage|getlanguagename|safetext}</dd>
        <dt>{gt text='Reviewer'}</dt>
        <dd>{$review.reviewer}</dd>
        <dt>{gt text='Email'}</dt>
        <dd>{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="mailto:{$review.email}" title="{gt text='Send an email'}">{icon type='mail' size='extrasmall' __alt='Email'}</a>
        {else}
          {$review.email}
        {/if}
        </dd>
        <dt>{gt text='Score'}</dt>
        <dd>{$review.score|reviewsGetListEntry:'review':'score'|safetext}</dd>
        <dt>{gt text='Web'}</dt>
        <dd>{if $review.url ne ''}
        {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="{$review.url}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
        {else}
          {$review.url}
        {/if}
        {else}&nbsp;{/if}
        </dd>
        <dt>{gt text='Title of link'}</dt>
        <dd>{$review.url_title}</dd>
        <dt>{gt text='Hits'}</dt>
        <dd>{$review.hits}</dd>
       {* <dt>{gt text='Cover'}</dt>
        <dd>{$review.cover}</dd> 
        <dt>{gt text='Cover upload'}</dt>
        <dd>{if $review.coverUpload ne ''}
          <a href="{$review.coverUploadFullPathURL}" title="{$review->getTitleFromDisplayPattern()|replace:"\"":""}"{if $review.coverUploadMeta.isImage} rel="imageviewer[review]"{/if}>
          {if $review.coverUploadMeta.isImage}
              {thumb image=$review.coverUploadFullPath objectid="review-`$review.id`" preset=$reviewThumbPresetCoverUpload tag=true img_alt=$review->getTitleFromDisplayPattern()}
          {else}
              {gt text='Download'} ({$review.coverUploadMeta.size|reviewsGetFileSize:$review.coverUploadFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if}
        </dd> *}
        
    </dl>
    {if $modvars.Reviews.enablecategorization eq 1}
        {include file='admin/include_categories_display.tpl' obj=$review}
    {/if}
    {include file='admin/include_standardfields_display.tpl' obj=$review}

    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        {* include display hooks *}
        {notifydisplayhooks eventname='reviews.ui_hooks.reviews.display_view' id=$review.id urlobject=$currentUrlObject assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
            {$hook}
        {/foreach}
        {if count($review._actions) gt 0}
            <p id="itemActions">
            {foreach item='option' from=$review._actions}
                <a href="{$option.url.type|reviewsActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
            {/foreach}
            </p>
            <script type="text/javascript">
            /* <![CDATA[ */
                document.observe('dom:loaded', function() {
                    reviewsInitItemActions('review', 'display', 'itemActions');
                });
            /* ]]> */
            </script>
        {/if}
    {/if}
</div>
{include file='admin/footer.tpl'}
