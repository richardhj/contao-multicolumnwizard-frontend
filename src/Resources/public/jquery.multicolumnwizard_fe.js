$(function () {
    $(document).on('click', 'table.multicolumnwizard a[data-operations="new"]', function (event) {
        event.preventDefault();

        var clonedRow = $(this).closest('tr').clone();
        clonedRow.find('input').val('');
        $(this).closest('tr').after(clonedRow);

        updateMcwInputs($(this).closest('table'));
    });

    $(document).on('click', 'table.multicolumnwizard a[data-operations="up"]', function (event) {
        event.preventDefault();
        var row = $(this).parents('tr:first');
        row.insertBefore(row.prev());

        updateMcwInputs($(this).closest('table'));
    });

    $(document).on('click', 'table.multicolumnwizard a[data-operations="down"]', function (event) {
        event.preventDefault();
        var row = $(this).parents('tr:first');
        row.insertAfter(row.prev());

        updateMcwInputs($(this).closest('table'));
    });

    $(document).on('click', 'table.multicolumnwizard a[data-operations="delete"]', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();

        updateMcwInputs($(this).closest('table'));
    });

    function updateMcwInputs($table) {
        $table.find('tr[data-rowid]').each(function (index) {
            $(this).attr('data-rowid', index);

            $(this).find('label[for]').each(function () {
                $(this).attr('for', $(this).attr('for').replace(/(.+?)(\[\d])(\[.+?])/, '$1[' + index + ']$3'))
            });

            $(this).find('*[name][id]').each(function () {
                var name = $(this).attr('name').replace(/(.+?)(\[\d])(\[.+?])/, '$1[' + index + ']$3');
                $(this).attr('name', name);
                $(this).attr('id', 'ctrl_' + name);
            });
        });
    }
}(jQuery));
