<?php
/**
 * Template Name: Artricenter Especialidades
 *
 * @package Artricenter_Content
 */

get_header();
?>

<?php do_action('artricenter_before_content'); ?>

<main class="artricenter-especialidades">
    <?php while (have_posts()) : the_post(); ?>
        <div class="artricenter-container">
            <h1><?php the_title(); ?></h1>

            <!-- Page intro content from editor -->
            <div class="artricenter-especialidades__intro">
                <?php the_content(); ?>
            </div>

            <!-- Specialty listings -->
            <section class="artricenter-especialidades__list">
                <?php echo do_shortcode('[especialidades_list]'); ?>
            </section>
        </div>
    <?php endwhile; ?>
</main>

<?php do_action('artricenter_after_content'); ?>

<?php get_footer(); ?>
