<?php
class wctest{
    public function __construct(){
        if(is_admin()){
	    	add_action('admin_menu', array($this, 'add_plugin_page'));
		}
    }
	
    public function add_plugin_page(){
        // This page will be under "Settings"
	add_options_page('Settings Admin', 'LinkPaper', 'manage_options', 'linkpaper-admin', array($this, 'create_admin_page'));
    }

    public function create_admin_page(){
    ?>

    <?php
        
    //TODO: enqueue CSS in wp
    $lp_white_list = (get_option('lp_white_list') != '') ? get_option('lp_white_list') : '[]';
    $lp_white_or_black = (get_option('lp-white-or-black') != '') ? get_option('lp-white-or-black') : 'black';
    ?>
	</pre>
	<script>
        function alternateSwitch($scope) {
            $scope.switchAlternate = 'off';
        }

        function linkCtrl($scope) {
            $scope.links = <?php echo $lp_white_list; ?>;
            $scope.whiteOrBlack = "<?php echo $lp_white_or_black; ?>";
            $scope.linkReset = "Reset White List";
            $scope.addRow = function() {
                $scope.links.push($scope.itemName);
                $scope.itemName = "";
            }
            $scope.resetLinks = function() {
                if ($scope.linkReset != "Undo") {
                    $scope.backup = $scope.links;
                    $scope.links = [];
                    $scope.linkReset = "Undo";
                } else {
                    $scope.linkReset = "Reset White List";
                    $scope.links = $scope.backup;
                }
            }
            $scope.removeLink = function(link){
                $scope.links.splice($scope.links.indexOf(link),1);
            }
        }

        function vendorCtrl($scope) {
            $scope.vendorList = <?php echo (get_option('lp_vendors') != '') ? get_option('lp_vendors') : "{preference:'skim', vendors: [{id: 'skim', key:'', name: 'Skim Links', refUrl: 'http://www.skimlinks.com'},{id: 'vig', key:'', name: 'VigLinks', refUrl: 'https://www.viglink.com/?vgref=956423'}]}"; ?>;
            $scope.change = function(t) {
                $scope.vendorList.preference = t;
                console.log(t);
            }
        }
    </script>
    <div id="lp-donate-box" class="lp-donate">
        If this plugin enhances your site, donate!
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="VRPN664QBTQ5N">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
	<div ng-app class="wrap lp-admin-app">
    <form action="options.php" method="post" name="options">
        <div ng-controller="vendorCtrl" id="lp_vendor">
            <h2>Select Your Vendor Settings</h2>
            <?php wp_nonce_field('update-options') ?>
            <table class="form-table lp-vendors" width="100%" cellpadding="10" id="lp-vendors">
            <tbody>
                <tr class="lp_button_bar"><td>Preferred</td><td>Vendor</td><td>Key</td><td><span>Get Account</span></td></tr>
                <tr ng-repeat="vendor in vendorList.vendors" class="lp-vendor" ng-class-odd="'odd'" ng-class-even="'even'">
                    <td><input type="radio" name="lp-vendor" value="{{vendor.id}}" ng-model="vendorList.preference"></td>
                    <td>{{vendor.name}}</td>
                    <td><input ng-model="vendor.key" type="text" name="{{vendor.id}}" value="{{vendor.key}}" ng-change="change('{{vendor.id}}')" class="lp-vendor-input"/></td>
                    <td><a href="{{vendor.refUrl}}" ng-if="vendor.key.length==0" target="_blank">Get {{vendor.name}}</a></td>
                </tr>
            </tbody>
            </table>
        <input type="hidden" name="lp_vendors" value={{vendorList}}>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="lp_vendors" />
        <input type="submit" name="Submit" value="Update"  class="button lp-submit-button"/>
        </div>
    </form>    
    <div ng-controller="linkCtrl" id="lp_link_table">
        <form action="options.php" method="post" name="options">
        <h2>Whitelist + Blacklist</h2>
        <?php wp_nonce_field('update-options') ?>
        <table class="form-table" width="100%" cellpadding="10" id="lp-link-table">
        <tbody>
            <tr class="lp_button_bar">
                <td align="center">
                    <input value="" type="text" placeholder="Add a URL" ng-model="itemName">
                </td>
                <td align="left">
                    <a href="" class="button" ng-click="addRow()">Add URL</a>
                </td>
                <td align="center">
                    <div class="onoffswitch">
                        <input ng-model="whiteOrBlack" ng-true-value="white" ng-false-value="black" type="checkbox" name="lp-white-or-black" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                        <label class="onoffswitch-label" for="myonoffswitch">
                            <span class="onoffswitch-inner">
                                <span class="onoffswitch-active"><span class="onoffswitch-switch">white</span></span>
                                <span class="onoffswitch-inactive"><span class="onoffswitch-switch">black</span></span>
                            </span>
                        </label>
                    </div>
                </td>
                <td align="right">
                    <a href="" class="button" ng-click="resetLinks()">{{linkReset}}</a>
                </td>
            </tr>
            <tr ng-repeat="link in links" class="lp_link_row" ng-class-odd="'odd'" ng-class-even="'even'" ng-animate="{enter: 'repeat-enter',leave: 'repeat-leave',move: 'repeat-move'}">
                <td class="lp_link_item" colspan="4"><a href="" ng-click="removeLink(link)"><img src="/wp-content/plugins/linkpaper/img/delete.png"/></a>{{link}}</td>
            </tr>
        </tbody>
        </table>
        <input type="hidden" name="lp_white_list" value={{links}}>
        <input type="hidden" name="lp-white-or-black" value={{whiteOrBlack}}>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="lp_white_list,lp-white-or-black" />
        <input type="submit" name="Submit" value="Update" class="button lp-submit-button"/>
        </form>
        </div>
    </div>
	<pre>
    <?php }
}
?>