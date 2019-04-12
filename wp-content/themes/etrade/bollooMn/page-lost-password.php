<?php /* Template Name: lost password */ ?>



<?php
if(is_user_logged_in()) {wp_redirect(home_url()); }
        global $wpdb;

        $error = '';
        $success = '';

        // check if we're in reset form
        if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] )
        {
            $email = trim($_POST['user_login']);

            if( empty( $email ) ) {
                $error = 'Нэвтрэх нэр эсвэл имэйл хаягаа оруулна уу.';
            } else if( ! is_email( $email )) {
                $error = 'Нэвтрэх нэр эсвэл имэйл хаяг буруу.';
            } else if( ! email_exists( $email ) ) {
                $error = 'Энэ имэйл хаягаар бүртгүүлсэн хэрэглэгч байхгүй байна.';
            } else {

                $random_password = wp_generate_password( 12, false );
                $user = get_user_by( 'email', $email );

                $update_user = wp_update_user( array (
                        'ID' => $user->ID,
                        'user_pass' => $random_password
                    )
                );

                // if  update user return true then lets send user an email containing the new password
                if( $update_user ) {
                    $to = $email;
                    $subject = 'Шинэ нууц үг';
                    $sender = 'monsale.mn';
                    $message = '<b>Нууц үг:</b> '.$random_password;
                    $headers[] = 'MIME-Version: 1.0' . "\r\n";
                    $headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers[] = "X-Mailer: PHP \r\n";
                    $headers[] = 'From: '.$sender.' < '.$email.'>' . "\r\n";
                    $message .='
                         Цахим гэмт хэргээс урьдчилан сэргийлэхийн тулд та өөрийн нууц үг/ПИН кодыг бусдад бүү задруулаарай.
                         <br>Бидэнтэй холбоо барих:
                         <br>Вэб хуудас: www.monsale.mn 
                            <br>Харилцагчийн мэдээллийн төв: 99898989<br>
                        <p></p>
                        <p> Үйлчлүүлсэн танд баярлалаа.</p>
                        <p></p>
                        <p>  Энэхүү цахим шууданг автоматаар илгээсэн тул хариу и-мэйл хүлээн авах боломжгүй.</p>

                    ';
                    $mail = wp_mail( $to, $subject, $message, $headers );
                    if( $mail )
                        $success = 'Та шинэ нууц үгээ и-мэйл хаягаа шалгана уу.';



                } else {
                    $error = 'Таны акаунтыг шинэчлэхэд алдаа гарлаа.';
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
                    <div class="text-center mb-4">
                        <h1 style="color:#232f3e; font-weight: 400;">Нууц үг сэргээх</h1>
                    </div>
                    <form id="loginform" method="post">
                        <fieldset>
                            <?php
                                if( ! empty( $error ) )
                                    echo '<div class="message"><p class="error" style="font-size:13px; color: #d12333;"> '. $error .'</p></div>';

                                if( ! empty( $success ) )
                                    echo '<div class="error_login"><p class="success" style="font-size:13px; color: #28a745">'. $success .'</p></div>';
                            ?>
                            <p><label for="user_login">Нэвтрэх нэр эсвэл Имэйл хаяг:</label>
                                <?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
                                <input type="text" name="user_login" id="user_login" value="<?php echo $user_login; ?>" /></p>
                            <p>
                                <input type="hidden" name="action" value="reset" />
                                <input type="submit" value="Нууц үг сэргээх" class="button" id="submit" />
                            </p>
                        </fieldset>
                    </form>
                </div><!-- .entry-content -->
            </div> <!-- .content-wrap -->

        </div><!-- #content -->
    <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
