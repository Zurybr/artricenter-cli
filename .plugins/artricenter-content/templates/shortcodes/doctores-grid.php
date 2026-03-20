<?php
/**
 * Doctors Grid Shortcode Template
 *
 * @package Artricenter_Content
 */

// $query is available from shortcode handler
?>

<div class="artricenter-doctores-grid">
    <?php while ($query->have_posts()) : $query->the_post(); ?>
        <?php
        $doctor_id = get_the_ID();
        $specialty = get_post_meta($doctor_id, '_doctor_specialty', true);
        $location = get_post_meta($doctor_id, '_doctor_location', true);
        ?>

        <article class="artricenter-doctores-grid__card">
            <?php if (has_post_thumbnail()) : ?>
                <div class="artricenter-doctores-grid__photo">
                    <?php the_post_thumbnail('medium'); ?>
                </div>
            <?php endif; ?>

            <div class="artricenter-doctores-grid__info">
                <h3 class="artricenter-doctores-grid__name">
                    <?php the_title(); ?>
                </h3>

                <?php if ($specialty) : ?>
                    <p class="artricenter-doctores-grid__specialty">
                        <?php echo esc_html($specialty); ?>
                    </p>
                <?php endif; ?>

                <?php if ($location) : ?>
                    <p class="artricenter-doctores-grid__location">
                        <?php echo esc_html($location); ?>
                    </p>
                <?php endif; ?>

                <a href="<?php the_permalink(); ?>" class="artricenter-doctores-grid__link">
                    <?php echo esc_html__('Ver perfil', 'artricenter-content'); ?>
                </a>
            </div>
        </article>

    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
</div>
