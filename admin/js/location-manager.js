jQuery(document).ready(function ($) {
    $('#add-location').on('click', function () {
        const loc = $('#location-input').val().trim();
        if (!loc) return alert('Please enter a location name');

        $.post(locationAjax.ajax_url, {
            action: 'add_location',
            location: loc,
            _ajax_nonce: locationAjax.nonce
        }, function (res) {
            if (res.success) {
                $('#location-list').append(`
                    <tr data-location="${res.data}">
                        <td>${res.data}</td>
                        <td><button class="button delete-location">Delete</button></td>
                    </tr>
                `);
                $('#location-input').val('');
            } else {
                alert('Error adding location');
            }
        });
    });

    $(document).on('click', '.delete-location', function () {
        const row = $(this).closest('tr');
        const loc = row.data('location');

        $.post(locationAjax.ajax_url, {
            action: 'delete_location',
            location: loc,
            _ajax_nonce: locationAjax.nonce
        }, function (res) {
            if (res.success) {
                row.remove();
            } else {
                alert('Failed to delete location');
            }
        });
    });
});
