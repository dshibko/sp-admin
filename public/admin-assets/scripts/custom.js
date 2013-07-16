var elements = [];


$(document).on('ready', function(){
    bindInputs();
});

function bindInputs() {
    $("form").find('input, textarea, select').each(function(i, obj) {
        if ($(obj).prop("tagName").toLowerCase() == 'input' && $(obj).attr('type').toLowerCase() == 'checkbox')
            elements[obj.name] = $(obj).is(":checked");
        else
            elements[obj.name] = $(obj).val();
    }).on('change', onChangeListener);
    $("form").submit(function() {
        $(window).off('beforeunload', pageUnload);
    });
}

function onChangeListener() {
    if ($(this).prop("tagName").toLowerCase() == 'input' && $(this).attr('type').toLowerCase() == 'checkbox')
        var value = $(this).is(":checked");
    else
        var value = $(this).val();
    if (elements[this.name] != value) {
        $(window).on('beforeunload', pageUnload);
        $("form").find('input, select').off('change', onChangeListener);
    }
}

function pageUnload() {
    return 'Are you sure you want to leave the page without saving the changes?';
}