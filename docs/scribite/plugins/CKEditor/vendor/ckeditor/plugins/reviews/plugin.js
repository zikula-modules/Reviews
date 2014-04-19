CKEDITOR.plugins.add('Reviews', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertReviews', {
            exec: function (editor) {
                var url = Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=Reviews&type=external&func=finder&editor=ckeditor';
                // call method in Reviews_Finder.js and also give current editor
                ReviewsFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('reviews', {
            label: 'Insert Reviews object',
            command: 'insertReviews',
         // icon: this.path + 'images/ed_reviews.png'
            icon: '/images/icons/extrasmall/favorites.png'
        });
    }
});
