<?php  /* Template Name: faq */   get_header(); ?>

<?php if ( have_posts() ) : ?>


    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>



        <div id="content" class="clearfix">
            <article >
                <div class="content-wrap">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-content">
                        <div class="accordion " id="accordionExample">
                        <?php $i=1;  while(the_repeater_field('faq',get_the_ID())): ?>
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $i;?>">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
                                            <?php the_sub_field('q'); ?>
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse<?php echo $i;?>" class="collapse " aria-labelledby="heading<?php echo $i;?>" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <?php echo get_sub_field('a'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; endwhile; ?>
                        </div>
                    </div><!-- .entry-content -->

                </div> <!-- .content-wrap -->
            </article>

        </div><!-- #content -->

    <?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>