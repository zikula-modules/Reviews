{* Purpose of this template: edit view of generic item list content type *}
<div class="z-formrow">
    {gt text='Object type' domain='module_reviews' assign='objectTypeSelectorLabel'}
    {formlabel for='reviewsObjectType' text=$objectTypeSelectorLabel}
        {reviewsObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='reviewsOjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
        <span class="z-sub z-formnote">{gt text='If you change this please save the element once to reload the parameters below.' domain='module_reviews'}</span>
</div>

{formvolatile}
{if $properties ne null && is_array($properties)}
    {nocache}
    {foreach key='registryId' item='registryCid' from=$registries}
        {assign var='propName' value=''}
        {foreach key='propertyName' item='propertyId' from=$properties}
            {if $propertyId eq $registryId}
                {assign var='propName' value=$propertyName}
            {/if}
        {/foreach}
        <div class="z-formrow">
            {modapifunc modname='Reviews' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
            {gt text='Category' domain='module_reviews' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='module_reviews' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="reviewsCatIds`$propertyName`" text=$categorySelectorLabel}
                {formdropdownlist id="reviewsCatIds`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode}
                <span class="z-sub z-formnote">{gt text='This is an optional filter.' domain='module_reviews'}</span>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}

<div class="z-formrow">
    {gt text='Sorting' domain='module_reviews' assign='sortingLabel'}
    {formlabel text=$sortingLabel}
    <div>
        {formradiobutton id='reviewsSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='module_reviews' assign='sortingRandomLabel'}
        {formlabel for='reviewsSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='reviewsSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='module_reviews' assign='sortingNewestLabel'}
        {formlabel for='reviewsSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='reviewsSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='module_reviews' assign='sortingDefaultLabel'}
        {formlabel for='reviewsSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="z-formrow">
    {gt text='Amount' domain='module_reviews' assign='amountLabel'}
    {formlabel for='reviewsAmount' text=$amountLabel}
        {formintinput id='reviewsAmount' dataField='amount' group='data' mandatory=true maxLength=2}
</div>

<div class="z-formrow">
    {gt text='Template' domain='module_reviews' assign='templateLabel'}
    {formlabel for='reviewsTemplate' text=$templateLabel}
        {reviewsTemplateSelector assign='allTemplates'}
        {formdropdownlist id='reviewsTemplate' dataField='template' group='data' mandatory=true items=$allTemplates}
</div>

<div id="customTemplateArea" class="z-formrow z-hide">
    {gt text='Custom template' domain='module_reviews' assign='customTemplateLabel'}
    {formlabel for='reviewsCustomTemplate' text=$customTemplateLabel}
        {formtextinput id='reviewsCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80}
        <span class="z-sub z-formnote">{gt text='Example' domain='module_reviews'}: <em>itemlist_[objectType]_display.tpl</em></span>
</div>

<div class="z-formrow z-hide">
    {gt text='Filter (expert option)' domain='module_reviews' assign='filterLabel'}
    {formlabel for='reviewsFilter' text=$filterLabel}
        {formtextinput id='reviewsFilter' dataField='filter' group='data' mandatory=false maxLength=255}
        <span class="z-sub z-formnote">
            ({gt text='Syntax examples'}: <kbd>name:like:foobar</kbd> {gt text='or'} <kbd>status:ne:3</kbd>)
        </span>
</div>

{pageaddvar name='javascript' value='prototype'}
<script type="text/javascript">
/* <![CDATA[ */
    function reviewsToggleCustomTemplate() {
        if ($F('reviewsTemplate') == 'custom') {
            $('customTemplateArea').removeClassName('z-hide');
        } else {
            $('customTemplateArea').addClassName('z-hide');
        }
    }

    document.observe('dom:loaded', function() {
        reviewsToggleCustomTemplate();
        $('reviewsTemplate').observe('change', function(e) {
            reviewsToggleCustomTemplate();
        });
    });
/* ]]> */
</script>
