<?php
/**
 * Mission/Vision/Values Cards Shortcode Template
 *
 * @package Artricenter_Content
 */

// Hardcoded mission, vision, values for now
// Future: Make this editable via WordPress options page
$cards = [
    [
        'title' => 'Misión',
        'content' => 'Brindar atención médica especializada en reumatología con calidad humana y tecnología de vanguardia.',
        'icon' => 'heart', // CSS class or emoji
        'color' => 'blue',
    ],
    [
        'title' => 'Visión',
        'content' => 'Ser el referente nacional en el tratamiento de enfermedades reumáticas, comprometidos con la investigación y la innovación.',
        'icon' => 'eye',
        'color' => 'green',
    ],
    [
        'title' => 'Valores',
        'content' => 'Compromiso, excelencia, empatía, integridad y trabajo en equipo son los pilares que guían nuestra práctica médica.',
        'icon' => 'star',
        'color' => 'orange',
    ],
];
?>

<div class="artricenter-mission-cards">
    <?php foreach ($cards as $card) : ?>
        <article class="artricenter-mission-cards__card artricenter-mission-cards__card--<?php echo esc_attr($card['color']); ?>">
            <?php if ($card['icon']) : ?>
                <div class="artricenter-mission-cards__icon">
                    <?php echo esc_html($card['icon']); ?>
                </div>
            <?php endif; ?>

            <h3 class="artricenter-mission-cards__title">
                <?php echo esc_html($card['title']); ?>
            </h3>

            <p class="artricenter-mission-cards__content">
                <?php echo esc_html($card['content']); ?>
            </p>
        </article>
    <?php endforeach; ?>
</div>
