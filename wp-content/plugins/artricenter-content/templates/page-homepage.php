<?php
/**
 * Template Name: Artricenter Homepage
 *
 * @package Artricenter_Content
 */

get_header();
?>

<!-- Header from structure plugin -->
<?php do_action('artricenter_before_content'); ?>

<main class="artricenter-homepage">
    <?php while (have_posts()) : the_post(); ?>

        <!-- Artricenter Intro Section -->
        <section class="artricenter-homepage__intro">
            <div class="artricenter-container">
                <h1><?php the_title(); ?></h1>
                <div class="artricenter-homepage__content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>

        <!-- Nuestra Historia Section -->
        <section id="nuestra-historia" class="artricenter-homepage__history">
            <div class="artricenter-container">
                <h2>Nuestra Historia</h2>
                <!-- Content from page editor or hardcoded -->
                <p>History content placeholder</p>
            </div>
        </section>

        <!-- Nuestros Médicos Section -->
        <section id="nuestros-medicos" class="artricenter-homepage__doctors">
            <div class="artricenter-container">
                <h2>Nuestros Médicos</h2>
                <?php echo do_shortcode('[artricenter_doctores_grid]'); ?>
            </div>
        </section>

        <!-- Misión/Visión/Valores Section -->
        <section id="mision-vision-valores" class="artricenter-homepage__mission">
            <div class="artricenter-container">
                <h2>Misión, Visión y Valores</h2>
                <?php echo do_shortcode('[artricenter_mission_cards]'); ?>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<!-- Footer from structure plugin -->
<?php do_action('artricenter_after_content'); ?>

<?php get_footer(); ?>
