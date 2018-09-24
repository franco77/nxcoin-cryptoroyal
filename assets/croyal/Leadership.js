$(document).ready(function() {
    $.fn.CrLeadership = function(options) {

        var star_qualification = {
            1: {
                'min_people' : 10,
                'min_invest' : 2001,
                'min_omset' : 27000,
                'sponsors_qualification' : {
                    'min_people' : 0,
                    'with_stars' : 0
                }
            },
            2: {
                'min_people' : 10,
                'min_invest' : 2001,
                'min_omset' : 100000,
                'sponsors_qualification' : {
                    'min_people' : 3,
                    'with_stars' : 1
                }
            },
            3: {
                'min_people' : 10,
                'min_invest' : 2001,
                'min_omset' : 330000,
                'sponsors_qualification' : {
                    'min_people' : 3,
                    'with_stars' : 2
                }
            },
            4: {
                'min_people' : 10,
                'min_invest' : 2001,
                'min_omset' : 1050000,
                'sponsors_qualification' : {
                    'min_people' : 3,
                    'with_stars' : 3
                }
            },
        }
        var NEXT_ACHIVEMENT = null;

        var url_detail = env.site_url + '/leadership/detail';
        var _this = this;
        var detail_template = `
        <style>.progress { background-color:#ededed; }</style>
            <div class="card">
                <div class="card-body">
                    <h3>Your Star: <span id="curr_star"></span></h3>
                    <hr>
                    <h5>Next Achievement to Reach <span id="next_star"></span> Star</h5>
                    
                    
                    <label for="">Referral <span id="ach_ref_label"><span></label>
                    <div class="progress" id="ach_ref_progress" style="height:20px;">
                    
                        <div id="ach_ref_bar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="1" aria-valuemax="100">
                            <span class="sr-only">  % Complete</span>
                        </div>
                    </div>
                    <br>
                    <label for="">Staking <span id="ach_staking_label">(450/20001)</span></label>
                    <div id="ach_staking_progress" class="progress" style="height:20px;">
                    
                        <div id="ach_staking_bar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="1" aria-valuemax="100" style="width: 100%;">
                            <span class="sr-only">  % Complete</span>
                        </div>
                    </div>
                    <br>
                    <label for="">Omset <span id="ach_omset_label">(450/20001)</span></label>
                    <div id="ach_omset_progress" class="progress" style="height:20px;">
                    
                        <div id="ach_omset_bar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="1" aria-valuemax="100" style="width: 100%;">
                            <span class="sr-only">  % Complete</span>
                        </div>
                    </div>

                    <br>
                    <label id="ach_sponsor" for="">Sponsor with <span id="star_achivement">0</span> Star <span id="ach_sponsor_star_label">(1/3)</span></label>
                    <div id="ach_sponsor_star_progress" class="progress" style="height:20px;">
                    
                        <div id="ach_sponsor_star_bar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="1" aria-valuemax="100" style="width: 100%;">
                            <span class="sr-only">  % Complete</span>
                        </div>
                    </div>
                    
                </div>
            </div>
        `;

        

        this.onGetDetail = function(res) {
            console.log(res);
            _this.build(res);
        };

        this.build = function(data) {
            var $temp       = $(detail_template);
            var curr_star   = data.achivements.star;
            
            var curr_referral   = data.people;
            var curr_staking    = data.invest;
            var curr_omset      = parseFloat(data.omset);
            var curr_sponsor_with_star = data.sponsor_stars[ curr_star+1 ];

            NEXT_ACHIVEMENT     = star_qualification[ curr_star+1 ];

            sponsors_qualification  = NEXT_ACHIVEMENT.sponsors_qualification;
            sponsor_with_stars      = sponsors_qualification.with_stars;
            sponsor_min_people      = sponsors_qualification.min_people;
            
            referral_progress       = ( curr_referral / NEXT_ACHIVEMENT.min_people) * 100;
            staking_progress        = (curr_staking / NEXT_ACHIVEMENT.min_invest) * 100;
            omset_progress          = (curr_omset / NEXT_ACHIVEMENT.min_omset) * 100;
            sponsor_qual_progress   = (curr_sponsor_with_star / sponsor_min_people) * 100;

            

            console.log(curr_omset);

            $temp.find("#next_star").html(curr_star+1);
            $temp.find("#ach_ref_label").html('('+curr_referral+'/'+NEXT_ACHIVEMENT.min_people+')');
            $temp.find("#ach_staking_label").html('('+curr_staking+'/'+NEXT_ACHIVEMENT.min_invest+')');
            $temp.find("#ach_omset_label").html('('+curr_omset+'/'+NEXT_ACHIVEMENT.min_omset+')');
            $temp.find("#ach_sponsor_star_label").html('('+curr_sponsor_with_star+'/'+sponsor_min_people+')');
            $temp.find("#star_achivement").html(sponsor_with_stars);

            $temp.find("#ach_ref_bar").css("width",referral_progress+"%");
            $temp.find("#ach_staking_bar").css("width",staking_progress+"%");
            $temp.find("#ach_omset_bar").css("width",omset_progress+"%");
            $temp.find("#ach_sponsor_star_bar").css("width",sponsor_qual_progress+"%");

            if(curr_star == 0) {
                $temp.find("#ach_sponsor,#ach_sponsor_star_progress").hide();
            }
            
            var outline = '<i class="mdi mdi-star-outline"></i>';
            var bold = '<i class="mdi mdi-star"></i>';

            for(var i=1; i <= 4; i++) {
                if(i <= curr_star) {
                    $temp.find("#curr_star").append(bold);
                } else {
                    $temp.find("#curr_star").append(outline);
                }
                
            }
            this.html($temp);
            
        };

        

        this.getAchievments = function() {
            $.ajax({
                method: 'GET',
                url: url_detail,
                type: 'application/json',
                success: _this.onGetDetail
            });
            return _this;
        };
        return this;
    };
})