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
        toggleSubmitButton();
    });

    // Handle metro selection
    $('#metro').on('change', function() {
        toggleSubmitButton();
    });

    // Function to enable/disable the submit button
    function toggleSubmitButton() {
        const category = $('#lawyer-category').val();
        const metro = $('#metro').val();

        if (category && metro) {
            $('input[type="submit"]').prop('disabled', false);
        } else {
            $('input[type="submit"]').prop('disabled', true);
        }
    }

    // Handle form submission
    $('#lawyer-search-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const category = $('#lawyer-category').val();
        const metroSelect = $('#metro');
        const metro = metroSelect.val();
        const metroParent = metroSelect.find('option:selected').data('parent');

        let newUrl = "";
        if (category && metro) {
            if (metroParent) {
                newUrl = `${window.location.origin}/abogados/${category}/${metroParent}/${metro}/`;
            } else {
                newUrl = `${window.location.origin}/abogados/${category}/${metro}/`;
            }
            console.log('Redirecting to: ', newUrl); // Debugging statement
            window.location.href = newUrl; // Redirect to the new URL
        }
    });
});
