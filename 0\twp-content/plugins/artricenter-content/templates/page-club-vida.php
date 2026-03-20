<?php
/**
 * Template Name: Artricenter Club de Vida y Salud
 *
 * @package Artricenter_Content
 */

get_header();
?>

<?php do_action('artricenter_before_content'); ?>

<main class="artricenter-club-vida">
    <?php while (have_posts()) : the_post(); ?>
        <div class="artricenter-container">
            <h1><?php the_title(); ?></h1>

            <!-- Membership program information from editor -->
            <div class="artricenter-club-vida__content">
                <?php the_content(); ?>
            </div>

            <!-- Benefits/features section -->
            <section class="artricenter-club-vida__benefits">
                <h2>Beneficios del Club</h2>
                <!-- Content from editor -->
                <p>Membership benefits will be listed here</p>
            </section>
        </div>
    <?php endwhile; ?>
</main>

<?php do_action('artricenter_after_content'); ?>

<?php get_footer(); ?>
