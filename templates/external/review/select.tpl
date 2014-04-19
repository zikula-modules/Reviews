{* Purpose of this template: Display a popup selector for Forms and Content integration *}
{assign var='baseID' value='review'}
<div id="{$baseID}Preview" style="float: right; width: 300px; border: 1px dotted #a3a3a3; padding: .2em .5em; margin-right: 1em">
    <p><strong>{gt text='Review information'}</strong></p>
    {img id='ajax_indicator' modname='core' set='ajax' src='indicator_circle.gif' alt='' class='z-hide'}
    <div id="{$baseID}PreviewContainer">&nbsp;</div>
</div>
<br />
<br />
{assign var='leftSide' value=' style="float: left; width: 10em"'}
{assign var='rightSide' value=' style="float: left"'}
{assign var='break' value=' style="clear: left"'}

{if $properties ne null && is_array($properties)}
    {gt text='All' assign='lblDefault'}
    {nocache}
    {foreach key='propertyName' item='propertyId' from=$properties}
        <p>
            {modapifunc modname='Reviews' type='category' func='hasMultipleSelection' ot='review' registry=$propertyName assign='hasMultiSelection'}
            {gt text='Category' assign='categoryLabel'}
            {assign var='categorySelectorId' value='catid'}
            {assign var='categorySelectorName' value='catid'}
            {assign var='categorySelectorSize' value='1'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' assign='categoryLabel'}
                {assign var='categorySelectorName' value='catids'}
                {assign var='categorySelectorId' value='catids__'}
                {assign var='categorySelectorSize' value='8'}
            {/if}
            <label for="{$baseID}_{$categorySelectorId}{$propertyName}"{$leftSide}>{$categoryLabel}:</label>
            &nbsp;
            {selector_category name="`$baseID`_`$categorySelectorName``$propertyName`" field='id' selectedValue=$catIds.$propertyName categoryRegistryModule='Reviews' categoryRegistryTable=$objectType categoryRegistryProperty=$propertyName defaultText=$lblDefault editLink=false multipleSize=$categorySelectorSize}
            <br{$break} />
        </p>
    {/foreach}
    {/nocache}
{/if}
<p>
    <label for="{$baseID}Id"{$leftSide}>{gt text='Review'}:</label>
    <select id="{$baseID}Id" name="id"{$rightSide}>
        {foreach item='review' from=$items}
            <option value="{$review.id}"{if $selectedId eq $review.id} selected="selected"{/if}>{$review->getTitleFromDisplayPattern()}</option>
        {foreachelse}
            <option value="0">{gt text='No entries found.'}</option>
        {/foreach}
    </select>
    <br{$break} />
</p>
<p>
    <label for="{$baseID}Sort"{$leftSide}>{gt text='Sort by'}:</label>
    <select id="{$baseID}Sort" name="sort"{$rightSide}>
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
    <select id="{$baseID}SortDir" name="sortdir">
        <option value="asc"{if $sortdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
        <option value="desc"{if $sortdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
    </select>
    <br{$break} />
</p>
<p>
    <label for="{$baseID}SearchTerm"{$leftSide}>{gt text='Search for'}:</label>
    <input type="text" id="{$baseID}SearchTerm" name="searchterm"{$rightSide} />
    <input type="button" id="reviewsSearchGo" name="gosearch" value="{gt text='Filter'}" />
    <br{$break} />
</p>
<br />
<br />

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        reviews.itemSelector.onLoad('{{$baseID}}', {{$selectedId|default:0}});
    });
/* ]]> */
</script>
