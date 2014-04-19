{* Purpose of this template: Display search options *}
<input type="hidden" id="reviewsActive" name="active[Reviews]" value="1" checked="checked" />
<div>
    <input type="checkbox" id="active_reviewsReviews" name="reviewsSearchTypes[]" value="review"{if $active_review} checked="checked"{/if} />
    <label for="active_reviewsReviews">{gt text='Reviews' domain='module_reviews'}</label>
</div>
