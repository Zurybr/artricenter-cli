<?php
/**
 * Template Name: Artricenter Tratamiento Médico Integral
 *
 * @package Artricenter_Content
 */

get_header();
?>

<?php do_action('artricenter_before_content'); ?>

<main class="artricenter-tratamiento">
    <?php while (have_posts()) : the_post(); ?>
        <div class="artricenter-container">
            <h1><?php the_title(); ?></h1>

            <!-- PAIPER program description from editor -->
            <div class="artricenter-tratamiento__content">
                <?php the_content(); ?>
            </div>

            <!-- Treatment steps section -->
            <section class="artricenter-tratamiento__steps">
                <h2>Etapas del Tratamiento</h2>
                <!-- Content from editor or structured list -->
                <p>PASIPER program steps will be described here</p>
            </section>
        </div>
    <?php endwhile; ?>
</main>

<?php do_action('artricenter_after_content'); ?>

<?php get_footer(); ?>
