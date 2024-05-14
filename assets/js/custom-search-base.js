jQuery(document).ready(function($) {
    $('#lawyer-search-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const category = $('#lawyer-category').val();
        const metroSelect = $('#metro');
        const metro = metroSelect.val();
        const metroParent = metroSelect.find('option:selected').data('parent');

        let newUrl = "";
        if (category && metro) {
            if (metroParent) {
                newUrl = `${window.location.origin}/${metroParent}/${metro}/${category}/`;
            } else {
                newUrl = `${window.location.origin}/${metro}/${category}/`;
            }
            window.location.href = newUrl; // Redirect to the new URL
        }
    });
});
