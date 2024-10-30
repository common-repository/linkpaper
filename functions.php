<?php 

add_action('init', 'jqueryCheck');
add_action('init', 'angularCheck');
add_action('wp_footer', 'embedLinkPaper',20);
add_action( 'wp_enqueue_scripts', 'lp_add_styles' );
add_action( 'admin_enqueue_scripts', 'lp_add_styles' ); 


function lp_add_styles() {

    wp_deregister_style('linkpaper-style');
    wp_register_style('linkpaper-style', plugins_url('/css/lp-style.css', __FILE__),false, '1.0.0');
    wp_enqueue_style('linkpaper-style');
    
    wp_deregister_style('bootstrap-3.0.0');
    wp_register_style('bootstrap-3.0.0', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css',false,'3.0.0');
    wp_enqueue_style('bootstrap-3.0.0');
}

function embedLinkPaper() {
	if (!is_admin()) {
        $lp_vendors = (get_option('lp_vendors') != '') ? get_option('lp_vendors') : '';
        $lp_vendors = json_decode($lp_vendors, true);
        $vendor_pref = $lp_vendors['preference'];
        echo $vendor_pref;
        if ($lp_vendors!='') {
            foreach ($lp_vendors['vendors'] as $lp_vendor) {
                if ($vendor_pref==$lp_vendor['id']) {
                    jsDump($lp_vendor['key'],$vendor_pref);
                    return;
                }
            } 
        }
	}
}

function jqueryCheck() {
	if (!is_admin()) {
		//wp_deregister_script('jquery-2.1.1');
		//wp_register_script('jquery-2.1.1', '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', false, '2.1.1', true);
		//wp_enqueue_script('jquery-2.1.1');
	}
}

function angularCheck() {
	if (is_admin()) {
		wp_deregister_script('angular-1.2.18');
		wp_register_script('angular-1.2.18', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.18/angular.min.js', false, '1.2.18', true);
		wp_enqueue_script('angular-1.2.18');
	}
}

function jsDump($refID,$vig_or_skim) { 
	$lp_white_list = (get_option('lp_white_list') != '') ? get_option('lp_white_list') : '[]';
    $lp_white_or_black = (get_option('lp-white-or-black') != '') ? get_option('lp-white-or-black') : 'black';
    //$vig_or_skim = (get_option('lp-vendor') != '') ? get_option('lp-vendor') : '';
    //TODO: pass php variables into links.js
	?><script>
        var pHost = window.location.host;
        var pUrl = encodeURIComponent(window.location.href);
        var whiteList = <?php echo $lp_white_list;?>;
        var lpColor = '<?php echo $lp_white_or_black; ?>';
        var lpVendor = '<?php echo $vig_or_skim; ?>';
        if (lpVendor == 'vig') {
            front = "http://redirect.viglink.com?key=<?php echo $refID; ?>&u=";
            tail = "";
        } else if (lpVendor == 'skim') {
            front = "http://go.redirectingat.com/?id=<?php echo $refID; ?>&xs=1&url=";
            tail = "&sref=" + pUrl;
        } else {
            
        }
        function in_array(needle, haystack) {
            for (var i=0, len=haystack.length;i<len;i++) {
                var str = needle;
                var patt = new RegExp(haystack[i]);
                if (patt.test(str)) {
                    return true;
                }
            }
            return false;
        }

        jQuery('#content').find('a[href]').each(function() {
            pathArray = this.href.split( '/' );
            lHost = pathArray[2];
            if (this.href.charAt(0)=="#") {
                return;
            } else if (this.href.charAt(0)=="") {
                return;
            } else if (pHost == lHost) {
                return;
            } else {
                url = front + encodeURIComponent(this.href) + tail;
                if (in_array(lHost,whiteList) && lpColor=="white") {
                    this.href = url;
                } else if (!in_array(lHost,whiteList) && lpColor=="black") {
                    this.href = url;
                } else {
                    return;
                }
            }
        }).end();
	</script>
	
    <?php
}
?>