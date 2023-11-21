<?php

function custom_product_block_init() {
    register_block_type(
        'custom-product-block/block',
        array(
            'editor_script' => 'custom-product-block',
            'render_callback' => 'custom_product_block_render_frontend',
        )
    );
}
add_action('init', 'custom_product_block_init');

function custom_product_block_enqueue() {
    wp_enqueue_script(
        'custom-product-block',
        get_template_directory_uri() . '/index.js',
        array('wp-blocks', 'wp-components', 'wp-editor'),
        true
    );

    wp_enqueue_style(
        'custom-product-block-styles',
        get_template_directory_uri() . '/styles.css',
        array(),
        '1.0'
    );
}
add_action('enqueue_block_editor_assets', 'custom_product_block_enqueue');

function custom_product_block_render_frontend($attributes) {
    ob_start();
    custom_product_block_render($attributes);
    return ob_get_clean();
}

function custom_product_block_render($attributes) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'meta_query'     => array(
            array(
                'key'     => '_wc_pre_orders_enabled',
                'value'   => 'yes',
                'compare' => '=',
            ),
        ),
    );

    $products = new WP_Query($args);

    if ($products->have_posts()) {
        ?>
        <div class="custom-product-block">
            <h2><?php echo esc_html($attributes['heading']); ?></h2>
            <div class="products">
                <?php
                while ($products->have_posts()) {
                    $products->the_post();
                    ?>
                    <div class="product">
                        <?php
                        echo '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . esc_attr(get_the_title()) . '">';
                        echo '<h3>' . esc_html(get_the_title()) . '</h3>';
                        echo '<p>' . wc_price(get_post_meta(get_the_ID(), '_price', true)) . '</p>';
                        echo '<p>' . esc_html(get_post_meta(get_the_ID(), 'custom_field_name', true)) . '</p>';
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    }
}

register_block_type('custom-product-block/block', array(
    'render_callback' => 'custom_product_block_render_frontend',
    'editor_script'   => 'custom-product-block',
));
