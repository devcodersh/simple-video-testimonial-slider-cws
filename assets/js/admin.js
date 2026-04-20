(function($) {
    'use strict';

    $(document).ready(function() {
        let slideIndex = $('#svts-slides-container .svts-slide-item').length;

        // Add new slide
        $('#svts-add-slide').on('click', function() {
            slideIndex++;
            const template = $('#svts-slide-template').html()
                .replace(/{{index}}/g, slideIndex - 1)
                .replace(/{{number}}/g, slideIndex);

            $('#svts-slides-container').append(template);
        });

        // Remove slide
        $(document).on('click', '.svts-remove-slide', function() {
            if (confirm(svtsAdmin.confirmDelete)) {
                $(this).closest('.svts-slide-item').fadeOut(300, function() {
                    $(this).remove();
                    updateSlideIndices();
                });
            }
        });

        // Update slide indices after removal
        function updateSlideIndices() {
            $('#svts-slides-container .svts-slide-item').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('h3').text('Testimonial ' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                    }
                });
            });
            slideIndex = $('#svts-slides-container .svts-slide-item').length;
        }

        // Show success message
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('message') === 'saved') {
            $('<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>')
                .insertAfter('.wrap > h1')
                .delay(3000)
                .fadeOut();
        }
    });

})(jQuery);