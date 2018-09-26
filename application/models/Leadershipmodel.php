<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leadershipmodel extends CI_Model {
    private $min_stacking_amount = 2001;
    protected $qualifications = [
        1 => [
            'min_people' => 10,
            'min_invest' => 2001,
            'min_omset' => 27000,
            'sponsors_qualification' => [
                'min_people' => 0,
                'with_stars' => 0
            ]
        ],
        2 => [
            'min_people' => 10,
            'min_invest' => 2001,
            'min_omset' => 100000,
            'sponsors_qualification' => [
                'min_people' => 3,
                'with_stars' => 1
            ]
        ],
        3 => [
            'min_people' => 10,
            'min_invest' => 2001,
            'min_omset' => 330000,
            'sponsors_qualification' => [
                'min_people' => 3,
                'with_stars' => 2
            ]
        ],
        4 => [
            'min_people' => 10,
            'min_invest' => 2001,
            'min_omset' => 1050000,
            'sponsors_qualification' => [
                'min_people' => 3,
                'with_stars' => 3
            ]
        ],
        
    ];
    protected $bonuses = [
        1 => 1,
        2 => 3,
        3 => 4,
        4 => 5
    ]; // bonus percentage

    public function __construct() {
        $this->load->model('stackingmodel');
    }

    public function get_leader_detail( $userid = NULL ) {
        
        $userid = ($userid) ? $userid : userid();

        $networks = $this->usermodel->get_networks( $userid );
        $direct_referral_id = $this->extract_direct_referral($userid, $networks);

        
        // QUALIFICATIONS
        $people = $this->stackingmodel->get_stacking_batch($direct_referral_id,$this->min_stacking_amount)->num_rows(); // how many people under this userid which qualified
        //$invest         = $this->stackingmodel->get_amount( $userid ); // total investation of this user
        $omset          = $this->stackingmodel->get_omset_jaringan( $userid ); // how many omset of this user
        $omset          = ($omset) ? $omset : 0;
        $sponsor_stars  = $this->calc_sponsor_stars( $networks );
        

        $leader_detail = [
            'people'        => $people,
            //'invest'        => $invest,
            'omset'         => $omset,
            'sponsor_stars' => $sponsor_stars,
            'direct_referral_id' => $direct_referral_id
        ];
        $leader_detail['achivements'] = $this->decide_stars( $leader_detail );
        
        return $leader_detail;

    }

    private function decide_stars($leader_detail) {
        
        $star = 0;
        $stars_qualification = [];
        
        foreach( $this->qualifications as $key => $qualification ) {

            $min_people_qualified       = FALSE;
            $min_invest_qualified       = FALSE;
            $min_omset_qualified        = FALSE;
            $min_sponsor_star_qualified = FALSE;
            $achived                    = FALSE;

            if( $leader_detail['people'] >= $qualification['min_people'] ) {
                $min_people_qualified = TRUE;
            }

            // if( $leader_detail['invest'] >= $qualification['min_invest'] ) {
            //     $min_invest_qualified = TRUE;
            // }

            if( $leader_detail['omset'] >= $qualification['min_omset'] ) {
                $min_omset_qualified = TRUE;
            }

            if( $qualification['sponsors_qualification']['min_people'] > 0 ) {
                $sponsor_qual = $qualification['sponsors_qualification'];
                
                $with_stars = $sponsor_qual['with_stars'];
                $min_people = $sponsor_qual['min_people'];

                if( $leader_detail['sponsor_stars'][$with_stars] >= $min_people ) {
                    $min_sponsor_star_qualified = TRUE;
                }

            } else {

                $min_sponsor_star_qualified = TRUE;

            }

            if(
                $min_people_qualified &&
                $min_invest_qualified &&
                $min_omset_qualified &&
                $min_sponsor_star_qualified
            ) {
                $star++;
                $achived = TRUE;

            }
            
            
            $stars_qualification['star_'.$key] = [
                'min_people_qualified' => (string) $min_people_qualified,
                //'min_invest_qualified' => (string) $min_invest_qualified,
                'min_omset_qualified' => (string) $min_omset_qualified,
                'min_sponsor_star_qualified' => (string) $min_sponsor_star_qualified,
                'achived' => $achived
            ];

        }
        return [
            'star' => $star,
            'stars_qualification' => $stars_qualification
        ];

    }
    
    // calc how many stars of sponsor under this user
    private function calc_sponsor_stars( $networks ) {

        $stars = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0
        ];
        if(count($networks) > 0 ) {

            foreach( $networks as $net ) {
                
                if($net->user_stars != 0) {
                    $stars[ $net->user_stars ] += 1;
                }
    
            }

        }

        return $stars;

    }

    public function calc_bonus( $star, $staking_amount ) {

        $percentage = $this->bonuses[$star];
        $bonus = bcdiv( bcmul($staking_amount, $percentage, 8), "100", 8 );
        return $bonus;

    }
    public function extract_direct_referral($userid, $networks) {
        $direct_ref = [];
        foreach( $networks as $net ) {
            if($net->referral_id == $userid) {
                $direct_ref[] = $net->id;
            }
        }
        return $direct_ref;
    }

    public function update_star($userid, $star) {
        
        $updated = $this->db
            ->set('user_stars', $star)
            ->where('id', $userid)
            ->update('tb_users');

        if($updated) {

            return 1;
            
        }
        return 0;
    }

    public function get_leaders( $select = NULL ) {

        $select = ($select) ? $select : '*';

        return $this->db->select($select)
        ->from('tb_users')
        ->where('user_stars >',0)
        ->order_by('user_stars', 'desc')
        ->get();
    }
}