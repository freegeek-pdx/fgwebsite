<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><label for="s"><?php _e( 'Search this site' ) ?></label><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
<input type="submit" id="searchsubmit" value="Search" />
</div>
</form>
