/**
 * Created by Sarfraz on 4/1/2015.
 */

$('body').on('click', '.confirm-delete', function (e) {
    var id = $(this).data('id');
    var label = $(this).data('label');

    $('#modal-delete-confirm').find('.modal-body p:eq(1)').html('You are about to delete <strong>' + label + '</strong>, this procedure is irreversible.');
    $('#modal-delete-confirm').find('.dialogdelete').attr('href', this.href);
    $('#modal-delete-confirm').data('id', id).modal('show');

    e.preventDefault();
});

$('body').on('click', '.confirm-delete2', function (e) {
    var id = $(this).data('id');
    var label = $(this).data('label');

    $('#modal-delete-confirm').find('.modal-body p:eq(1)').html('You are about to delete <strong>' + label + '</strong> item.');
    $('#modal-delete-confirm').find('.dialogdelete').attr('href', this.href);
    $('#modal-delete-confirm').data('id', id).modal('show');

    e.preventDefault();
});

$('.table').not('.nodatatable').dataTable({
    "bAutoWidth": true,
    "iDisplayLength": 25,
    "ordering": false,
    "bFilter": false,
    "bRetrieve": true,
    "bLengthChange": false
});

$('.select2, select').select2();
$('.tags').select2({"tags": true});

// generate blog modal
$('#generate_blog').click(function () {
    var $modal = $('#modal-generate');

    $modal.find('.modal-body #message').html('<i class="fa fa-circle-o-notch fa-spin"></i> Working, please wait...');

    $modal.modal({"show": true, "backdrop": "static"});
    $modal.find('.modal-footer').hide();

    $.get(__root + '/generate', function (response) {
        $modal.find('#message').html(response);
        $modal.find('.modal-footer').slideDown();
    });
});

$('body').on('hidden.bs.modal', '.modal', function () {
    $('.modal-body #message', $(this)).empty();
    $(this).removeData('bs.modal');
});

// view generate log
$('body').on('click', '#viewGenLog', function () {
    $('#genlog').slideToggle();
});