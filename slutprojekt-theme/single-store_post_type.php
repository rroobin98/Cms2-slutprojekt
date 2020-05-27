<?php
  get_header();
  include('google-maps.php');
?>

<div class="container">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

      <h1><?php the_title(); ?></h1>
      <h3><?php the_field('address') ?></h3>

      <?php if( have_rows('oppetider') ): ?>
        <?php while ( have_rows('oppetider') ) : the_row(); ?>
          <ul>
            <li><?php the_sub_field('dag') ?>: <?php the_sub_field('tid_fran') ?> - <?php the_sub_field('tid_till') ?></li>
          </ul>
        <?php endwhile;
      endif; ?>

      <?php

        $location = get_field('karta');

        if( !empty($location) ):
        ?>
        <div class="acf-map">
        	<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
        </div>
      <?php endif; ?>

    <?php endwhile; ?>
    <?php else : ?>
      <p>Inga poster hittades.</p>
  <?php endif; ?>

</div>

<?php get_footer(); ?>
