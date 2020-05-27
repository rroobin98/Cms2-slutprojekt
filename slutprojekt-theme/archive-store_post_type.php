<?php
  get_header();
  include('google-maps.php');
?>

<div class="container">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

      <h3><a href="<?php the_permalink() ?>"><?php the_field('address') ?></a></h3>

      <?php if( have_rows('oppetider') ): ?>
        <?php while ( have_rows('oppetider') ) : the_row(); ?>
          <ul>
            <li><?php the_sub_field('dag') ?>: <?php the_sub_field('tid_fran') ?> - <?php the_sub_field('tid_till') ?></li>
          </ul>
        <?php endwhile;
      endif; ?>

    <?php endwhile; ?>
    <?php else : ?>
      <p>Inga poster hittades.</p>
  <?php endif; ?>

</div>

<?php get_footer(); ?>
