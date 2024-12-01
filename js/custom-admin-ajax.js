jQuery(document).ready(function($) {
    // Handle menu item clicks
    $('#adminmenu a').on('click', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        // Show the full-page overlay with spinner
        $('body').append('<div class="full-page-overlay"><div class="spinner"></div></div>');

        // Load the content via AJAX
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Extract the content from the response
                var content = $(response).find('#wpbody-content').html();
                $('#wpbody-content').html(content);

                // Update the browser's URL without reloading the page
                history.pushState(null, null, url);

                // Reinitialize scripts if needed (e.g., for forms, buttons, etc.)
                $(document).trigger('ajaxPageLoad');

                // Special handling for post-new.php
                if (url.indexOf('post-new.php') !== -1) {
                    initializePostEditor();
                }

                // Remove the full-page overlay with spinner
                $('.full-page-overlay').remove();
            },
            error: function() {
                alert('Failed to load content.');

                // Remove the full-page overlay with spinner
                $('.full-page-overlay').remove();
            }
        });

        return false;
    });

    // Handle browser back/forward buttons
    $(window).on('popstate', function() {
        var url = window.location.href;

        // Show the full-page overlay with spinner
        $('body').append('<div class="full-page-overlay"><div class="spinner"></div></div>');

        // Load the content via AJAX
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                var content = $(response).find('#wpbody-content').html();
                $('#wpbody-content').html(content);

                // Reinitialize scripts if needed
                $(document).trigger('ajaxPageLoad');

                // Special handling for post-new.php
                if (url.indexOf('post-new.php') !== -1) {
                    initializePostEditor();
                }

                // Remove the full-page overlay with spinner
                $('.full-page-overlay').remove();
            },
            error: function() {
                alert('Failed to load content.');

                // Remove the full-page overlay with spinner
                $('.full-page-overlay').remove();
            }
        });
    });

    // Function to reinitialize the post editor
    function initializePostEditor() {
        // Reinitialize the WordPress editor components for post-new.php
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#content', // Ensure the correct selector is used
                // Add other TinyMCE options as needed
            });
        }

        // Reinitialize other scripts for post editor, e.g., Quicktags, media, etc.
        if (typeof quicktags !== 'undefined') {
            quicktags({id: 'content'});
        }
        if (typeof wp.media !== 'undefined') {
            wp.media.editor.initialize('content');
        }

        // Trigger any other required WordPress actions
        $(document).trigger('wp-editor-reinit');
    }
});



 