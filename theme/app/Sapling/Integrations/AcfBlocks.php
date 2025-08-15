<?php

use Timber\Timber;
use Sapling\SaplingPlugin;
use Sapling\FileUtils;

class AcfBlocks implements SaplingPlugin
{

    public function init()
    {
        if(!\class_exists(ACF::class)) {
            return;
        }

        // add in our hooks for this plugin
        add_action('acf/init', array( self::class, 'acf_register_json_blocks' ));
        add_action('acf/init', array( self::class, 'acf_register_php_blocks' ));
        add_filter('acf/blocks/wrap_frontend_innerblocks', array(self::class, 'acf_should_wrap_innerblocks'), 10, 2);

        // init the global functions for wordpress
        if(!function_exists('acf_block_json_render_callback')) {

            function acf_block_json_render_callback($block, $content)
            {
                $base_dir = get_template_directory(). '/blocks/';

                $context           = Timber::context();
                $context['post']   = Timber::get_post();
                $context['block']  = $block;
                $context['fields'] = get_fields();
                $block_name        = explode('/', $block['name'])[1];
                $template          = FileUtils::firstOfFiles([
                    $base_dir . $block_name . '/'. $block_name. '-render.twig',
                    $base_dir . $block_name . '/'. $block_name. '-index.twig',
                    $base_dir . $block_name . '/index.twig',
                ]);

                // load the context filter file if it exists
                // change the context for this block here for this specific block
                $context_filter = get_template_directory(). '/blocks/'. $block_name . '/context.php';
                if(file_exists($context_filter)) {
                    include $context_filter;
                }

                Timber::render($template, $context);
            }
        }

        if(!function_exists('acf_block_php_render_callback')) {

            function acf_block_php_render_callback($block, $content)
            {
                $base_dir = get_template_directory(). '/blocks/';

                $context           = Timber::context();
                $context['post']   = Timber::get_post();
                $context['block']  = $block;
                $context['fields'] = get_fields();
                $block_name        = explode('/', $block['name'])[1];
                $template          = FileUtils::firstOfFiles([
                    $base_dir . $block_name . '/'. $block_name. '-render.twig',
                    $base_dir . $block_name . '/'. $block_name. '-index.twig',
                    $base_dir . $block_name . '/index.twig',
                ]);

                // load the context filter file if it exists
                // change the context for this block here for this specific block
                $context_filter = FileUtils::firstOfFiles([
                    $base_dir . $block_name . '/'. $block_name .'-context.php',
                    $base_dir . $block_name . '/context.php',
                ]);

                if($context_filter) {
                    include $context_filter;
                }

                Timber::render($template, $context);
            }
        }
    }

    public static function acf_register_json_blocks()
    {
        $blocks = array();

        $blocks_dir = get_stylesheet_directory() . '/blocks/';
        $block_files = glob($blocks_dir . '*', GLOB_ONLYDIR);

        foreach ($block_files as $block_dir) {

            $block_name = basename($block_dir);
            $block_config = [$block_dir . '/'. $block_name .'-block.json', $block_dir . '/block.json'];

            // get the first block config file that exists
            $block_loader = FileUtils::firstOfFiles($block_config);

            if ($block_loader && file_exists($block_loader)) {
                $blocks[] = $block_loader;
            }
		}
        asort($blocks);

        foreach ($blocks as $block) {
            register_block_type($block);
        }

    }

    public static function acf_register_php_blocks()
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        $blocks_dir = get_stylesheet_directory() . '/blocks/';
        $block_files = glob($blocks_dir . '*', GLOB_ONLYDIR);

        foreach ($block_files as $block_dir) {
            if (file_exists($block_dir . '/block.json')) {
                // json config, skip php
                continue;
            }

            $block_name = basename($block_dir);
            $block_config = [$block_dir . '/'. $block_name .'-block.php', $block_dir . '/block.php'];

            // get the first block config file that exists
            $block_loader = FileUtils::firstOfFiles($block_config);

            // Ensure a block.php file exists in the folder
            if ($block_loader) {
                //include the block config
                include $block_loader;

                // run the register function on the block
                $register_function = str_replace('-', '_', basename($block_dir)) ."_register_block";
                $register_function();
            }
        }
    }

    // Remove ACF block wrapper div
    public static function acf_should_wrap_innerblocks($wrap, $name)
    {
        return false;
    }

}
