   <?php  
      if ( !class_exists( 'CSF' ) ) {
         get_template_part( 'template-parts/footer/footer', 'style-1' );
      } else {
         $footer_layout = exhibz_option("footer_style", "style-1");
         get_template_part( 'template-parts/footer/footer', $footer_layout );
      }
   ?>
   <?php wp_footer(); ?>
   </body>
</html>