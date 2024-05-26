<?php

if ($query->have_posts()) : ?>
    <div class="search-results">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="search-result-item">
                <?php if ($elementor_template_id) : ?>
                    <?php echo do_shortcode('[elementor-template id="' . esc_attr($elementor_template_id) . '"]'); ?>
                <?php else : ?>
                    <h2><?php the_title(); ?></h2>
                    <div class="excerpt"><?php the_excerpt(); ?></div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
    <?php wp_reset_postdata(); ?>
<?php else : ?>
    <p>No se encontraron resultados.</p>
<?php endif; ?>
