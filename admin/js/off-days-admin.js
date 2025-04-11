jQuery(document).ready(function($) {
    const input = $('#off_day_input');
    const button = $('#add_off_day_btn');
    const table = $('#off_days_table tbody');

    function loadOffDays() {
        $.ajax({
            url: locationAjax.ajax_url,
            data: { action: 'get_off_days' },
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                table.empty();
                $.each(data, function(index, day) {
                    const row = $('<tr>');
                    const dateCell = $('<td>').text(day);
                    const removeBtnCell = $('<td>');
                    const removeBtn = $('<button>').text('❌').addClass('button button-secondary');

                    removeBtn.on('click', function() {
                        $.post(locationAjax.ajax_url, {
                            action: 'delete_off_day',
                            day: day
                        }, function() {
                            loadOffDays();
                        });
                    });

                    removeBtnCell.append(removeBtn);
                    row.append(dateCell, removeBtnCell);
                    table.append(row);
                });
            }
        });
    }

    button.on('click', function() {
        const date = input.val();
        if (!date) {
            alert('Please select a date');
            return;
        }

        $.post(locationAjax.ajax_url, {
            action: 'add_off_day',
            day: date
        }, function() {
            input.val('');
            loadOffDays();
        });
    });


    loadOffDays();
});