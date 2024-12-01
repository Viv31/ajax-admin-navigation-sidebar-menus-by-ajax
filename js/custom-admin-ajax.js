// jQuery(document).ready(function($) {
//     // Handle menu item clicks
//     $('#adminmenu a').on('click', function(e) {
//         e.preventDefault();

//         var url = $(this).attr('href');

//         // Load the content via AJAX
//         $.ajax({
//             url: url,
//             type: 'GET',
//             beforeSend: function() {
//                 // Show a loading spinner or similar
//                 $('#wpbody-content').html('<div class="loading">Loading...</div>');
//             },
//             success: function(response) {
//                 // Extract the content from the response
//                 var content = $(response).find('#wpbody-content').html();
//                 $('#wpbody-content').html(content);
//             },
//             error: function() {
//                 alert('Failed to load content.');
//             }
//         });

//         return false;
//     });
// });

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



 // Bind the submit event to the form, not the button
    jQuery('#loginform').on('submit', function(e) {
       
        e.preventDefault(); // Prevent the default form submission

        // Optional: Remove or adjust the alert for debugging
        alert('Form submitted!');

        var username = jQuery('#user_login').val();
        var password = jQuery('#user_pass').val();
        var security = jQuery('#security').val(); // Ensure this matches your nonce field's ID

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajax_url,
            data: {
                'action': 'loginajaxajaxurl', 
                'username': username,
                'password': password,
                'security': security,
            },
            beforeSend: function() {
               jQuery('#login-message').text(ajax_login_object.loadingmessage);
            },
            success: function(response) {
                if (response.loggedin) {
                    window.location.href = ajax_login_object.redirecturl;
                } else {
                    jQuery('#login-message').text(response.message);
                }
            }
        });
    });


   /* jQuery(document).ready(function($) {
    // Handle clicks on the "All" or "Published" tabs
    jQuery('ul.subsubsub a').on('click', function(e) {
        e.preventDefault(); // Prevent the default link behavior


        var postStatus = jQuery(this).attr('href').split('post_status=')[1].split('&')[0]; // Get the post status from the URL
        
        //alert(postStatus);

        // Highlight the clicked tab
        jQuery('ul.subsubsub a').removeClass('current');
        jQuery(this).addClass('current');
        
        // Perform AJAX request to fetch posts by status
       $.ajax({
            url: ajax_object.ajax_url,
            method: 'POST',
            data: {
                action: 'fetch_posts_by_status',
                nonce: ajax_object.nonce,
                post_status: postStatus
            },
            success: function(response) {
                if (response.success) {
                    var postsTableBody = jQuery('#the-list'); // WordPress post table body
                    postsTableBody.empty(); // Clear current posts

                    // Loop through the returned posts and add them to the table
                    jQuery.each(response.data, function(index, post) {
                        postsTableBody.append(
                            '<tr><td><a href="' + post.link + '">' + post.title + '</a></td></tr>'
                        );
                    });
                } else {
                    alert('Failed to fetch posts.');
                }
            },
            error: function() {
                alert('An error occurred.');
            }
        });
    });
});*/
