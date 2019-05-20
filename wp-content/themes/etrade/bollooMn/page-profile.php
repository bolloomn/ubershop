<?php /* Template Name: my profile */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$user = wp_get_current_user();

$error = [];

$pid = 0;
if (isset($_GET['pid'])) {
    $pid = username_exists($_GET['pid']);
    if (!$pid) {
        wp_redirect('register');
    }
}

if (isset($_POST['register'])) {


    if (isset($_POST['password']) and $_POST['password'] != '') {
        if ($_POST['password'] != $_POST['c_password']) {
            $error[] = 'Нууц үг таарахгүй байна.';
        } else {
            wp_set_password($_POST['password'], $user->ID);
        }
    }

    if (count($error) == 0) {
        update_user_meta($user->ID, 'first_name', $_POST['first_name']);
        update_user_meta($user->ID, 'last_name', $_POST['last_name']);
        update_user_meta($user->ID, 't_phone', $_POST['t_phone']);
        update_user_meta($user->ID, 't_male', $_POST['t_male']);
        update_user_meta($user->ID, 't_hot', $_POST['t_hot']);
        update_user_meta($user->ID, 't_sum', $_POST['t_sum']);
        update_user_meta($user->ID, 't_gudamj', $_POST['t_gudamj']);
        update_user_meta($user->ID, 't_bair', $_POST['t_bair']);
        update_user_meta($user->ID, 't_toot', $_POST['t_toot']);
        update_user_meta($user->ID, 't_address', $_POST['t_address']);
        update_user_meta($user->ID, 't_bank_name', $_POST['t_bank_name']);
        update_user_meta($user->ID, 't_bank_account', $_POST['t_bank_account']);
        update_user_meta($user->ID, 't_bank_number', $_POST['t_bank_number']);
        update_user_meta($user->ID, 'instagram', $_POST['instagram']);
        update_user_meta($user->ID, 'facebook', $_POST['facebook']);
    }
}

?>

<?php get_header(); ?>


