<?php /* Template Name: register */

$captcha_instance = new ReallySimpleCaptcha();
$captcha_instance->bg = array(0, 0, 0);
$word = $captcha_instance->generate_random_word();
$error=[];




?>

<?php get_header(); ?>
<?php if (have_posts()) : ?>
    <?php /* Start the Loop */ ?>
    <?php while (have_posts()) : the_post(); ?>


        <div id="content" class="clearfix">

            <div class="content-wrap">
                <div class="entry-content">
                    <form id="loginform" style="max-width: 800px !important;" method="post">
                        <div class="row">
                            <?php

                            if(!isset($_POST['register'])){
                                $_SESSION['cap'] = $word;
                            } else {
                                echo 'ses: '.$_SESSION['cap'].'<br>';
                                echo 'post: '.$_POST['captcha'].'<br>';
                                if($_SESSION['cap']!=$_POST['captcha']){
                                
                                    $_SESSION['cap'] = $word;
                                    $error[]='Баталгаажуулах код буруу байна.';
                                }
                            }
              ?>
                            <div class="col-md-12">
                                <div class="heading-title">Ерөнхий мэдээлэл</div>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Нэвтрэх нэр</label>
                                    <input type="text" name="user_login" id="user_login" class="form-control" value=""
                                           >
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Имэйл</label>
                                    <input type="email" name="user_email" id="user_email" class="form-control" value=""
                                           >
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Овог</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value=""
                                           >
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Нэр</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value=""
                                           >
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Утас</label>
                                    <input type="text" name="t_phone" id="t_phone" class="form-control" value=""
                                           >
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Хүйс</label>
                                    <select name="t_male" id="t_male" class="form-control">
                                        <option value="эрэгтэй">эрэгтэй</option>
                                        <option value="эмэгтэй">эмэгтэй</option>
                                    </select>
                                </p>
                            </div>

                            <div class="col-md-12">
                                <div class="heading-title mt-4">Хаяг байршил</div>
                            </div>

                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Хот, Аймаг</label>
                                    <input type="text" name="t_hot" id="t_hot" class="form-control" value="" >
                                </p>
                                <p>
                                    <label for="user_login">Сум, Дүүрэг</label>
                                    <input type="text" name="t_sum" id="t_sum" class="form-control" value="" >
                                </p>
                                <p>
                                    <label for="user_login">Гудамж талбай</label>
                                    <input type="text" name="t_gudamj" id="t_gudamj" class="form-control" value=""
                                           >
                                </p>
                                <p>
                                    <label for="user_login">Байшин, байр</label>
                                    <input type="text" name="t_bair" id="t_bair" class="form-control" value="" >
                                </p>
                            </div>


                            <div class="col-md-6">

                                <p>
                                    <label for="user_login">Тоот</label>
                                    <input type="text" name="t_toot" id="t_toot" class="form-control" value="" >
                                </p>
                                <p>
                                    <label for="user_login">Дэлгэрэнгүй Хаяг</label><br>
                                    <textarea class="form-control" name="t_address" id="t_address"
                                              style=" height: 193px;" ></textarea>
                                </p>

                            </div>

                            <div class="col-md-12">
                                <div class="heading-title mt-4">Нууц үг</div>
                            </div>

                            <div class="col-md-6">
                                <label for="user_login">Нууц үг</label>
                                <input type="password" name="password" id="password" class="form-control" value=""
                                       >
                            </div>
                            <div class="col-md-6">
                                <label for="user_login">Нууц үг баталгаажуулах</label>
                                <input type="password" name="cpassword" id="c_password" class="form-control" value=""
                                       >

                            </div>
                            <div class="col-md-12">
                                <div class="heading-title mt-4">Баталгаажуулах хэсэг</div>
                            </div>
                            <div class="col-md-4 offset-md-4 text-center">
                                <label for="user_login">харагдаж кодыг оруулна: <b><?= $word; ?></b></label>
                                <input type="text" name="captcha" class="form-control" value="" >
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <input type="submit" name="register" class="form-control mt-4" value="Бүртгүүлэх">
                            </div>
                        </div>

                    </form>
                </div><!-- .entry-content -->
            </div> <!-- .content-wrap -->

        </div><!-- #content -->
    <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>

