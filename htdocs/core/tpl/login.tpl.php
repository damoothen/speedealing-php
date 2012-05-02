<?php
/* Copyright (C) 2009-2010 Regis Houssin 		<regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Laurent Destailleur 	<eldy@users.sourceforge.net>
 * Copyright (C) 2011-2012 Herve 				<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

header('Cache-Control: Public, must-revalidate');
header("Content-type: text/html; charset=".$conf->file->character_set_client);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- BEGIN PHP TEMPLATE -->
<html>

<?php
print '<head>
<meta name="robots" content="noindex,nofollow" />
<meta name="author" content="Speedealing Development Team" />
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- Foundation framework -->';
print '<link rel="stylesheet" href="'.DOL_URL_ROOT.'/theme/pertho_sample/foundation/stylesheets/foundation.css">';
print '<!-- Favicons and the like (avoid using transparent .png) -->';
print '<link rel="shortcut icon" type="image/x-icon" href="'.DOL_URL_ROOT.'/theme/favicon.ico"/>'."\n";
print '<link rel="apple-touch-icon-precomposed" href="'.DOL_URL_ROOT.'/theme/icon.png" />';

//<!-- Favicons and the like (avoid using transparent .png) -->
//<link rel="apple-touch-icon-precomposed" href="icon.png" />
//<link rel="shortcut icon" type="image/x-icon" href="'.$favicon.'"/>
print '<title>'.$langs->trans('Login').' '.$title.'</title>'."\n";

print '<!-- main styles -->';
print '<link rel="stylesheet" href="'.DOL_URL_ROOT.'/theme/pertho_sample/css/style.css" />';
if (! empty($conf->global->MAIN_HTML_HEADER)) print $conf->global->MAIN_HTML_HEADER;
print '<!-- HTTP_USER_AGENT = '.$_SERVER['HTTP_USER_AGENT'].' -->
</head>';

?>

<body class="ptrn_a grdnt_a">
    <div class="container">
        <div class="row">
            <div class="eight columns centered">            
            </div>
        </div>
            <div class="row">
                <div class="eight columns centered">
                    <div class="login_box">
                        <div class="lb_content">
                            <div class="login_logo"><img src="<?php echo $urllogo; ?>" width="120" alt="" /></div>
                                <div class="cf">
                                    <h2 class="lb_ribbon lb_blue"><span>Login to your account</span><span style="display:none">New password</span></h2>
                                    <a href="#" class="right small sl_link">
                                        <span>Forgot your password?</span>
                                        <span style="display:none">Back to login form</span>
                                    </a>
				</div>
                                <div class="row m_cont">
                                    <div class="eight columns centered">
                                        <div class="l_pane">
                                            <form name="login" action="<?php echo $php_self; ?>" method="post" class="nice" id="l_form">
                                                <input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />
                                                <input type="hidden" name="loginfunction" value="loginfunction" />
                                                <!-- Add fields to send local user information -->
                                                <input type="hidden" name="tz" id="tz" value="" />
                                                <input type="hidden" name="dst_observed" id="dst_observed" value="" />
                                                <input type="hidden" name="dst_first" id="dst_first" value="" />
                                                <input type="hidden" name="dst_second" id="dst_second" value="" />
                                                <input type="hidden" name="screenwidth" id="screenwidth" value="" />
                                                <input type="hidden" name="screenheight" id="screenheight" value="" />
						<div class="sepH_c">
                                                    <div class="elVal">
                                                        <label for="username"><?php echo $langs->trans('Login'); ?></label>
        						<input type="text" id="username" name="username" class="oversize expand input-text" value="<?php echo GETPOST('username')?GETPOST('username'):$login; ?>" tabindex="1" />
                                                    </div>
                                                    <div class="elVal">
                                                        <label for="password"><?php echo $langs->trans('Password'); ?></label>
							<input type="password" id="password" name="password" class="oversize expand input-text" tabindex="2" />
                                                    </div>
                                                    <?php
                                                        if (! empty($hookmanager->resArray['options'])) {
                                                            foreach ($hookmanager->resArray['options'] as $option)
                                                            {
                                                                echo '<!-- Option by hook -->';
                                                                echo '<div class="elVal">';
                                                                echo $option;
                                                                echo '</div>';
                                                            }
                                                        }
                                                    ?>

                                                    <?php if ($captcha) { ?>
                                                        <!-- Captcha -->
                                                        <tr><td valign="middle" nowrap="nowrap"> &nbsp; <b><?php echo $langs->trans('SecurityCode'); ?></b></td>
                                                        <td valign="top" nowrap="nowrap" align="left" class="none">

                                                        <table class="login_table" style="width: 100px;"><tr>
                                                        <td><input id="securitycode" class="flat" type="text" size="6" maxlength="5" name="code" tabindex="4" /></td>
                                                        <td><img src="<?php echo DOL_URL_ROOT ?>/core/antispamimage.php" border="0" width="80" height="32" /></td>
                                                        <td><a href="<?php echo $php_self; ?>"><?php echo $captcha_refresh; ?></a></td>
                                                        </tr></table>

                                                        </td></tr>
                                                    <?php } ?>
						</div>
						<div class="cf">
                                                    <label for="remember" class="left"><input type="checkbox" id="remember"> Remember me</label>
                                                    <input type="submit" class="button small radius right black" value="<?php echo $langs->trans('Connection'); ?>" tabindex="5"  />
						</div>
                                            </form>
					</div>
					<div class="l_pane" style="display:none">
                                            <form action="dashboard.html" method="post" class="nice" id="rp_form">
                                                <div class="sepH_c">
                                                    <p class="sepH_b">Please enter your email address. You will receive a link to create a new password via email.</p>
                                                    <div class="elVal">
                                                        <label for="upname">E-mail:</label>
                                                        <input type="text" id="upname" name="upname" class="oversize expand input-text" />
                                                    </div>
						</div>
						<div class="cf">
                                                    <input type="submit" class="button small radius right black" value="Get new password" />
						</div>
                                            </form>
					</div>
                                    </div>
				</div>
                            </div>
			</div>
                    </div>
		</div>

</div>


<?php if (! empty($_SESSION['dol_loginmesg']))
{
?>
	<center><table width="60%"><tr><td align="center"><div class="error">
	<?php echo $_SESSION['dol_loginmesg']; ?>
	</div></td></tr></table></center>
<?php
}
?>

<?php if ($main_home)
{
?>
	<center><table summary="info" cellpadding="0" cellspacing="0" border="0" align="center" width="750">
	<tr><td align="center">
	<?php echo $main_home; ?>
	</td></tr></table></center><br>
<?php
}
?>

<?php
if (! empty($conf->global->MAIN_GOOGLE_AD_CLIENT) && ! empty($conf->global->MAIN_GOOGLE_AD_SLOT))
{
?>
	<div align="center">
		<script type="text/javascript"><!--
			google_ad_client = "<?php echo $conf->global->MAIN_GOOGLE_AD_CLIENT ?>";
			google_ad_slot = "<?php echo $conf->global->MAIN_GOOGLE_AD_SLOT ?>";
			google_ad_width = <?php echo $conf->global->MAIN_GOOGLE_AD_WIDTH ?>;
			google_ad_height = <?php echo $conf->global->MAIN_GOOGLE_AD_HEIGHT ?>;
			//-->
		</script>
		<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
<?php
}
?>

<!-- authentication mode = <?php echo $main_authentication ?> -->
<!-- cookie name used for this session = <?php echo $session_name ?> -->
<!-- urlfrom in this session = <?php echo $_SESSION["urlfrom"] ?> -->

<?php if (! empty($conf->global->MAIN_HTML_FOOTER)) print $conf->global->MAIN_HTML_FOOTER; ?>
    </div>
    
    <script src="<?php echo DOL_URL_ROOT ?>/includes/js/jquery.min.js"></script>
    <script src="<?php echo DOL_URL_ROOT ?>/includes/js/s_scripts.js"></script>
    <script src="<?php echo DOL_URL_ROOT ?>/includes/lib/validate/jquery.validate.min.js"></script>
    <script>
			$(document).ready(function() {
				$(".sl_link").click(function(event){
					$('.l_pane').slideToggle('normal').toggleClass('dn');
					$('.sl_link,.lb_ribbon').children('span').toggle();
					event.preventDefault();
				});

				$("#l_form").validate({
					highlight: function(element) {
						$(element).closest('.elVal').addClass("form-field error");
					},
					unhighlight: function(element) {
						$(element).closest('.elVal').removeClass("form-field error");
					},
					rules: {
						username: "required",
						password: "required"
					},
					messages: {
						username: "Please enter your username (type anything)",
						password: "Please enter a password (type anything)"
					},
					errorPlacement: function(error, element) {
						error.appendTo( element.closest(".elVal") );
					}
				});

				$("#rp_form").validate({
					highlight: function(element) {
						$(element).closest('.elVal').addClass("form-field error");
					},
					unhighlight: function(element) {
						$(element).closest('.elVal').removeClass("form-field error");
					},
					rules: {
						upname: {
							required: true,
							email: true
						}
					},
					messages: {
						upname: "Please enter a valid email address"
					},
					errorPlacement: function(error, element) {
						error.appendTo( element.closest(".elVal") );
					}
				});
			});
    </script>
</body>
</html>
<!-- END PHP TEMPLATE -->
