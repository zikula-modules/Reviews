// Reviews plugin for Xinha
// developed by Michael Ueberschaer
//
// requires Reviews module (http://webdesign-in-bremen.com)
//
// Distributed under the same terms as xinha itself.
// This notice MUST stay intact for use (see license.txt).

'use strict';

function Reviews(editor) {
    var cfg, self;

    this.editor = editor;
    cfg = editor.config;
    self = this;

    cfg.registerButton({
        id       : 'Reviews',
        tooltip  : 'Insert Reviews object',
     // image    : _editor_url + 'plugins/Reviews/img/ed_Reviews.gif',
        image    : '/images/icons/extrasmall/favorites.png',
        textMode : false,
        action   : function (editor) {
            var url = Zikula.Config.baseURL + 'index.php'/*Zikula.Config.entrypoint*/ + '?module=Reviews&type=external&func=finder&editor=xinha';
            ReviewsFinderXinha(editor, url);
        }
    });
    cfg.addToolbarElement('Reviews', 'insertimage', 1);
}

Reviews._pluginInfo = {
    name          : 'Reviews for xinha',
    version       : '2.5.0',
    developer     : 'Michael Ueberschaer',
    developer_url : 'http://webdesign-in-bremen.com',
    sponsor       : 'ModuleStudio 0.6.2',
    sponsor_url   : 'http://modulestudio.de',
    license       : 'htmlArea'
};
