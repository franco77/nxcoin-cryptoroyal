<div class="card gredient-info-bg m-t-0 m-b-0">
    <div class="card-body">
        <h4 class="card-title text-white">My Wallet Balance</h4>
        <h5 class="card-subtitle text-white op-5"></h5>
        <div class="row m-t-30 m-b-20"> 
            <div class="col-sm-12 col-lg-12">
                <div class="row"> 
                    <div class="col-sm-12 col-md-3">
                        <div class="info d-flex align-items-center">
                            <div class="m-r-10">
                                <i class="mdi mdi-wallet text-white display-5 op-5"></i>
                            </div>
                            <div style="margin-left: 10px">
                                <h3 class="text-white mb-0">
                                    <?php
                                        echo currency($this->stackingmodel->get_amount(),'2','');
                                    ?>
                                </h3>
                                <span class="text-white op-5">Stacking Balance</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-sm-12 col-md-3">
                        <div class="info d-flex align-items-center">
                            <div class="m-r-10">
                                <img src="assets/logo-nx.png" style="width: 75px;">
                            </div>
                            <div style="margin-left: 10px">
                                <h3 class="text-white mb-0"><?php echo currency($this->walletmodel->cek_balance('A'),'2','') ?></h3>
                                <span class="text-white op-5">NXCC Wallet</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-sm-12 col-md-3">
                        <div class="info d-flex align-items-center">
                            <div class="m-r-10">
                                <img src="assets/logo-nx.png" style="width: 75px;">
                            </div>
                            <div style="margin-left: 10px">
                                <h3 class="text-white mb-0"><?php echo currency($this->walletmodel->cek_balance('B'),'2','') ?></h3>
                                <span class="text-white op-5">Bonus Entry Wallet</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <div class="info d-flex align-items-center">
                            <div class="m-r-10">
                                <img src="assets/logo-nx.png" style="width: 75px;">
                            </div>
                            <div style="margin-left: 10px">
                                <h3 class="text-white mb-0">
                                    <?php $a = $this->walletmodel->cek_balance('C') * $this->walletmodel->getPriceNx(); 
                                        echo '$ '.number_format($a,2,'.',',');
                                     ?></h3>
                                <span class="text-white op-5">Jackpot Wallet</span>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>