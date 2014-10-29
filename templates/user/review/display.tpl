{* purpose of this template: reviews display view in user area *}
{include file='user/header.tpl'}
<div class="reviews-review reviews-display">
    {gt text='Review' assign='templateTitle'}
    {assign var='templateTitle' value=$review->getTitleFromDisplayPattern()|default:$templateTitle}
    {pagesetvar name='title' value=$templateTitle|@html_entity_decode}
    <h2>{$templateTitle|notifyfilters:'reviews.filter_hooks.reviews.filter'}{* <small>({$review.workflowState|reviewsObjectState:false|lower})</small> *} {icon id='itemActionsTrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h2>

    <dl>
       {* <dt>{gt text='State'}</dt>
        <dd>{$review.workflowState|reviewsGetListEntry:'review':'workflowState'|safetext}</dd>
        <dt>{gt text='Title'}</dt>
        <dd>{$review.title}</dd> *}
        <dt>{gt text='Text'}</dt>
        <dd>{$review.text}</dd>
        <dt>{gt text='Zlanguage'}</dt>
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
        <dd>{* {$review.score|reviewsGetListEntry:'review':'score'|safetext} *}
        {$review.score|reviewsShowStars}</dd>
        {if $review.url ne ''}
        <dt>{gt text='Url'}</dt>
        <dd>{if $review.url ne ''}
        {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="{$review.url}" title="{if $review.url_title ne ''}{$review.url_title}{else}{gt text='Visit this page'}{/if}">{icon type='url' size='extrasmall' title=$review.url_title}</a>
        {else}
          {$review.url}
        {/if}
        {else}&nbsp;{/if}
        </dd>
        {/if}
        <dt>{gt text='Hits'}</dt>
        <dd>{$review.hits}</dd>
        <dt>{gt text='Cover'}</dt>
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
        </dd>
        
    </dl>
    {include file='user/include_categories_display.tpl' obj=$review}
    {include file='user/include_standardfields_display.tpl' obj=$review}

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
{include file='user/footer.tpl'}
