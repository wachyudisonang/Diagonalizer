<?php

// CPT.php
// $people = new CPT(array(
//     'post_type_name' => 'person',
//     'singular' => 'Person',
//     'plural' => 'People',
//     'slug' => 'kampret'
// ), array(
//     'supports' => array('editor', 'thumbnail', 'comments')
// ));

// $people->register_taxonomy(array(
//     'taxonomy_name' => 'genre',
//     'singular' => 'Genre',
//     'plural' => 'Genres',
//     'slug' => 'genre'
// ));

// $people->columns(array(
//     'cb' => '<input type="checkbox" />',
//     'title' => __('Title'),
//     'genres' => __('Genres'),
//     'price' => __('Price'),
//     'rating' => __('Rating'),
//     'date' => __('Date')
// ));

function scpt_demo() {
    if ( ! class_exists( 'Super_Custom_Post_Type' ) )
        return;

    $demo_posts = new Super_Custom_Post_Type( 'demo-post' );

    # Test Icon. Should be a square grid.
    $demo_posts->set_icon( 'th-large' );

    # Taxonomy test, should be like tags
    $tax_tags = new Super_Custom_Taxonomy( 'tax-tag' );

    # Taxonomy test, should be like categories
    $tax_cats = new Super_Custom_Taxonomy( 'tax-cat', 'Tax Cat', 'Tax Cats', 'category' );

    # Connect both of the above taxonomies with the post type
    connect_types_and_taxes( $demo_posts, array( $tax_tags, $tax_cats ) );

    # Add a meta box with every field type
    $demo_posts->add_meta_box( array(
        'id'      => 'demo-fields',
        'context' => 'normal',
        'fields'  => array(
            'textbox-demo'        => array(),
            'textarea-demo'       => array( 'type' => 'textarea' ),
            'media-demo'          => array( 'type' => 'media' ),
            'wysiwyg-demo'        => array( 'type' => 'wysiwyg' ),
            'boolean-demo'        => array( 'type' => 'boolean' ),
            'checkboxes-demo'     => array( 'type' => 'checkbox', 'options' => array( 'one', 'two', 'three' ) ),
            'radio-buttons-demo'  => array( 'type' => 'radio',    'options' => array( 'one', 'two', 'three' ) ),
            'select-demo'         => array( 'type' => 'select',   'options' => array( 1 => 'one', 2 => 'two', 3 => 'three' ) ),
            'multi-select-demo'   => array( 'type' => 'select',   'options' => array( 'one', 'two', 'three' ), 'multiple' => 'multiple' ),
            'date-demo'           => array( 'type' => 'date' ),
            'label-override-demo' => array( 'label' => 'Label Demo' )
        )
    ) );

    # Add another CPT to test one-to-one (it could just as easily be one-to-many or many-to-many) relationships
    $linked_posts = new Super_Custom_Post_Type( 'linked-post', 'Other Post', 'Other Posts' );
    $linked_posts->add_meta_box( array(
        'id'      => 'one-to-one',
        'title'   => 'Testing One-to-One relationship',
        'context' => 'side',
        'fields'  => array(
            'demo-posts'   => array( 'type' => 'select', 'data' => 'demo-post' ),
            'side-wysiwyg' => array( 'type' => 'wysiwyg' )
        )
    ) );
    $linked_posts->set_icon( 'cogs' );
}
// add_action( 'after_setup_theme', 'scpt_demo' );

// add_filter( 'scpt_show_admin_menu', '__return_false' );


// include FRAMEWORK_DIR . 'acpt/init.php'; // https://github.com/kevindees/advanced_custom_post_types

// add_action('init', 'makeThem');
function makeThem() {

    $args = array(
        'supports' => array( 'title', 'editor', 'page-attributes'  ),
        'hierarchical' => true,
    );

    $books = acpt_post_type('book','books', false,  $args );
    $courses = acpt_post_type('course','courses', false,  $args );

    $books->icon('notebook');

    acpt_tax('color', 'colors', 'book', true);
    acpt_tax('author', 'authors', array($books, $courses), true );

}

// add_action( 'add_meta_boxes', 'addThem' );
function addThem() {
    acpt_meta_box('Details', array('book', 'course'));
}

function meta_details() {
    $personal = acpt_form('info', array('group' => '[personal]'));
    $personal->text('name');
    $personal->textarea('address');

    $business = acpt_form('info', array('group' => '[business]'));
    $business->text('name');
    $business->textarea('address');
}