chooseImage();
$('#file-upload').on('fileuploaded', function () {
    chooseImage();
});

function chooseImage() {
    var url = $('#choose-images').data('url');
    $('#choose-images').load(url);
}

$('#choose-images').on('click', '.choose-image', function () {
    $('.choose-image').removeClass('chosen');
    $(this).addClass('chosen');
});
$('#choose').on('click', function () {
    var chosen = $('#choose-images .chosen img');
    chosen.each(function (index, chosen) {
        var formGroupChosen = $('#form-group-chosen img').add('#form-group-chosen input');
        formGroupChosen.each(function (index, img) {
            if (img instanceof HTMLImageElement) {
                $(img).prop('src', chosen.src);
            }
            if (img instanceof HTMLInputElement) {
                $(img).val(chosen.src);
            }
        })
    });
    $('#modal-upload').modal('hide');
});

$('#remove-chosen').on('click', function () {
    var formGroupChosen = $('#form-group-chosen img').add('#form-group-chosen input');
    formGroupChosen.each(function (index, img) {
        if (img instanceof HTMLImageElement) {
            $(img).prop('src', '');
        }
        if (img instanceof HTMLInputElement) {
            $(img).val('');
        }
    });
    return false;
});
