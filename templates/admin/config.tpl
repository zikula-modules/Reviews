{* purpose of this template: module configuration *}
{include file='admin/header.tpl'}
<div class="reviews-config">
    {gt text='Settings' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type='config' size='small' __alt='Settings'}
        <h3>{$templateTitle}</h3>
    </div>

    {form cssClass='z-form'}
        {* add validation summary and a <div> element for styling the form *}
        {reviewsFormFrame}
            {formsetinitialfocus inputId='enablecategorization'}
            {gt text='General' assign='tabTitle'}
            <fieldset>
                <legend>{$tabTitle}</legend>
            
                <p class="z-confirmationmsg">{gt text='Here you can manage all basic settings for this application.'}</p>
            
                <div class="z-formrow">
                    {formlabel for='enablecategorization' __text='Enablecategorization' cssClass=''}
                        {formcheckbox id='enablecategorization' group='config'}
                </div>
                <div class="z-formrow">
                    {formlabel for='pagesize' __text='Pagesize' cssClass=''}
                        {formintinput id='pagesize' group='config' maxLength=255 __title='Enter the pagesize. Only digits are allowed.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='scoreForUsers' __text='Score for users' cssClass=''}
                        {formcheckbox id='scoreForUsers' group='config'}
                </div>
                <div class="z-formrow">
                    {formlabel for='addcategorytitletopermalink' __text='Addcategorytitletopermalink' cssClass=''}
                        {formcheckbox id='addcategorytitletopermalink' group='config'}
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {formbutton commandName='save' __text='Update configuration' class='z-bt-save'}
                {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            </div>
        {/reviewsFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}
