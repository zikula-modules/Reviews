{* purpose of this template: reviews view filter form in user area *}
{checkpermissionblock component='Reviews:Review:' instance='::' level='ACCESS_EDIT'}
{assign var='objectType' value='review'}
<form action="{$modvars.ZConfig.entrypoint|default:'index.php'}" method="get" id="reviewsReviewQuickNavForm" class="reviews-quicknav">
    <fieldset>
        <h3>{gt text='Quick navigation'}</h3>
        <input type="hidden" name="module" value="{modgetinfo modname='Reviews' info='url'}" />
        <input type="hidden" name="type" value="user" />
        <input type="hidden" name="func" value="view" />
        <input type="hidden" name="ot" value="review" />
        <input type="hidden" name="all" value="{$all|default:0}" />
        <input type="hidden" name="own" value="{$own|default:0}" />
        {gt text='All' assign='lblDefault'}
        {if !isset($categoryFilter) || $categoryFilter eq true}
            {nocache}
            {modapifunc modname='Reviews' type='category' func='getAllProperties' ot=$objectType assign='properties'}
            {if $properties ne null && is_array($properties)}
                {gt text='All' assign='lblDefault'}
                {foreach key='propertyName' item='propertyId' from=$properties}
                    {modapifunc modname='Reviews' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
                    {gt text='Category' assign='categoryLabel'}
                    {assign var='categorySelectorId' value='catid'}
                    {assign var='categorySelectorName' value='catid'}
                    {assign var='categorySelectorSize' value='1'}
                    {if $hasMultiSelection eq true}
                        {gt text='Categories' assign='categoryLabel'}
                        {assign var='categorySelectorName' value='catids'}
                        {assign var='categorySelectorId' value='catids__'}
                        {assign var='categorySelectorSize' value='5'}
                    {/if}
                        <label for="{$categorySelectorId}{$propertyName}">{$categoryLabel}</label>
                        &nbsp;
                        {selector_category name="`$categorySelectorName``$propertyName`" field='id' selectedValue=$catIdList.$propertyName categoryRegistryModule='Reviews' categoryRegistryTable=$objectType categoryRegistryProperty=$propertyName defaultText=$lblDefault editLink=false multipleSize=$categorySelectorSize}
                {/foreach}
            {/if}
            {/nocache}
        {/if}
        {if !isset($workflowStateFilter) || $workflowStateFilter eq true}
                <label for="workflowState">{gt text='Workflow state'}</label>
                <select id="workflowState" name="workflowState">
                    <option value="">{$lblDefault}</option>
                {foreach item='option' from=$workflowStateItems}
                <option value="{$option.value}"{if $option.title ne ''} title="{$option.title|safetext}"{/if}{if $option.value eq $workflowState} selected="selected"{/if}>{$option.text|safetext}</option>
                {/foreach}
                </select>
        {/if}
        {if !isset($scoreFilter) || $scoreFilter eq true}
                <label for="score">{gt text='Score'}</label>
                <select id="score" name="score">
                    <option value="">{$lblDefault}</option>
                {foreach item='option' from=$scoreItems}
                <option value="{$option.value}"{if $option.title ne ''} title="{$option.title|safetext}"{/if}{if $option.value eq $score} selected="selected"{/if}>{$option.text|safetext}</option>
                {/foreach}
                </select>
        {/if}
        {if !isset($zlanguageFilter) || $zlanguageFilter eq true}
                <label for="zlanguage">{gt text='Zlanguage'}</label>
                {html_select_locales name='zlanguage' selected=$zlanguage}
        {/if}
        {if !isset($searchFilter) || $searchFilter eq true}
                <label for="searchTerm">{gt text='Search'}</label>
                <input type="text" id="searchTerm" name="searchterm" value="{$searchterm}" />
        {/if}
        {if !isset($sorting) || $sorting eq true}
                <label for="sortBy">{gt text='Sort by'}</label>
                &nbsp;
                <select id="sortBy" name="sort">
                    <option value="id"{if $sort eq 'id'} selected="selected"{/if}>{gt text='Id'}</option>
                    <option value="workflowState"{if $sort eq 'workflowState'} selected="selected"{/if}>{gt text='Workflow state'}</option>
                    <option value="title"{if $sort eq 'title'} selected="selected"{/if}>{gt text='Title'}</option>
                    <option value="text"{if $sort eq 'text'} selected="selected"{/if}>{gt text='Text'}</option>
                    <option value="zlanguage"{if $sort eq 'zlanguage'} selected="selected"{/if}>{gt text='Zlanguage'}</option>
                    <option value="reviewer"{if $sort eq 'reviewer'} selected="selected"{/if}>{gt text='Reviewer'}</option>
                    <option value="email"{if $sort eq 'email'} selected="selected"{/if}>{gt text='Email'}</option>
                    <option value="score"{if $sort eq 'score'} selected="selected"{/if}>{gt text='Score'}</option>
                    <option value="url"{if $sort eq 'url'} selected="selected"{/if}>{gt text='Url'}</option>
                    <option value="url_title"{if $sort eq 'url_title'} selected="selected"{/if}>{gt text='Url_title'}</option>
                    <option value="hits"{if $sort eq 'hits'} selected="selected"{/if}>{gt text='Hits'}</option>
                    <option value="cover"{if $sort eq 'cover'} selected="selected"{/if}>{gt text='Cover'}</option>
                    <option value="coverUpload"{if $sort eq 'coverUpload'} selected="selected"{/if}>{gt text='Cover upload'}</option>
                    <option value="createdDate"{if $sort eq 'createdDate'} selected="selected"{/if}>{gt text='Creation date'}</option>
                    <option value="createdUserId"{if $sort eq 'createdUserId'} selected="selected"{/if}>{gt text='Creator'}</option>
                    <option value="updatedDate"{if $sort eq 'updatedDate'} selected="selected"{/if}>{gt text='Update date'}</option>
                </select>
                <select id="sortDir" name="sortdir">
                    <option value="asc"{if $sdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                    <option value="desc"{if $sdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
                </select>
        {else}
            <input type="hidden" name="sort" value="{$sort}" />
            <input type="hidden" name="sdir" value="{if $sdir eq 'desc'}asc{else}desc{/if}" />
        {/if}
        {if !isset($pageSizeSelector) || $pageSizeSelector eq true}
                <label for="num">{gt text='Page size'}</label>
                <select id="num" name="num">
                    <option value="5"{if $pageSize eq 5} selected="selected"{/if}>5</option>
                    <option value="10"{if $pageSize eq 10} selected="selected"{/if}>10</option>
                    <option value="15"{if $pageSize eq 15} selected="selected"{/if}>15</option>
                    <option value="20"{if $pageSize eq 20} selected="selected"{/if}>20</option>
                    <option value="30"{if $pageSize eq 30} selected="selected"{/if}>30</option>
                    <option value="50"{if $pageSize eq 50} selected="selected"{/if}>50</option>
                    <option value="100"{if $pageSize eq 100} selected="selected"{/if}>100</option>
                </select>
        {/if}
        <input type="submit" name="updateview" id="quicknavSubmit" value="{gt text='OK'}" />
    </fieldset>
</form>

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        reviewsInitQuickNavigation('review', 'user');
        {{if isset($searchFilter) && $searchFilter eq false}}
            {{* we can hide the submit button if we have no quick search field *}}
            $('quicknavSubmit').addClassName('z-hide');
        {{/if}}
    });
/* ]]> */
</script>
{/checkpermissionblock}
