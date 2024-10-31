<?php

register_activation_hook(__FILE__, 'install_readmorecopylink');

if(!class_exists("StormationReadMoreCopyLinkPlugin")){
	class StormationReadMoreCopyLinkPlugin{
		function addHeaderCode(){
			global $bp;
			$text = get_site_option('ReadMoreCopyLinkText');
			$limit = get_site_option('ReadMoreCopyLinkLimit');
			?>
			<!-- Using Stormation's 'Read More, Copy Link' plugin from stormation.info -->
			<script language="JavaScript">
				function addLink() {
					var bodyElement = document.getElementsByTagName('body')[0];
					var selection;
					selection = window.getSelection();
					var selectiontxt = selection.toString();
					<?php
					$currentURL = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
					$url = '<a href=\''.$currentURL.'\'>'.$currentURL.'</a>';
					$site = get_site_url();
					$sitetitle = get_bloginfo();
					$placeholders = array('{URL}', '{SITE}', '{SITETITLE}');
					$values = array($url, $site, $sitetitle);

					$pageLink = str_replace($placeholders, $values, $text);
					?>
					var pageLink = "<br /><br /> <?php echo $pageLink; ?>";
					var copytext = selection + pageLink;
					<?php if($limit > -1){ ?>
						var copytext = selectiontxt.substring(0, <?php echo $limit; ?>)+'... '+pageLink;
					<?php } ?>
					var attachDiv = document.createElement('div');
					attachDiv.style.position='absolute';
					attachDiv.style.left='-99999px';
					bodyElement.appendChild(attachDiv);
					attachDiv.innerHTML = copytext;
					selection.selectAllChildren(attachDiv);
					window.setTimeout(function() {
						bodyElement.removeChild(attachDiv);
					},0);
				}
				document.oncopy = addLink;
			</script>
			<?php
		}
		function addAdminMenus(){
			if(!menuExists('Stormation')) {
				add_menu_page('Stormation.info', 'Stormation', 'manage_options', 'Stormation', 'displayStormationOptionsPage2', plugins_url('icon16.png', __FILE__));
			}
			add_submenu_page('Stormation', 'Read More, Copy Link', 'Read More, Copy Link', 'manage_options', 'ReadMoreCopyLinkOptions', 'displayAdminOptionsPage2' );
			function displayStormationOptionsPage2(){
				echo '<br /><iframe id="StormationFrame" src="http://www.stormation.info/portfolio-category/wordpress/" width="98%" height="600px"></iframe>';
			}
			function displayAdminOptionsPage2(){
    			if(!current_user_can('manage_options')){
      				wp_die( __('You do not have sufficient permissions to access this page.') );
    			}else{	
    				echo "<div class=\"wrap\">";
				echo "<div id=\"icon-mass-messaging-options\" class=\"icon32\"><img src=\"".plugins_url('icon32.png', __FILE__)."\" width=32 height=32 /></div><h2>Read More, Copy Link Options</h2>";
				if(isset($_POST['update_ReadMoreCopyLinkOptions'])){
					if(isset($_POST['update_ReadMoreCopyLinkText'])){
               					update_site_option('ReadMoreCopyLinkText',$_POST['update_ReadMoreCopyLinkText']);
                			}
                			if(isset($_POST['update_ReadMoreCopyLinkLimit'])){
               					update_site_option('ReadMoreCopyLinkLimit',$_POST['update_ReadMoreCopyLinkLimit']);
                			} 
                			?><div class="updated"><p><strong>Options Updated</strong></p></div><?php
				}
				if(isset($_POST['reset_ReadMoreCopyLinkOptions'])){
					update_site_option('ReadMoreCopyLinkText', 'Read More at {URL} &copy; {SITETITLE}');
					update_site_option('ReadMoreCopyLinkLimit', -1);
					?><div class="updated"><p><strong>Options Reset</strong></p></div><?php
				}
				$oldReadMoreCopyLinkText= get_site_option('ReadMoreCopyLinkText');
				$oldReadMoreCopyLinkLimit= get_site_option('ReadMoreCopyLinkLimit');
				?>
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<table class="form-table">
					<tr valign="top">
					<th scope="row"><label for="update_ReadMoreCopyLinkText">Copy Link Text</label></th>
					<td><input name="update_ReadMoreCopyLinkText" type="text" id="update_ReadMoreCopyLinkText" value="<?php echo $oldReadMoreCopyLinkText; ?>" class="regular-text" />
					<p class="description">Use this box to decide how you want the read more to display. (HTML)<br />Use {URL} to show the page url.<br />Use {SITE} to show the site url.<br />Use {SITETITLE} to show the site title.<br />[Default= Read More at {URL} &copy; {SITETITLE}]</p></td>
					</tr>
					<tr valign="top">
					<th scope="row"><label for="update_ReadMoreCopyLinkLimit">Copy Limit</label></th>
					<td><input name="update_ReadMoreCopyLinkLimit" type="text" id="update_ReadMoreCopyLinkLimit" value="<?php echo $oldReadMoreCopyLinkLimit; ?>" class="regular-text" />
					<p class="description">How many characters should be limited for copying (-1 = disabled).<br />[Default= -1]</p></td>
					</tr>
					</table>
					<table><tr><td><p class="submit"><input type="submit" name="update_ReadMoreCopyLinkOptions" id="submit" class="button-primary" value="Save Changes"  /></p></td>
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>"><td><p class="submit"><input type="submit" name="reset_ReadMoreCopyLinkOptions" id="submit" class="button-secondary" value="Reset to defaults"  /></p></td></form></tr>
					</table>
					</form></div>
                		<?php
				}
			}
		}
		
	}
}

if(class_exists("StormationReadMoreCopyLinkPlugin")){
	$S_ReadMoreCopyLink = new StormationReadMoreCopyLinkPlugin();
}

if(isset($S_ReadMoreCopyLink)){
	add_action('wp_head', array(&$S_ReadMoreCopyLink, 'addHeaderCode'), 1);
	add_action('admin_menu', array(&$S_ReadMoreCopyLink,'addAdminMenus'), 1);
}

function install_readmorecopylink(){
	add_option('ReadMoreCopyLinkText', 'Read More at {URL} &copy; {SITETITLE}');
	add_option('ReadMoreCopyLinkLimit', -1);
}
if(!function_exists('menuExists')) {
	function menuExists($handle, $sub = false) {
		global $menu, $submenu;
		if(!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
			return false;
		}	
		$check_menu = $sub ? $submenu : $menu;
		if(empty($check_menu)) {
			return false;
		}
		foreach($check_menu as $k => $item) {
			if($sub) {
				foreach($item as $sm) {
					if($handle == $sm[2]) {
						return true;
					}
				}
			} else {
				if($handle == $item[2]) {
					return true;
				}
			}
		}
		return false;
	}
}
?>