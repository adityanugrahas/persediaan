/* ---------------------------------------------------------------------------
 * Premium UI/UX Interactions v2.0
 * --------------------------------------------------------------------------- */

(function($) {
    'use strict';

    $(function() {
        // 1. Smooth Entry Animations for Cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    cardObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        $('.card').each(function() {
            this.style.opacity = '0';
            this.style.transform = 'translateY(20px)';
            this.style.transition = 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
            cardObserver.observe(this);
        });

        // 2. Parallax Effect for Background Blobs (Mouse Move)
        $(document).on('mousemove', function(e) {
            const amountX = (e.pageX - $(window).width() / 2) / 80;
            const amountY = (e.pageY - $(window).height() / 2) / 80;

            $('.blob-1').css('transform', `translate(${amountX}px, ${amountY}px)`);
            $('.blob-2').css('transform', `translate(${amountX * -1.5}px, ${amountY * -1.5}px)`);
            $('.blob-3').css('transform', `translate(${amountX * 0.8}px, ${amountY * -0.8}px)`);
        });

        // 3. Button Micro-interactions
        $('.btn').on('mouseenter', function() {
            $(this).find('i').addClass('animated infinite pulse slower');
        }).on('mouseleave', function() {
            $(this).find('i').removeClass('animated infinite pulse slower');
        });

        // 4. Sidebar Search Highlight
        $('.sidebar-left .nav-form .form-control').on('focus', function() {
            $(this).closest('.nav-form').addClass('active');
        }).on('blur', function() {
            $(this).closest('.nav-form').removeClass('active');
        });

        // 5. Standalone AJAX mode detection (Self-heal)
        if ($('html:not(.fixed)').length > 0 && $('.body').length === 0) {
            $('body').addClass('standalone-mode');
            $('<div class="standalone-badge text-center" style="position:fixed; top:0; left:0; right:0; background:var(--primary); color:#fff; padding:5px; z-index:10000; font-size:10px; font-weight:800; letter-spacing:0.1em;">FRAGEMENT VIEWPORT ENABLED</div>').prependTo('body');
            
            // Inject theme for standalone view if missing
            if ($('link[href*="custom.css"]').length === 0) {
                 $('<link rel="stylesheet" href="css/custom.css">').appendTo('head');
                 $('<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css">').appendTo('head');
                 $('<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css">').appendTo('head');
                 $('body').css({
                     'background': 'radial-gradient(circle at 0% 0%, #1e1b4b 0%, #0f172a 50%, #020617 100%)',
                     'min-height': '100vh',
                     'display': 'flex',
                     'align-items': 'center',
                     'justify-content': 'center'
                 });
            }
        }
    });

})(jQuery);