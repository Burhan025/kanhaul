<!-- footer top -->
<div id="footer_top">
	<div class="wrap">
				<div itemscope itemtype="http://schema.org/LocalBusiness" class="module contact">
			<h3>Contact Info</h3>
            <p><strong itemprop="name">Kan-Haul</strong></p>

<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<span itemprop="streetAddress"><p>11910 Greenville Ave</p>
<p>Suite 610</p></span>
<p>
<span itemprop="addressLocality">Dallas</span>, 
<span itemprop="addressRegion">TX</span> 
<span itemprop="postalCode">75243</span></p></span>
<p><strong id="internal-source-marker_0.6029303360264748"></strong>Ph 
<span itemprop="telephone"><a href="tel:8009599501" onClick="ga('send', 'event', { eventCategory: 'Click to Call', eventAction: 'Clicked Phone Number', eventLabel: 'Footer 800 Number'});">(800) 959-9501</a></span></p><p>Fax (972) 994-0803</p><p><a title="Contact Us" href="http://kanhaul.com/contact-us/">Contact Us»</a></p>
		</div><!-- end module -->
	
		<div class="module quicklinks">
			<h3>Quick Links</h3>
			<?php the_field('quick_links', 63); ?>
		</div><!-- end module -->
        
        <div class="module news">
            <h3>Latest News</h3>
            <?php $my_query = new WP_Query('showposts=1&category_name='); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
            <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            <p style="margin:5px 0px;"><?php the_date(); ?></p>
       	    <p><?php the_excerpt_custom_length(250); ?></p>
			<?php endwhile; wp_reset_query();?>

		</div><!-- end module -->
	
		<div class="module testimonials">
            <h3>Testimonials</h3>
            <?php
                $query = new WP_Query(array(
                    'post_type' 	 => 'testimonial', 
                    'posts_per_page' => '1',
                    'orderby'		 => 'rand'
                ));
                while($query->have_posts()) : $query->the_post(); ?>
                <p><?php the_excerpt_custom_length(250); ?></p>
                <?php endwhile; wp_reset_query(); ?>
                <p><a href="<?php bloginfo('url'); ?>/about-us/testimonials/">View All Testimonials&raquo;</a></p>
		</div><!-- end module -->
        
<!-- footer -->
<div id="footer">
		<p class="left">Copyright &copy; <?=date('Y')?> KAN-HAUL <span>|</span>  All Rights Reserved  <span>|</span> <a href="<?php bloginfo('url'); ?>/sitemap/">Sitemap</a>  <!--<span>|</span> <a rel="nofollow" href="<?php bloginfo('url'); ?>/wp/wp-admin/">Admin</a>--></p>
        <p class="right"> <a href="https://www.thriveagency.com" target="_blank">Web Design</a> By <a href="https://www.thriveagency.com" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/thrive-logo-white-small.png" alt="Thrive Internet Marketing Agency" /></a></p>
</div> <!-- end footer -->

        
        
	</div><!-- end wrap -->
</div> <!-- end footer top -->
</div> <!--main-container -->

<?php wp_footer(); ?>

</body>
</html>