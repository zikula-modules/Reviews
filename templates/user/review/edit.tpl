{* purpose of this template: build the Form to edit an instance of review *}
{include file='user/header.tpl'}
{pageaddvar name='javascript' value='modules/Reviews/javascript/Reviews_editFunctions.js'}
{pageaddvar name='javascript' value='modules/Reviews/javascript/Reviews_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit review' assign='templateTitle'}
{elseif $mode eq 'create'}
    {gt text='Create review' assign='templateTitle'}
{else}
    {gt text='Edit review' assign='templateTitle'}
{/if}
<div class="reviews-review reviews-edit">
    {pagesetvar name='title' value=$templateTitle}
    <h2>{$templateTitle}</h2>
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {reviewsFormFrame}
    {formsetinitialfocus inputId='title'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='title' __text='Title' mandatorysym='1' cssClass=''}
            {formtextinput group='review' id='title' mandatory=true readOnly=false __title='Enter the title of the review' textMode='singleline' maxLength=255 cssClass='required' }
            {reviewsValidationError id='title' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='text' __text='Text' mandatorysym='1' cssClass=''}
            {formtextinput group='review' id='text' mandatory=true __title='Enter the text of the review' textMode='multiline' rows='6' cols='50' cssClass='required' }
            {reviewsValidationError id='text' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='zlanguage' __text='Language' mandatorysym='0' cssClass=''}
            {formlanguageselector group='review' id='zlanguage' mandatory=false addAllOption=true __title='Choose the language of the review'}
            {reviewsValidationError id='zlanguage' class=''}
        </div>
        
        <div class="z-formrow">
            {formlabel for='reviewer' __text='Reviewer' mandatorysym='1' cssClass=''}
            {formtextinput group='review' id='reviewer' mandatory=true readOnly=false __title='Enter the reviewer of the review' textMode='singleline' maxLength=255 cssClass='required' }
            {reviewsValidationError id='reviewer' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='email' __text='Email' mandatorysym='1' cssClass=''}
                {formemailinput group='review' id='email' mandatory=true readOnly=false __title='Enter the email of the review' textMode='singleline' maxLength=255 cssClass='required validate-email' }
            {reviewsValidationError id='email' class='required'}
            {reviewsValidationError id='email' class='validate-email'}
        </div>
        {if $modvars.Reviews.scoreForUsers eq 1}
        <div class="z-formrow">
            {formlabel for='score' __text='Score' cssClass=''}
            {formdropdownlist group='review' id='score' mandatory=false __title='Choose the score' selectionMode='single'}
        </div>
        {/if}
        <div class="z-formrow">
            {formlabel for='url' __text='Url' cssClass=''}
            {formurlinput group='review' id='url' mandatory=false readOnly=false __title='Enter the url of the review' textMode='singleline' maxLength=255 cssClass=' validate-url' }
            {reviewsValidationError id='url' class='validate-url'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='url_title' __text='Url_title' cssClass=''}
            {formtextinput group='review' id='url_title' mandatory=false readOnly=false __title='Enter the url_title of the review' textMode='singleline' maxLength=255 cssClass='' }
        </div>
        
        <div class="z-formrow">
            {formlabel for='hits' __text='Hits' cssClass=''}
            {formintinput group='review' id='hits' mandatory=false __title='Enter the hits of the review' maxLength=18 cssClass=' validate-digits' }
            {reviewsValidationError id='hits' class='validate-digits'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='cover' __text='Cover' cssClass=''}
            {formtextinput group='review' id='cover' mandatory=false readOnly=false __title='Enter the cover of the review' textMode='singleline' maxLength=255 cssClass='' }
            <em class="z-sub z-formnote">{gt text='Name of the cover image, located in %s. Not required.' tag1=modules/Reviews/images/}</em>
        </div>
        
        <div class="z-formrow">
            {formlabel for='coverUpload' __text='Cover upload' cssClass=''}<br />{* break required for Google Chrome *}
            {formuploadinput group='review' id='coverUpload' mandatory=false readOnly=false cssClass=' validate-upload' }
            <span class="z-formnote"><a id="resetCoverUploadVal" href="javascript:void(0);" class="z-hide" style="clear:left;">{gt text='Reset to empty value'}</a></span>
            
                <span class="z-formnote">{gt text='Allowed file extensions:'} <span id="coverUploadFileExtensions">gif, jpeg, jpg, png</span></span>
            <span class="z-formnote">{gt text='Allowed file size:'} {'102400'|reviewsGetFileSize:'':false:false}</span>
            {if $mode ne 'create'}
                {if $review.coverUpload ne ''}
                    <span class="z-formnote">
                        {gt text='Current file'}:
                        <a href="{$review.coverUploadFullPathUrl}" title="{$formattedEntityTitle|replace:"\"":""}"{if $review.coverUploadMeta.isImage} rel="imageviewer[review]"{/if}>
                        {if $review.coverUploadMeta.isImage}
                            {thumb image=$review.coverUploadFullPath objectid="review-`$review.id`" preset=$reviewThumbPresetCoverUpload tag=true img_alt=$formattedEntityTitle}
                        {else}
                            {gt text='Download'} ({$review.coverUploadMeta.size|reviewsGetFileSize:$review.coverUploadFullPath:false:false})
                        {/if}
                        </a>
                    </span>
                    <span class="z-formnote">
                        {formcheckbox group='review' id='coverUploadDeleteFile' readOnly=false __title='Delete cover upload ?'}
                        {formlabel for='coverUploadDeleteFile' __text='Delete existing file'}
                    </span>
                {/if}
            {/if}
            {reviewsValidationError id='coverUpload' class='validate-upload'}
        </div>
        <p class="z-warningmsg">{gt text='Please make sure that the information entered is 100% valid and uses proper grammar and capitalization. For instance, please do not enter your text in ALL CAPS, as it will be rejected.'}</p>
    </fieldset>
    
    {include file='user/include_categories_edit.tpl' obj=$review groupName='reviewObj'}
    {if $mode ne 'create'}
        {include file='user/include_standardfields_edit.tpl' obj=$review}
    {/if}
    
    {* include display hooks *}
    {if $mode ne 'create'}
        {assign var='hookId' value=$review.id}
        {notifydisplayhooks eventname='reviews.ui_hooks.reviews.form_edit' id=$hookId assign='hooks'}
    {else}
        {notifydisplayhooks eventname='reviews.ui_hooks.reviews.form_edit' id=null assign='hooks'}
    {/if}
    {if is_array($hooks) && count($hooks)}
        {foreach key='providerArea' item='hook' from=$hooks}
            <fieldset>
                {$hook}
            </fieldset>
        {/foreach}
    {/if}
    
    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatCreation' __text='Create another item after save'}
                    {formcheckbox group='review' id='repeatCreation' readOnly=false}
            </div>
        </fieldset>
    {/if}
    
    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {foreach item='action' from=$actions}
        {assign var='actionIdCapital' value=$action.id|@ucwords}
        {gt text=$action.title assign='actionTitle'}
        {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
        {if $action.id eq 'delete'}
            {gt text='Really delete this review?' assign='deleteConfirmMsg'}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
        {else}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
        {/if}
    {/foreach}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
    {/reviewsFormFrame}
{/form}
</div>
{include file='user/footer.tpl'}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='removeImageArray'}


<script type="text/javascript">
/* <![CDATA[ */

    var formButtons, formValidator;

    function handleFormButton (event) {
        var result = formValidator.validate();
        if (!result) {
            // validation error, abort form submit
            Event.stop(event);
        } else {
            // hide form buttons to prevent double submits by accident
            formButtons.each(function (btn) {
                btn.addClassName('z-hide');
            });
        }

        return result;
    }

    document.observe('dom:loaded', function() {

        reviewsAddCommonValidationRules('review', '{{if $mode ne 'create'}}{{$review.id}}{{/if}}');
        {{* observe validation on button events instead of form submit to exclude the cancel command *}}
        formValidator = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = formValidator.validate();
        {{/if}}

        formButtons = $('{{$__formid}}').select('div.z-formbuttons input');

        formButtons.each(function (elem) {
            if (elem.id != 'btnCancel') {
                elem.observe('click', handleFormButton);
            }
        });

        Zikula.UI.Tooltips($$('.reviews-form-tooltips'));
        reviewsInitUploadField('coverUpload');
    });

/* ]]> */
</script>
