<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
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
                        <div class="contact1-pic js-tilt" data-tilt>
                            <img src="{{ asset('/frontend/images/mail.png') }}" alt="IMG">
                        </div>
                        <form method="post" id="home_footer_modal" class="contact1-form validate-form" enctype="multipart/form-data">
                            <span class="contact1-form-title">
                                Get in touch
                            </span>
                            <div class="form-group" id="form_result"></div>
                            <div class="wrap-input1 validate-input" data-validate = "Name is required">
                                <input class="input1" type="text" name="name" id="name"  placeholder="Name">
                                <span class="shadow-input1"></span>
                            </div>
                            <div class="wrap-input1 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                                <input class="input1" type="text" name="email"  id="email" placeholder="Email">
                                <span class="shadow-input1"></span>
                            </div>
                            <div class="wrap-input1 validate-input" data-validate = "Mobile is required">
                                <input class="input1" type="text" name="mobile"  id="mobile"  placeholder="Mobile">
                                <span class="shadow-input1"></span>
                            </div>
                            <div class="wrap-input1 validate-input" data-validate = "Message is required">
                                <textarea class="input1" name="message" id="message"  placeholder="Message"></textarea>
                                <span class="shadow-input1"></span>
                            </div>
                            <div class="container-contact1-form-btn">
                                <button id="home_footer_modal_btn" class="contact1-form-btn"><span>Send Email<i class="fa fa-long-arrow-right" aria-hidden="true"></i></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end adding form -->
        </div>
    </div>
</div>
