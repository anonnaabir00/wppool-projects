import Vue from 'vue';
import App from './App.vue';

jQuery(document).ready(function($) {
    // Add Image button click
    $('#add-gallery-image').on('click', function() {
        var gallery_frame = wp.media({
            title: 'Select Project Images',
            library: {
                type: 'image'
            },
            multiple: true
        });

        gallery_frame.on('select', function() {
            var attachments = gallery_frame.state().get('selection').toJSON();
            var galleryImages = $('#gallery-images');

            $.each(attachments, function(index, attachment) {
                var image_url = attachment.sizes.thumbnail.url;
                var image_id = attachment.id;

                var imageHTML = '<li class="mr-8 mb-8"><img class="project-image" src="' + image_url + '" alt="Gallery Image">';
                imageHTML += '<input type="hidden" name="gallery_images[]" value="' + image_id + '">';
                imageHTML += '<span class="remove-image">Remove</span></li>';

                galleryImages.append(imageHTML);
            });
        });

        gallery_frame.open();
    });

    // Remove Image click
    $('body').on('click', '.remove-image', function() {
        $(this).closest('li').remove();
    });
});


new Vue({
    el: '#wppool_fields',
    render: h => h(App),
    // components: { App }
});
