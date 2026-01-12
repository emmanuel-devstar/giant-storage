<?php
function max_image_size() {
    add_image_size( 'max', 2000, 2000, false); // maximum size of saved images
}
add_action( 'after_setup_theme', 'max_image_size' );

function replace_uploaded_image($image_data) {

    // if there is no large image : return
    if (!isset($image_data['sizes']['max'])) return $image_data;

    // check extension
    $extension = pathinfo($image_data['file'], PATHINFO_EXTENSION);
    if ( $extension === 'svg') return $image_data;

    // paths to the uploaded image and the large image
    $upload_dir = wp_upload_dir();
    $uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
    $large_image_filename = $image_data['sizes']['max']['file'];

    // Do what wordpress does in image_downsize() ... just replace the filenames ;)
    $image_basename = wp_basename($uploaded_image_location);
    $large_image_location = str_replace($image_basename, $large_image_filename, $uploaded_image_location);

    // delete the uploaded image
    unlink($uploaded_image_location);

    // rename the large image
    rename($large_image_location, $uploaded_image_location);

    // update image metadata and return them
    $image_data['width'] = $image_data['sizes']['max']['width'];
    $image_data['height'] = $image_data['sizes']['max']['height'];
    unset($image_data['sizes']['max']);

    // Check if other size-configurations link to the large-file
    foreach($image_data['sizes'] as $size => $sizeData) {
        if ($sizeData['file'] === $large_image_filename)
            unset($image_data['sizes'][$size]);
    }

    return $image_data;
}
add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image');