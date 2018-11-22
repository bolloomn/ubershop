<?php /* Template Name: login */

if(isset($_POST['login_Sbumit'])) {
    $creds                  = array();
    $creds['user_login']    = stripslashes( trim( $_POST['userName'] ) );
    $creds['user_password'] = stripslashes( trim( $_POST['passWord'] ) );
    $creds['remember']      = '';
    $redirect_to            = esc_url_raw( $_POST['redirect_to'] );
    $secure_cookie          = null;

    if($redirect_to == '')
        $redirect_to= get_site_url(). '/dashboard/' ;

    if ( ! force_ssl_admin() ) {
        $user = is_email( $creds['user_login'] ) ? get_user_by( 'email', $creds['user_login'] ) : get_user_by( 'login', sanitize_user( $creds['user_login'] ) );

        if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
            $secure_cookie = true;
            force_ssl_admin( true );
        }
    }

    if ( force_ssl_admin() ) {
        $secure_cookie = true;
    }

    if ( is_null( $secure_cookie ) && force_ssl_login() ) {
        $secure_cookie = false;
    }

    $user = wp_signon( $creds, $secure_cookie );

    if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
        $redirect_to = str_replace( 'http:', 'https:', $redirect_to );
    }

    if ( ! is_wp_error( $user ) ) {
        wp_safe_redirect( $redirect_to );
    } else {
        if ( $user->errors ) {
            $errors['invalid_user'] = __('Нэвтрэх нэр эсвэл нууц үг буруу байна.');
        } else {
            $errors['invalid_user_credentials'] = 'Нэвтрэх нэр эсвэл нууц үг оруулна уу';
        }
    }
}

?>

<?php get_header(); ?>
<?php if ( have_posts() ) : ?>
    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <div id="content" class="clearfix">

                <div class="content-wrap">
                    <div class="entry-content">
                        <form name="loginform" id="loginform"  method="post">
                            <?php if(!empty($errors)) { foreach($errors as $err ) echo '<p style="font-size:13px; color: #d12333;">'.$err.'</p>';} ?>
                            <p class="login-username">
                                <label for="user_login">Нэвтрэх нэр</label>
                                <input type="text" name="userName" id="user_login" class="input" value="" >
                            </p>
                            <p class="login-password">
                                <label for="user_pass">Нууц үг</label>
                                <input type="password" name="passWord" id="user_pass" class="input" value="" >
                            </p>


                            <p class="login-submit" style="margin-top: 20px;">
                                <input type="hidden" name="login_Sbumit" >
                                <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Нэвтрэх">
                                <input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>">
                            </p>
                            <p style="margin-top: 15px; margin-bottom: 0px" >
                                <a href="<?php echo home_url('lost-password');?>" style="font-size:14px; float: left;"><i class="fa fa-key"></i> Нууц үг сэргээх</a>
                                <a href="<?php echo home_url('register');?>" style="font-size:14px;  float: right;"><i class="fa fa-user-plus"></i> Бүртгүүлэх</a>
                            </p>
                            <div style="clear: both;"></div>
                        </form>
                    </div><!-- .entry-content -->
                </div> <!-- .content-wrap -->

        </div><!-- #content -->
    <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>