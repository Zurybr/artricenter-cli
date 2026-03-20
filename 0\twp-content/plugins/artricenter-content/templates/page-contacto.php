<?php
/**
 * Template Name: Artricenter Contacto
 *
 * @package Artricenter_Content
 */

get_header();
?>

<?php do_action('artricenter_before_content'); ?>

<main class="artricenter-contacto">
    <?php while (have_posts()) : the_post(); ?>
        <div class="artricenter-container">
            <h1><?php the_title(); ?></h1>

            <!-- Contact page intro from editor -->
            <div class="artricenter-contacto__intro">
                <?php the_content(); ?>
            </div>

            <!-- Contact form section (will be implemented in Phase 3) -->
            <section class="artricenter-contacto__form">
                <h2>Envíanos un Mensaje</h2>
                <!-- Form will be added in Phase 3 -->
                <div class="artricenter-contacto__form-placeholder">
                    <?php echo esc_html__('Contact form will be displayed here', 'artricenter-content'); ?>
                </div>
            </section>

            <!-- Clinic information section -->
            <section class="artricenter-contacto__info">
                <h2>Información de Contacto</h2>
                <!-- Clinic contact details -->
                <p>Contact information will be displayed here</p>
            </section>
        </div>
    <?php endwhile; ?>
</main>

<?php do_action('artricenter_after_content'); ?>

<?php get_footer(); ?>
