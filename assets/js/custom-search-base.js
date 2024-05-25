jQuery(document).ready(function($) {
    // Initialize Select2 on the dropdowns
    $('#lawyer-category').select2({
        placeholder: "Buscar categoría",
        allowClear: true
    });

    $('#metro').select2({
        placeholder: "Buscar metro",
        allowClear: true
    });

    // Function to enable/disable the submit button for the main form
    function toggleSubmitButton(categorySelector, metroSelector, submitButtonSelector) {
        const category = $(categorySelector).val();
        const metro = $(metroSelector).val();
        if (category && metro) {
            $(submitButtonSelector).prop('disabled', false);
        } else {
            $(submitButtonSelector).prop('disabled', true);
        }
    }

    // Handle category selection
    $('#lawyer-category').on('change', function() {
        const category = $(this).val();

        if (category) {
            // Enable metro select and fetch metro options based on selected category
            $('#metro').prop('disabled', false).html('<option value="" disabled selected>Buscar metro</option>');

            // Fetch metro terms dynamically via AJAX
            $.ajax({
                url: csb_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'csb_get_metro_terms',
                    category: category
                },
                success: function(response) {
                    if (response.success) {
                        response.data.forEach(function(metro) {
                            $('#metro').append('<option value="' + metro.slug + '" data-parent="' + metro.parent + '">' + metro.name + '</option>');
                        });
                    } else {
                        console.error(response.data);
                    }
                    // Refresh Select2
                    $('#metro').select2({
                        placeholder: "Buscar metro",
                        allowClear: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#metro').prop('disabled', true).html('<option value="" disabled selected>Seleccione una categoría primero</option>');
        }

        // Enable the submit button if both fields are selected
        toggleSubmitButton('#lawyer-category', '#metro', '#lawyer-search-form input[type="submit"]');
    });

    // Handle metro selection
    $('#metro').on('change', function() {
        toggleSubmitButton('#lawyer-category', '#metro', '#lawyer-search-form input[type="submit"]');
    });

    // Handle form submission for the main search form
    $('#lawyer-search-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const category = $('#lawyer-category').val();
        const metroSelect = $('#metro');
        const metro = metroSelect.val();
        const metroParent = metroSelect.find('option:selected').data('parent');
        const resultsUrl = $('#results-url').val();

        console.log('Category: ', category);
        console.log('Metro: ', metro);
        console.log('Metro Parent: ', metroParent);
        console.log('Results URL: ', resultsUrl);

        let newUrl = "";
        if (category && metro) {
            if (metroParent) {
                newUrl = `${window.location.origin}${resultsUrl}${category}/${metroParent}/${metro}/`;
            } else {
                newUrl = `${window.location.origin}${resultsUrl}${category}/${metro}/`;
            }
            console.log('Redirecting to: ', newUrl); // Debugging statement
            window.location.href = newUrl; // Redirect to the new URL
        } else {
            console.log('Category or Metro not found');
        }
    });

    // Trigger change event to enable metro select and populate options if a category is preselected
    if ($('#lawyer-category').val()) {
        $('#lawyer-category').trigger('change');
    }
});
