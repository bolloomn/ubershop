<?php /* Template Name: register */
session_start();
if(is_user_logged_in()) {wp_redirect(home_url()); }

$captcha_instance = new ReallySimpleCaptcha();
$captcha_instance->bg = array(0, 0, 0);
$word = $captcha_instance->generate_random_word();
$error=[];

$pid=0;
if(isset($_GET['pid'])){
    $pid = username_exists($_GET['pid']);
    if(!$pid){ wp_redirect('register'); }
}

if(!isset($_POST['register'])){
    $_SESSION['cap'] = $word;
} else {
    if($_SESSION['cap']!=$_POST['captcha']){
        $_SESSION['cap'] = $word;
        $error[]='Баталгаажуулах код буруу байна.';
    }

    if($_POST['password']!=$_POST['c_password']){
        $error[]='Нууц үг таарахгүй байна.';
    }


    $user_id = username_exists( $_POST['user_login'] );

    if($user_id){
        $error[]='Нэвтрэх нэр бүртэгдсэн байна';
    }

    if(email_exists($_POST['user_email']) == true){
        $error[]='Имэйл хаяг бүртэгдсэн байна';
    }

    if (count($error)==0) {
        $user_id = wp_create_user( $_POST['user_login'], $_POST['password'], $_POST['user_email']);
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
        update_user_meta($user_id, 'last_name', $_POST['last_name']);
        update_user_meta($user_id, 't_phone', $_POST['t_phone']);
        update_user_meta($user_id, 't_male', $_POST['t_male']);
        update_user_meta($user_id, 't_hot', $_POST['t_hot']);
        update_user_meta($user_id, 't_sum', $_POST['t_sum']);
        update_user_meta($user_id, 't_gudamj', $_POST['t_gudamj']);
        update_user_meta($user_id, 't_bair', $_POST['t_bair']);
        update_user_meta($user_id, 't_toot', $_POST['t_toot']);
        update_user_meta($user_id, 't_address', $_POST['t_address']);
        update_user_meta($user_id, 't_parent', $_POST['t_parent']);
        wp_redirect('login?r=1');
    }
}

?>

<?php get_header(); ?>


        <div id="content" class="clearfix">

            <div class="content-wrap">
                <div class="entry-content">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 ">

                            <?php foreach ($error as $e){
                                echo '<div  class="alert alert-danger mb-4" role="alert">'.$e.'</div>';
                             } ?>

                            <div class="text-center mb-4">
                                <h1 style="color:#232f3e; font-weight: 400;">Бүртгүүлэх</h1>
                            </div>

                        <form class="bg-white p-4 mb-4"  method="post">

                            <div class="row">
                                <input type="hidden" name="t_parent" value="<?=$pid;?>">
                                <?php if($pid!=0) { ?>
                                    <div class="col-md-12">
                                        <div class="heading-title">Урьсан хэрэглэгч</div>
                                        <p>
                                            <input  type="text" readonly class="form-control bg-white text-black-50" value="<?=$_GET['pid'];?>">
                                        </p>
                                    </div>
                                <?php } ?>
                                <div class="col-md-12">
                                    <div class="heading-title">Ерөнхий мэдээлэл</div>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <label for="user_login">Нэвтрэх нэр</label>
                                        <input type="text" name="user_login" id="user_login" class="form-control" value="<?php echo checkPost('user_login'); ?>"
                                               required>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <label for="user_email">Имэйл</label>
                                        <input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo checkPost('user_email'); ?>"
                                               required>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <label for="last_name">Овог</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo checkPost('last_name'); ?>"
                                               required>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <label for="first_name">Нэр</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo checkPost('first_name'); ?>"
                                               required>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <label for="t_phone">Утас</label>
                                        <input type="text" name="t_phone" id="t_phone" class="form-control" value="<?php echo checkPost('t_phone'); ?>"
                                               required>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <p>
                                        <label for="t_male">Хүйс</label>
                                        <select name="t_male" id="t_male" class="form-control" required>
                                            <option value="эрэгтэй" <?php if(checkPost('t_phone')=='эрэгтэй'){echo 'selected';} ?>>эрэгтэй</option>
                                            <option value="эмэгтэй" <?php if(checkPost('t_phone')=='эмэгтэй'){echo 'selected';} ?>>эмэгтэй</option>
                                        </select>
                                    </p>
                                </div>

                                <div class="col-md-12">
                                    <div class="heading-title mt-4">Хаяг байршил</div>
                                </div>

                                <div class="col-md-6">
                                    <p>
                                        <label for="t_hot">Хот, Аймаг</label>
                                        <input type="text" name="t_hot" id="t_hot" class="form-control" value="<?php echo checkPost('t_hot'); ?>" required>
                                    </p>
                                    <p>
                                        <label for="t_sum">Сум, Дүүрэг</label>
                                        <input type="text" name="t_sum" id="t_sum" class="form-control" value="<?php echo checkPost('t_sum'); ?>" required>
                                    </p>
                                    <p>
                                        <label for="t_gudamj">Гудамж талбай</label>
                                        <input type="text" name="t_gudamj" id="t_gudamj" class="form-control" value="<?php echo checkPost('t_gudamj'); ?>"
                                               >
                                    </p>
                                    <p>
                                        <label for="t_bair">Байшин, байр</label>
                                        <input type="text" name="t_bair" id="t_bair" class="form-control" value="<?php echo checkPost('t_bair'); ?>" required>
                                    </p>
                                </div>


                                <div class="col-md-6">

                                    <p>
                                        <label for="t_toot">Тоот</label>
                                        <input type="text" name="t_toot" id="t_toot" class="form-control" value="<?php echo checkPost('t_toot'); ?>" required>
                                    </p>
                                    <p>
                                        <label for="t_address">Дэлгэрэнгүй Хаяг</label><br>
                                        <textarea class="form-control" name="t_address" id="t_address"
                                                  style=" height: 193px;" required><?php echo checkPost('t_address'); ?></textarea>
                                    </p>

                                </div>

                                <div class="col-md-12">
                                    <div class="heading-title mt-4">Нууц үг</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password">Нууц үг</label>
                                    <input type="password" name="password" id="password" class="form-control" value=""
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="c_password">Нууц үг баталгаажуулах</label>
                                    <input type="password" name="c_password" id="c_password" class="form-control" value=""
                                           required>

                                </div>
                                <div class="col-md-12">
                                    <div class="heading-title mt-4">Баталгаажуулах хэсэг</div>
                                </div>
                                <div class="col-md-6 offset-md-3 text-center">
                                    <label for="captcha">харагдаж кодыг оруулна уу: <b><?= $word; ?></b></label>
                                    <input type="text" name="captcha" id="captcha" class="form-control" value="" required>
                                </div>
                                <div class="col-md-6 offset-md-3">
                                    <input type="submit" name="register" class="form-control mt-4" value="Бүртгүүлэх">
                                </div>
                            </div>

                        </form>
                        </div>
                    </div>
                </div><!-- .entry-content -->
            </div> <!-- .content-wrap -->

        </div><!-- #content -->
<?php get_footer(); ?>

