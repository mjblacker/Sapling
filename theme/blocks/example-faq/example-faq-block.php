<?php

/**
 * Block Name: FAQ Block
 */

// Register ACF Block
function example_faq_register_block()
{
    $name = "example-faq";

    if (function_exists('acf_register_block_type')) {
        acf_register_block_type(array(
            'name'             => $name,
            'title'            => __('Example FAQ'),
            'description'      => __('An example FAQ block with automatic schema'),
            'render_callback'  => 'acf_block_php_render_callback',
            'category'         => 'custom',
            'icon'             => 'grid',
            'keywords'         => [],
            'supports'         => [
                'align' => ['wide', 'full'],
                'mode'  => 'preview',
            ],
        ));
    }

    // Create ACF Fields
    if (function_exists('acf_add_local_field_group')) {

        acf_add_local_field_group(array(
            'key' => 'group_sapling_faq',
            'title' => 'FAQ Settings',
            'fields' => array(
                array(
                    'key' => 'field_faq_layout',
                    'label' => 'Layout',
                    'name' => 'layout',
                    'type' => 'select',
                    'required' => 1,
                    'choices' => array(
                        'one' => 'One Column',
                        'two' => 'Two Column',
                    ),
                    'default_value' => 'one',  // Optional: sets default
                    'return_format' => 'value'
                ),
                array(
                    'key' => 'field_faq_with_icon',
                    'label' => 'Disable Icon',
                    'name' => 'title_icon_disabled',
                    'type' => 'true_false',
                    'required' => 1,
                    'default_value' => 0,
                    'ui' => 1, // (Optional) Enables toggle switch UI
                ),
                array(
                    'key' => 'field_faq_icon_position',
                    'label' => 'Icon Position',
                    'name' => 'icon_position',
                    'type' => 'select',
                    'required' => 1,
                    'choices' => array(
                        'left' => 'Left',
                        'right' => 'Right',
                        'none' => 'None',
                    ),
                    'default_value' => 'left',  // Optional: sets default position
                    'return_format' => 'value'
                ),
                array(
                    'key' => 'field_faq_items',
                    'label' => 'Items',
                    'name' => 'items',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Item',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_question',
                            'label' => 'Question',
                            'name' => 'question',
                            'type' => 'text',
                            'required' => 1,
                            'default_value' => '',
                        ),
                        array(
                            'key' => 'field_answer',
                            'label' => 'Answer',
                            'name' => 'answer',
                            'type' => 'wysiwyg',
                            'required' => 1,
                            'default_value' => '',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/'.$name,
                    ),
                ),
            ),
        ));


    }
}
