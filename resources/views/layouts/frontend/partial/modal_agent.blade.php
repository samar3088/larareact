<div class="modal fade" id="myAgent" tabindex="-1" role="dialog" aria-labelledby="myAgentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closemodal">&times;</button>
            </div>
            <!-- start adding form -->
            <div class="modal-body">
                <div class="contact1">
                    <img src="{{ asset('/frontend/images/logo.png') }}" alt="IMG" class="logoclr">
                    <div class="container-contact1">
                        <form class="contact1-form validate-form" enctype="multipart/form-data" style="width: 100%;">
                            <span class="contact1-form-title" style="padding-bottom: 4px;">
                                Input <span class="evnt_clr"> Your Details</span>
                            </span>
                            <div class="form-group" id="form_result"></div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Please Enter Name</label>
                                    <input type="text" name="save_name" id="save_name" class="required form-control" style="border:2px solid #d2d2d2;height: 40px;" placeholder="Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Please Enter Email</label>
                                    <input type="text" name="save_email" id="save_email" class="required form-control" style="border:2px solid #d2d2d2;height: 40px;" placeholder="Valid email is required: ex@abc.xyz">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button type="button" id="show_cc" class="btn btn-secondary" value="Add Cc Mails" name="show_cc" style="background-color: #d4603b;border-color: #d25026;">Add Cc Mails</button>
                                    <button type="button" id="show_bcc" class="btn btn-secondary" value="Add Cc Mails" name="show_bcc" style="background-color: #d4603b;border-color: #d25026;">Add BCc Mails</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6" id="cc_mails_div">
                                    <label>Comma Separated Cc Mails</label>
                                    <input type="text" name="cc_mails" id="cc_mails" class="form-control" style="border:2px solid #d2d2d2;height: 40px;" placeholder="Comma Separated Cc Mails">
                                </div>
                                <div class="form-group col-md-6" id="bcc_mails_div">
                                    <label>Comma Separated BCc Mails</label>
                                    <input type="text" name="bcc_mails" id="bcc_mails" class="form-control" style="border:2px solid #d2d2d2;height: 40px;" placeholder="Comma Separated BCc Mails">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Please Enter Mobile</label>
                                    <input type="text" name="save_mobile" id="save_mobile" class="required form-control" style="border:2px solid #d2d2d2;height: 40px;" placeholder="Mobile">
                                </div>
                                <div class="form-group col-md-6">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Please Enter any special instruction</label>
                                    <textarea class="form-control" name="description" id="description"  placeholder="Special Instructions"></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button id="btnSend"  value="Proceed to Checkout" name="login" class="contact1-form-btn"><span>Submit<i class="fa fa-long-arrow-right" aria-hidden="true"></i></span></button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- end adding form -->
        </div>
    </div>
</div>
