{* purpose of this template: reviews view view in user area *}
{include file='user/header.tpl'}
<div class="reviews-review reviews-view">
    {gt text='Review list' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    <h2>{$templateTitle}</h2>

    {if $canBeCreated}
        {checkpermissionblock component='Reviews:Review:' instance='::' level='ACCESS_COMMENT'}
            {gt text='Create review' assign='createTitle'}
            <a href="{modurl modname='Reviews' type='user' func='edit' ot='review'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        {/checkpermissionblock}
    {/if}
    {assign var='own' value=0}
    {if isset($showOwnEntries) && $showOwnEntries eq 1}
        {assign var='own' value=1}
    {/if}
    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='Reviews' type='user' func='view' ot='review'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='Reviews' type='user' func='view' ot='review' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
    {/if}

    {include file='user/review/view_quickNav.tpl' all=$all own=$own}{* see template file for available options *}

    <table class="z-datatable">
        <colgroup>
            <col id="cWorkflowState" />
            <col id="cTitle" />
            <col id="cText" />
            <col id="cZlanguage" />
            <col id="cReviewer" />
            <col id="cEmail" />
            <col id="cScore" />
            <col id="cUrl" />
            <col id="cUrl_title" />
            <col id="cHits" />
            <col id="cCover" />
            <col id="cCoverUpload" />
            <col id="cItemActions" />
        </colgroup>
        <thead>
        <tr>
            {assign var='catIdListMainString' value=','|implode:$catIdList.Main}
            <th id="hWorkflowState" scope="col" class="z-left">
                {sortlink __linktext='State' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='workflowState' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hTitle" scope="col" class="z-left">
                {sortlink __linktext='Title' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='title' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hText" scope="col" class="z-left">
                {sortlink __linktext='Text' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='text' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hZlanguage" scope="col" class="z-left">
                {sortlink __linktext='Zlanguage' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='zlanguage' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hReviewer" scope="col" class="z-left">
                {sortlink __linktext='Reviewer' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='reviewer' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hEmail" scope="col" class="z-left">
                {sortlink __linktext='Email' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='email' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hScore" scope="col" class="z-left">
                {sortlink __linktext='Score' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='score' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hUrl" scope="col" class="z-left">
                {sortlink __linktext='Url' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='url' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hUrl_title" scope="col" class="z-left">
                {sortlink __linktext='Url_title' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='url_title' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hHits" scope="col" class="z-right">
                {sortlink __linktext='Hits' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='hits' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hCover" scope="col" class="z-left">
                {sortlink __linktext='Cover' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='cover' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hCoverUpload" scope="col" class="z-left">
                {sortlink __linktext='Cover upload' currentsort=$sort modname='Reviews' type='user' func='view' ot='review' sort='coverUpload' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString workflowState=$workflowState score=$score zlanguage=$zlanguage searchterm=$searchterm pageSize=$pageSize}
            </th>
            <th id="hItemActions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
        </tr>
        </thead>
        <tbody>
    
    {foreach item='review' from=$items}
        <tr class="{cycle values='z-odd, z-even'}">
            <td headers="hWorkflowState" class="z-left z-nowrap">
                {$review.workflowState|reviewsObjectState}
            </td>
            <td headers="hTitle" class="z-left">
                {$review.title}
            </td>
            <td headers="hText" class="z-left">
                {$review.text}
            </td>
            <td headers="hZlanguage" class="z-left">
                {$review.zlanguage|getlanguagename|safetext}
            </td>
            <td headers="hReviewer" class="z-left">
                {$review.reviewer}
            </td>
            <td headers="hEmail" class="z-left">
                <a href="mailto:{$review.email}" title="{gt text='Send an email'}">{icon type='mail' size='extrasmall' __alt='Email'}</a>
            </td>
            <td headers="hScore" class="z-left">
                {$review.score|reviewsGetListEntry:'review':'score'|safetext}
            </td>
            <td headers="hUrl" class="z-left">
                {if $review.url ne ''}
                <a href="{$review.url}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
                {else}&nbsp;{/if}
            </td>
            <td headers="hUrl_title" class="z-left">
                {$review.url_title}
            </td>
            <td headers="hHits" class="z-right">
                {$review.hits}
            </td>
            <td headers="hCover" class="z-left">
                {$review.cover}
            </td>
            <td headers="hCoverUpload" class="z-left">
                {if $review.coverUpload ne ''}
                  <a href="{$review.coverUploadFullPathURL}" title="{$review->getTitleFromDisplayPattern()|replace:"\"":""}"{if $review.coverUploadMeta.isImage} rel="imageviewer[review]"{/if}>
                  {if $review.coverUploadMeta.isImage}
                      {thumb image=$review.coverUploadFullPath objectid="review-`$review.id`" preset=$reviewThumbPresetCoverUpload tag=true img_alt=$review->getTitleFromDisplayPattern()}
                  {else}
                      {gt text='Download'} ({$review.coverUploadMeta.size|reviewsGetFileSize:$review.coverUploadFullPath:false:false})
                  {/if}
                  </a>
                {else}&nbsp;{/if}
            </td>
            <td id="itemActions{$review.id}" headers="hItemActions" class="z-right z-nowrap z-w02">
                {if count($review._actions) gt 0}
                    {foreach item='option' from=$review._actions}
                        <a href="{$option.url.type|reviewsActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                    {/foreach}
                    {icon id="itemActions`$review.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                    <script type="text/javascript">
                    /* <![CDATA[ */
                        document.observe('dom:loaded', function() {
                            reviewsInitItemActions('review', 'view', 'itemActions{{$review.id}}');
                        });
                    /* ]]> */
                    </script>
                {/if}
            </td>
        </tr>
    {foreachelse}
        <tr class="z-datatableempty">
          <td class="z-left" colspan="13">
        {gt text='No reviews found.'}
          </td>
        </tr>
    {/foreach}
    
        </tbody>
    </table>
    
    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='Reviews' type='user' func='view' ot='review'}
    {/if}

    
    {notifydisplayhooks eventname='reviews.ui_hooks.reviews.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
{include file='user/footer.tpl'}
