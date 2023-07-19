<?php
get_header();
// Start the loop
while (have_posts()) :
    the_post();
    ?>
    <h1><?php the_title(); ?></h1>

    <?php
    // Get custom field values as an array
    $data = get_post_meta(get_the_ID(), 'custom_field_name', true);

    // Display custom field values
    if (isset($data['description']) && !empty($data['description'])) {
        echo '<p>Description: ' . esc_html($data['description']) . '</p>';
    }

    if (isset($data['url']) && !empty($data['url'])) {
        echo '<p>URL: <a href="' . esc_url($data['url']) . '">' . esc_url($data['url']) . '</a></p>';
    }

    if (isset($data['gallery_images']) && is_array($data['gallery_images']) && !empty($data['gallery_images'])) {
        echo '<h2>Gallery Images:</h2>';
        echo '<ul>';
        foreach ($data['gallery_images'] as $image) {
            echo '<li><img src="' . wp_get_attachment_image_url($image) . '" alt=""></li>';
        }
        echo '</ul>';
    }
    ?>

    <?php
endwhile;
get_footer();
?>
