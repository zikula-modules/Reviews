'use strict';

var currentReviewsEditor = null;
var currentReviewsInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a Xinha button.
 */
function ReviewsFinderXinha(editor, reviewsURL)
{
    var popupAttributes;

    // Save editor for access in selector window
    currentReviewsEditor = editor;

    popupAttributes = getPopupAttributes();
    window.open(reviewsURL, '', popupAttributes);
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function ReviewsFinderCKEditor(editor, reviewsURL)
{
    // Save editor for access in selector window
    currentReviewsEditor = editor;

    editor.popup(
        Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=Reviews&type=external&func=finder&editor=ckeditor',
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}



var reviews = {};

reviews.finder = {};

reviews.finder.onLoad = function (baseId, selectedId)
{
    $$('div.categoryselector select').invoke('observe', 'change', reviews.finder.onParamChanged);
    $('reviewsSort').observe('change', reviews.finder.onParamChanged);
    $('reviewsSortDir').observe('change', reviews.finder.onParamChanged);
    $('reviewsPageSize').observe('change', reviews.finder.onParamChanged);
    $('reviewsSearchGo').observe('click', reviews.finder.onParamChanged);
    $('reviewsSearchGo').observe('keypress', reviews.finder.onParamChanged);
    $('reviewsSubmit').addClassName('z-hide');
    $('reviewsCancel').observe('click', reviews.finder.handleCancel);
};

reviews.finder.onParamChanged = function ()
{
    $('reviewsSelectorForm').submit();
};

reviews.finder.handleCancel = function ()
{
    var editor, w;

    editor = $F('editorName');
    if (editor === 'xinha') {
        w = parent.window;
        window.close();
        w.focus();
    } else if (editor === 'tinymce') {
        reviewsClosePopup();
    } else if (editor === 'ckeditor') {
        reviewsClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function getPasteSnippet(mode, itemId)
{
    var itemUrl, itemTitle, itemDescription, pasteMode;

    itemUrl = $F('url' + itemId);
    itemTitle = $F('title' + itemId);
    itemDescription = $F('desc' + itemId);
    pasteMode = $F('reviewsPasteAs');

    if (pasteMode === '2' || pasteMode !== '1') {
        return itemId;
    }

    // return link to item
    if (mode === 'url') {
        // plugin mode
        return itemUrl;
    } else {
        // editor mode
        return '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
}


// User clicks on "select item" button
reviews.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = $F('editorName');
    if (editor === 'xinha') {
        if (window.opener.currentReviewsEditor !== null) {
            html = getPasteSnippet('html', itemId);

            window.opener.currentReviewsEditor.focusEditor();
            window.opener.currentReviewsEditor.insertHTML(html);
        } else {
            html = getPasteSnippet('url', itemId);
            var currentInput = window.opener.currentReviewsInput;

            if (currentInput.tagName === 'INPUT') {
                // Simply overwrite value of input elements
                currentInput.value = html;
            } else if (currentInput.tagName === 'TEXTAREA') {
                // Try to paste into textarea - technique depends on environment
                if (typeof document.selection !== 'undefined') {
                    // IE: Move focus to textarea (which fortunately keeps its current selection) and overwrite selection
                    currentInput.focus();
                    window.opener.document.selection.createRange().text = html;
                } else if (typeof currentInput.selectionStart !== 'undefined') {
                    // Firefox: Get start and end points of selection and create new value based on old value
                    var startPos = currentInput.selectionStart;
                    var endPos = currentInput.selectionEnd;
                    currentInput.value = currentInput.value.substring(0, startPos)
                                        + html
                                        + currentInput.value.substring(endPos, currentInput.value.length);
                } else {
                    // Others: just append to the current value
                    currentInput.value += html;
                }
            }
        }
    } else if (editor === 'tinymce') {
        html = getPasteSnippet('html', itemId);
        window.opener.tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if (editor === 'ckeditor') {
        /** to be done*/
    } else {
        alert('Insert into Editor: ' + editor);
    }
    reviewsClosePopup();
};


function reviewsClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// Reviews item selector for Forms
//=============================================================================

reviews.itemSelector = {};
reviews.itemSelector.items = {};
reviews.itemSelector.baseId = 0;
reviews.itemSelector.selectedId = 0;

reviews.itemSelector.onLoad = function (baseId, selectedId)
{
    reviews.itemSelector.baseId = baseId;
    reviews.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    $('reviewsObjectType').observe('change', reviews.itemSelector.onParamChanged);

    if ($(baseId + '_catidMain') != undefined) {
        $(baseId + '_catidMain').observe('change', reviews.itemSelector.onParamChanged);
    } else if ($(baseId + '_catidsMain') != undefined) {
        $(baseId + '_catidsMain').observe('change', reviews.itemSelector.onParamChanged);
    }
    $(baseId + 'Id').observe('change', reviews.itemSelector.onItemChanged);
    $(baseId + 'Sort').observe('change', reviews.itemSelector.onParamChanged);
    $(baseId + 'SortDir').observe('change', reviews.itemSelector.onParamChanged);
    $('reviewsSearchGo').observe('click', reviews.itemSelector.onParamChanged);
    $('reviewsSearchGo').observe('keypress', reviews.itemSelector.onParamChanged);

    reviews.itemSelector.getItemList();
};

reviews.itemSelector.onParamChanged = function ()
{
    $('ajax_indicator').removeClassName('z-hide');

    reviews.itemSelector.getItemList();
};

reviews.itemSelector.getItemList = function ()
{
    var baseId, pars, request;

    baseId = reviews.itemSelector.baseId;
    pars = 'ot=' + baseId + '&';
    if ($(baseId + '_catidMain') != undefined) {
        pars += 'catidMain=' + $F(baseId + '_catidMain') + '&';
    } else if ($(baseId + '_catidsMain') != undefined) {
        pars += 'catidsMain=' + $F(baseId + '_catidsMain') + '&';
    }
    pars += 'sort=' + $F(baseId + 'Sort') + '&' +
            'sortdir=' + $F(baseId + 'SortDir') + '&' +
            'searchterm=' + $F(baseId + 'SearchTerm');

    request = new Zikula.Ajax.Request(
        Zikula.Config.baseURL + 'ajax.php?module=Reviews&func=getItemListFinder',
        {
            method: 'post',
            parameters: pars,
            onFailure: function(req) {
                Zikula.showajaxerror(req.getMessage());
            },
            onSuccess: function(req) {
                var baseId;
                baseId = reviews.itemSelector.baseId;
                reviews.itemSelector.items[baseId] = req.getData();
                $('ajax_indicator').addClassName('z-hide');
                reviews.itemSelector.updateItemDropdownEntries();
                reviews.itemSelector.updatePreview();
            }
        }
    );
};

reviews.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = reviews.itemSelector.baseId;
    itemSelector = $(baseId + 'Id');
    itemSelector.length = 0;

    items = reviews.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (reviews.itemSelector.selectedId > 0) {
        $(baseId + 'Id').value = reviews.itemSelector.selectedId;
    }
};

reviews.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = reviews.itemSelector.baseId;
    items = reviews.itemSelector.items[baseId];

    $(baseId + 'PreviewContainer').addClassName('z-hide');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (reviews.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === reviews.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (selectedElement !== null) {
        $(baseId + 'PreviewContainer').update(window.atob(selectedElement.previewInfo))
                                      .removeClassName('z-hide');
    }
};

reviews.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = reviews.itemSelector.baseId;
    itemSelector = $(baseId + 'Id');
    preview = window.atob(reviews.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    $(baseId + 'PreviewContainer').update(preview);
    reviews.itemSelector.selectedId = $F(baseId + 'Id');
};
