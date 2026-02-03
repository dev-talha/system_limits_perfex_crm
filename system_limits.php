<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: System Limits
Description: Simple global limits for core Perfex resources (Leads, Staff, Customers, Proposals, Estimates, Invoices, Projects, Tasks, Media).
Version: 1.1.4
Author: System Limits Clean
*/

define('SYSTEM_LIMITS_MODULE_NAME', 'system_limits');

register_activation_hook(SYSTEM_LIMITS_MODULE_NAME, 'system_limits_activate');
hooks()->add_action('pre_admin_init', 'system_limits_pre_admin_init');
hooks()->add_action('admin_init', 'system_limits_admin_init');

function system_limits_pre_admin_init()
{
    system_limits_ensure_table(false);
}

function system_limits_activate()
{
    // Create table on activation
    system_limits_ensure_table(true);
}

function system_limits_admin_init()
{
    $CI = &get_instance();

    // Ensure table exists (safe), then load helper
    system_limits_ensure_table(false);
    $CI->load->helper(SYSTEM_LIMITS_MODULE_NAME . '/system_limits');
    // Load module language
    $CI->lang->load('system_limits', 'english', false, true, module_dir_path(SYSTEM_LIMITS_MODULE_NAME, ''));

    // Add Setup menu item
    if (is_admin() && isset($CI->app_menu)) {
        $CI->app_menu->add_setup_menu_item('system-limits', [
            'slug'     => 'system-limits',
            'name'     => _l('system_limits_menu'),
            'href'     => admin_url('system_limits'),
            'position' => 200,
        ]);
    }

    // Register hooks
    require_once(module_dir_path(SYSTEM_LIMITS_MODULE_NAME, 'hooks/system_limits_hooks.php'));
    system_limits_register_hooks();
}

/**
 * Ensure DB table exists. If $force = true, always attempts create.
 */
function system_limits_ensure_table($force = false)
{
    $CI = &get_instance();

    // db_prefix() exists in Perfex; but guard anyway
    if (!function_exists('db_prefix')) {
        return;
    }

    $table = $CI->db->dbprefix('system_limits');

    if (!$force && $CI->db->table_exists($table)) {
        return;
    }

    $CI->load->dbforge();

    if (!$CI->db->table_exists($table)) {
        $fields = [
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'resource' => ['type'=>'VARCHAR','constraint'=>50,'null'=>false],
            'limit_value' => ['type'=>'INT','constraint'=>11,'null'=>true,'default'=>null],
            'is_enabled' => ['type'=>'TINYINT','constraint'=>1,'null'=>false,'default'=>0],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ];

        $CI->dbforge->add_field($fields);
        $CI->dbforge->add_key('id', true);
        $CI->dbforge->add_key('resource', false, true);
        $CI->dbforge->create_table('system_limits', true);
    }

    // Ensure updated_at column exists (older installs)
    if (!$CI->db->field_exists('updated_at', $table)) {
        $CI->dbforge->add_column($table, [
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
    }

    // Seed resources
    $resources = ['leads','staff','customers','proposals','estimates','invoices','projects','tasks','media'];
    foreach ($resources as $r) {
        $CI->db->where('resource', $r);
        $exists = $CI->db->get($table)->row();
        if (!$exists) {
            $CI->db->insert($table, [
                'resource'=>$r,
                'limit_value'=>null,
                'is_enabled'=>0,
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }
    }
}
