<?php
// Start the loop
while (have_posts()) :
    the_post();
    ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div><?php the_excerpt(); ?></div>
    <?php
endwhile;
?>
