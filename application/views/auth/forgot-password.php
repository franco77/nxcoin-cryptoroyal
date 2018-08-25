<h5 class="font-medium m-b-20">Forgot Password</h5>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
		                <?php echo form_open('', array('class' => 'form-signin'), null); ?>

		                	<?php echo $this->session->flashdata('login_flash'); ?>

			                <div class="text-center">
								<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
								<h5 class="content-group"><small class="display-block">We will send information using E-Mail confirmations</small></h5>
							</div>

							<?php echo $this->session->flashdata('forgot_flash'); ?>
							<div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="ti-user"></i></span>
                                </div>
                                <input type="email" name="forgot_email" class="form-control form-control-lg" placeholder="Your E-Mail" aria-label="Password" aria-describedby="basic-addon1">
                            </div> 

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                </div>
                                <input type="text" name="forgot_username" class="form-control form-control-lg" placeholder="Your Username" aria-label="Password" aria-describedby="basic-addon1">
                            </div>   

							<div class="pull-left">
                                <button type="submit" name="do_forgot_password" value="true" class="btn btn-primary btn-lg mt-20 has-gradient-to-right-bottom btn-block font-2x">
									Reset password <i class="icon-arrow-right14 position-right"></i>
								</button>
                            </div>
                            <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        Already Remember? <a href="<?php echo base_url() ?>" class="text-info m-l-5"><b>Login</b></a>
                                    </div>
                                </div>
		                </form>
		            