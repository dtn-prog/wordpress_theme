<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( '404 - Page Not Found', 'your-theme-textdomain' ); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p><?php esc_html_e( 'The page you are looking for does not exist. Please try a different link.', 'your-theme-textdomain' ); ?></p>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>