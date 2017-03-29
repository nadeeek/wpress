<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Resto
 */

?>

	</div><!-- #content -->

	<footer>
		<div class="wrapper">
			<ul>
				<li>New york Resturant</li>
				<li>3926 Anmoore Road</li>
				<li>New York, NY 10014</li>
				<li>718-749-1714</li>
			</ul>
			<ul>
				<li>New york Resturant</li>
				<li>3926 Anmoore Road</li>
				<li>New York, NY 10014</li>
				<li>718-749-1714</li>
			</ul>
			<ul>
				<li><a href="">Blog</a></li>
				<li><a href="">Careers</a></li>
				<li><a href="">Privacy Policy</a></li>
				<li><a href="">Contact</a></li>
			</ul>
			<ul>
				<li><img src="<?php echo(get_template_directory_uri());?>/images/logu-white.jpg" alt="logu"></li>
				<li>&copy; All Right Reserved</li>
			</ul>
			<?php dynamic_sidebar( 'footer' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
