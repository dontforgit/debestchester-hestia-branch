<style>
    /* Hack to hide student name before login */
    h1.hestia-title{
        visibility:hidden;
    }
    h1.hestia-title:after{
        visibility:visible;
        display:block;
        content: "Protected: Login to view this page.";
    }
</style>
<article id="post-<?php the_ID(); ?>" class="section section-text">
    <div class="row">
        <div class="">
            <?php do_action( 'hestia_before_page_content' ); ?>
            <h2>You must be logged in to view this page.</h2>
            <?php wp_login_form(); ?>
        </div>
    </div>
</article>