<form method="get" id="search" action="<?php bloginfo('url'); ?>">
  <fieldset>
    <p><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Search Blog','mozblog'); ?>"> <button type="submit">Search</button></p>
  </fieldset>
</form>
