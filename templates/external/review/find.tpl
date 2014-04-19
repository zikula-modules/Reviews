{* Purpose of this template: Display a popup selector of reviews for scribite integration *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}">
<head>
    <title>{gt text='Search and select review'}</title>
    <link type="text/css" rel="stylesheet" href="{$baseurl}style/core.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}modules/Reviews/style/style.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}modules/Reviews/style/finder.css" />
    {assign var='ourEntry' value=$modvars.ZConfig.entrypoint}
    <script type="text/javascript">/* <![CDATA[ */
        if (typeof(Zikula) == 'undefined') {var Zikula = {};}
        Zikula.Config = {'entrypoint': '{{$ourEntry|default:'index.php'}}', 'baseURL': '{{$baseurl}}'}; /* ]]> */</script>
        <script type="text/javascript" src="{$baseurl}javascript/ajax/proto_scriptaculous.combined.min.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/helpers/Zikula.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/livepipe/livepipe.combined.min.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/helpers/Zikula.UI.js"></script>
        <script type="text/javascript" src="{$baseurl}javascript/helpers/Zikula.ImageViewer.js"></script>
    <script type="text/javascript" src="{$baseurl}modules/Reviews/javascript/Reviews_finder.js"></script>
{if $editorName eq 'tinymce'}
    <script type="text/javascript" src="{$baseurl}modules/Scribite/includes/tinymce/tiny_mce_popup.js"></script>
{/if}
</head>
<body>
    <form action="{$ourEntry|default:'index.php'}" id="reviewsSelectorForm" method="get" class="z-form">
    <div>
        <input type="hidden" name="module" value="Reviews" />
        <input type="hidden" name="type" value="external" />
        <input type="hidden" name="func" value="finder" />
        <input type="hidden" name="objectType" value="{$objectType}" />
        <input type="hidden" name="editor" id="editorName" value="{$editorName}" />

        <fieldset>
            <legend>{gt text='Search and select review'}</legend>
            
            {if $properties ne null && is_array($properties)}
                {gt text='All' assign='lblDefault'}
                {nocache}
                {foreach key='propertyName' item='propertyId' from=$properties}
                    <div class="z-formrow categoryselector">
                        {modapifunc modname='Reviews' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
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
                        <label for="{$categorySelectorId}{$propertyName}">{$categoryLabel}</label>
                        &nbsp;
                            {selector_category name="`$categorySelectorName``$propertyName`" field='id' selectedValue=$catIds.$propertyName categoryRegistryModule='Reviews' categoryRegistryTable=$objectType categoryRegistryProperty=$propertyName defaultText=$lblDefault editLink=false multipleSize=$categorySelectorSize}
                            <span class="z-sub z-formnote">{gt text='This is an optional filter.'}</span>
                    </div>
                {/foreach}
                {/nocache}
            {/if}

            <div class="z-formrow">
                <label for="reviewsPasteAs">{gt text='Paste as'}:</label>
                    <select id="reviewsPasteAs" name="pasteas">
                        <option value="1">{gt text='Link to the review'}</option>
                        <option value="2">{gt text='ID of review'}</option>
                    </select>
            </div>
            <br />

            <div class="z-formrow">
                <label for="reviewsObjectId">{gt text='Review'}:</label>
                    <div id="reviewsItemContainer">
                        <ul>
                        {foreach item='review' from=$items}
                            <li>
                                <a href="#" onclick="reviews.finder.selectItem({$review.id})" onkeypress="reviews.finder.selectItem({$review.id})">{$review->getTitleFromDisplayPattern()}</a>
                                <input type="hidden" id="url{$review.id}" value="{modurl modname='Reviews' type='user' func='display' ot='review' id=$review.id slug=$review.slug fqurl=true}" />
                                <input type="hidden" id="title{$review.id}" value="{$review->getTitleFromDisplayPattern()|replace:"\"":""}" />
                                <input type="hidden" id="desc{$review.id}" value="{capture assign='description'}{if $review.text ne ''}{$review.text}{/if}
                                {/capture}{$description|strip_tags|replace:"\"":""}" />
                            </li>
                        {foreachelse}
                            <li>{gt text='No entries found.'}</li>
                        {/foreach}
                        </ul>
                    </div>
            </div>

            <div class="z-formrow">
                <label for="reviewsSort">{gt text='Sort by'}:</label>
                    <select id="reviewsSort" name="sort" style="width: 150px" class="z-floatleft" style="margin-right: 10px">
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
                    <select id="reviewsSortDir" name="sortdir" style="width: 100px">
                        <option value="asc"{if $sortdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                        <option value="desc"{if $sortdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
                    </select>
            </div>

            <div class="z-formrow">
                <label for="reviewsPageSize">{gt text='Page size'}:</label>
                    <select id="reviewsPageSize" name="num" style="width: 50px; text-align: right">
                        <option value="5"{if $pager.itemsperpage eq 5} selected="selected"{/if}>5</option>
                        <option value="10"{if $pager.itemsperpage eq 10} selected="selected"{/if}>10</option>
                        <option value="15"{if $pager.itemsperpage eq 15} selected="selected"{/if}>15</option>
                        <option value="20"{if $pager.itemsperpage eq 20} selected="selected"{/if}>20</option>
                        <option value="30"{if $pager.itemsperpage eq 30} selected="selected"{/if}>30</option>
                        <option value="50"{if $pager.itemsperpage eq 50} selected="selected"{/if}>50</option>
                        <option value="100"{if $pager.itemsperpage eq 100} selected="selected"{/if}>100</option>
                    </select>
            </div>

            <div class="z-formrow">
                <label for="reviewsSearchTerm">{gt text='Search for'}:</label>
                    <input type="text" id="reviewsSearchTerm" name="searchterm" style="width: 150px" class="z-floatleft" style="margin-right: 10px" />
                    <input type="button" id="reviewsSearchGo" name="gosearch" value="{gt text='Filter'}" style="width: 80px" />
            </div>
            
            <div style="margin-left: 6em">
                {pager display='page' rowcount=$pager.numitems limit=$pager.itemsperpage posvar='pos' template='pagercss.tpl' maxpages='10'}
            </div>
            <input type="submit" id="reviewsSubmit" name="submitButton" value="{gt text='Change selection'}" />
            <input type="button" id="reviewsCancel" name="cancelButton" value="{gt text='Cancel'}" />
            <br />
        </fieldset>
    </div>
    </form>

    <script type="text/javascript">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            reviews.finder.onLoad();
        });
    /* ]]> */
    </script>

    {*
    <div class="reviews-finderform">
        <fieldset>
            {modfunc modname='Reviews' type='admin' func='edit'}
        </fieldset>
    </div>
    *}
</body>
</html>
