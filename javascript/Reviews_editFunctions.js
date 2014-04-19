'use strict';


/**
 * Resets the value of an upload / file input field.
 */
function reviewsResetUploadField(fieldName)
{
    if ($(fieldName) != null) {
        $(fieldName).setAttribute('type', 'input');
        $(fieldName).setAttribute('type', 'file');
    }
}

/**
 * Initialises the reset button for a certain upload input.
 */
function reviewsInitUploadField(fieldName)
{
    if ($('reset' + fieldName.capitalize() + 'Val') != null) {
        $('reset' + fieldName.capitalize() + 'Val').observe('click', function (evt) {
            evt.preventDefault();
            reviewsResetUploadField(fieldName);
        }).removeClassName('z-hide');
    }
}