<div id="content" class="clearfix">
    <div class="content-wrap">
        <div class="entry-content">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 ">
                    <div class="text-center mb-4">
                        <h1 style="color:#232f3e; font-weight: 400;">Миний профайл</h1>
                    </div>
                    <div class="bg-white p-4 mb-4">
                        <div class="heading-title">Найзаа урих холбоос</div>
                        <p><input type="url" readonly class="form-control"value="<?php echo home_url('register?pid='.str_replace (' ', '%20', $user->user_login));?>"></p>
                    </div>
                    <?php
                    foreach ($error as $e) {
                        echo '<div  class="alert alert-danger mb-4" role="alert">' . $e . '</div>';
                    }
                    if (count($error) == 0 and isset($_POST['register'])) {
                        echo '<div  class="alert alert-success mb-4" >Амжилттай хадгалагдлаа</div>';
                    }
                    ?>

                    <form class="bg-white p-4 mb-4" method="post">

                        <div class="row">
                            <?php
                            $parent = get_user_meta($user->ID, 't_parent', true);
                            if ($parent == '' && $parent != 0) {
                                $parent_user = get_userdata($parent);
                                ?>
                                <div class="col-md-12">
                                    <div class="heading-title">Урьсан хэрэглэгч</div>
                                    <p><input type="text" readonly class="form-control"
                                              value="<?php echo $parent_user->user_login; ?>"></p>
                                </div>
                            <?php } ?>
                            <div class="col-md-12">
                                <div class="heading-title">Ерөнхий мэдээлэл</div>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_login">Нэвтрэх нэр</label>
                                    <input type="text" readonly class="form-control"
                                           value="<?php echo $user->user_login; ?>"
                                           required>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="user_email">Имэйл</label>
                                    <input type="email" readonly class="form-control"
                                           value="<?php echo $user->user_email; ?>"
                                           required>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="last_name">Овог</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 'last_name', true); ?>"
                                        minlength="2"   required>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="first_name">Нэр</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 'first_name', true); ?>"
                                           minlength="2" required>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <label for="t_phone">Утас</label>
                                    <input type="number" name="t_phone" id="t_phone" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_phone', true); ?>"
                                           required>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p>
                                    <label for="t_male">Хүйс</label>
                                    <select name="t_male" id="t_male" class="form-control" required>
                                        <option value="эрэгтэй" <?php if (get_user_meta($user->ID, 't_male', true) == 'эрэгтэй') {
                                            echo 'selected';
                                        } ?>>эрэгтэй
                                        </option>
                                        <option value="эмэгтэй" <?php if (get_user_meta($user->ID, 't_male', true) == 'эмэгтэй') {
                                            echo 'selected';
                                        } ?>>эмэгтэй
                                        </option>
                                    </select>
                                </p>
                            </div>

                            <div class="col-md-12">
                                <div class="heading-title mt-4">Хаяг байршил</div>
                            </div>

                            <div class="col-md-6">
                                <p>
                                    <label for="t_hot">Хот, Аймаг</label>
                                    <input type="text" name="t_hot" id="t_hot" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_hot', true); ?>" required>
                                </p>
                                <p>
                                    <label for="t_sum">Сум, Дүүрэг</label>
                                    <input type="text" name="t_sum" id="t_sum" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_sum', true); ?>" required>
                                </p>
                                <p>
                                    <label for="t_gudamj">Гудамж талбай</label>
                                    <input type="text" name="t_gudamj" id="t_gudamj" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_gudamj', true); ?>"
                                    >
                                </p>
                                <p>
                                    <label for="t_bair">Байшин, байр</label>
                                    <input type="text" name="t_bair" id="t_bair" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_bair', true); ?>" required>
                                </p>
                            </div>


                            <div class="col-md-6">

                                <p>
                                    <label for="t_toot">Тоот</label>
                                    <input type="text" name="t_toot" id="t_toot" class="form-control"
                                           value="<?php echo get_user_meta($user->ID, 't_toot', true); ?>" required>
                                </p>
                                <p>
                                    <label for="t_address">Дэлгэрэнгүй Хаяг</label><br>
                                    <textarea class="form-control" name="t_address" id="t_address"
                                              style=" height: 193px;"
                                              required><?php echo get_user_meta($user->ID, 't_address', true); ?></textarea>
                                </p>

                            </div>

                            <div class="col-md-12">
                                <div class="heading-title mt-4">Дансны мэдээлэл</div>
                            </div>

                            <div class="col-md-4">
                                <label for="t_bank_name">Банк</label>
                                <input type="text"  name="t_bank_name" id="t_bank_name" class="form-control"
                                       value="<?php echo get_user_meta($user->ID, 't_bank_name', true); ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="t_bank_account">Данс эзэмшигч</label>
                                <input type="text"  id="t_bank_account"  name="t_bank_account" class="form-control"
                                       value="<?php echo get_user_meta($user->ID, 't_bank_account', true); ?>">
                            </div>

                            <div class="col-md-4">
                                <label for="t_bank_number">Дансны дугаар</label>
                                <input type="number"  id="t_bank_number"  name="t_bank_number" class="form-control"
                                       value="<?php echo get_user_meta($user->ID, 't_bank_number', true); ?>">
                            </div>
                            <div class="col-md-12">
                                <div class="heading-title mt-4">Сошиал</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password">Facebook (вэб холбоос)</label>
                                <input type="url" name="facebook" id="facebook" class="form-control" value="<?php echo get_user_meta($user->ID, 'facebook', true); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="c_password">Instagram (вэб холбоос)</label>
                                <input type="url" name="instagram" id="instagram" class="form-control" value="<?php echo get_user_meta($user->ID, 'instagram', true); ?>">
                            </div>

                            <div class="col-md-12">
                                <div class="heading-title mt-4">Нууц үг</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password">Шинэ нууц үг</label>
                                <input type="password" name="password" id="password" class="form-control" value="" minlength="6"
                                >
                            </div>
                            <div class="col-md-6">
                                <label for="c_password">Шинэ нууц үг баталгаажуулах</label>
                                <input type="password" name="c_password" id="c_password" class="form-control" value="" minlength="6"
                                >

                            </div>
                            <div class="col-md-6 offset-md-3">
                                <input type="submit" name="register" class="form-control mt-4" value="Хадгалах">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div><!-- .entry-content -->
    </div> <!-- .content-wrap -->

</div><!-- #content -->
<?php get_footer(); ?>