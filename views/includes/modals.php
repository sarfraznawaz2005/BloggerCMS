<!-- delete confirm modal start -->
<div id="modal-delete-confirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #b94a48; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 style="margin: 0;"><i class="fa fa-trash-o"></i> Delete</h4>
            </div>
            <div class="modal-body">
                <p class="pull-left" style="margin-right: 10px;"><i class="fa fa-4x fa-question-circle"></i></p>

                <p>You are about to delete, this procedure is irreversible.</p>

                <p>Do you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-danger dialogdelete"><i class="fa fa-trash-o"></i> Yes</a>
                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default secondary delete-dialog-cancel"><i class="fa fa-ban-circle"></i>
                    No</a>
            </div>
        </div>
    </div>
</div>
<!-- delete confirm modal end -->

<!-- generate modal start -->
<div id="modal-generate" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="margin: 0;"><i class="fa fa-paper-plane"></i> Generating Blog</h4>
            </div>
            <div class="modal-body text-center">
                <span id="message"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- generate modal end -->