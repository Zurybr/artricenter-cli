<?php
/**
 * Specialties List Shortcode Template
 *
 * @package Artricenter_Content
 */

// $query is available from shortcode handler
?>

<div class="artricenter-especialidades-list">
    <?php while ($query->have_posts()) : $query->the_post(); ?>
        <article class="artricenter-especialidades-list__item">
            <?php if (has_post_thumbnail()) : ?>
                <div class="artricenter-especialidades-list__icon">
                    <?php the_post_thumbnail('thumbnail'); ?>
                </div>
            <?php endif; ?>

            <div class="artricenter-especialidades-list__content">
                <h3 class="artricenter-especialidades-list__name">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h3>

                <?php if (get_the_excerpt()) : ?>
                    <p class="artricenter-especialidades-list__description">
                        <?php echo get_the_excerpt(); ?>
                    </p>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
</div>
