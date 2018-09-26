<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bonus extends CI_Controller {

    protected $in_debug_mode = FALSE;

    public function __construct() {

        parent::__construct();
        if(php_sapi_name() !== 'cli') {
            exit('You busted...!');
        }
    }

    public function run($type, $mode = FALSE) {

        $this->in_debug_mode = (boolean) $mode;

        switch ($type) {
            case 'leadership':
                $this->leadership();
                break;
        }
        
        $this->mailermodel->send('sugamirza2@gmail.com','Cryptoroyal - Bonus Command','its running dude!');


    }

    private function leadership() {
        
        $this->load->model('leadershipmodel');
        $this->load->model('stackingmodel');

        $leaders = $this->leadershipmodel->get_leaders(['id','username','user_stars']);

        if($leaders->num_rows() <= 0) {
            exit( 'dont have leaders'."\n" );

        }
        $leaders = $leaders->result();
        
        
        foreach( $leaders as $leader ) {
            
            $lead_network = $this->usermodel->get_networks($leader->id,'id');
            $new_stacking = false;

            if($lead_network) {

                $new_stacking = $this->stackingmodel->get_new_stacking($lead_network);
                $new_stacking = ($new_stacking->num_rows() > 0 ) ? $new_stacking->result() : false;

            }

            $leader_bonuses = [];
            if($new_stacking) {

                foreach( $new_stacking as $stacking ) {

                    $bonus_alocation = $this->bonusmodel->calc_leadership(
                        $stacking->stc_amount,
                        $leader->user_stars
                    );
                    
                    $leader_bonuses[] = $bonus_alocation;

                }

            }

            if(!empty($leader_bonuses)) {

                $total_bonus = [
                    'A' => 0,
                    'B' => 0,
                    'C' => 0
                ];
                foreach($leader_bonuses as $bonus_key => $bonus) {

                    $total_bonus['A'] = bcadd( (string) $total_bonus['A'], (string) $bonus['A'], 8 );
                    $total_bonus['B'] = bcadd( (string) $total_bonus['B'], (string) $bonus['B'], 8 );
                    $total_bonus['C'] = bcadd( (string) $total_bonus['C'], (string) $bonus['C'], 8 );
                    

                }
                if(!$this->in_debug_mode) {
                    $this->bonusmodel->insert_leadership($leader->id, $total_bonus);
                }

            }

            
        }


    }

}