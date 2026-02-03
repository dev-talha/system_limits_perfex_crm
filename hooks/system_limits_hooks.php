<?php
defined('BASEPATH') or exit('No direct script access allowed');

function system_limits_register_hooks()
{
    hooks()->add_action('before_lead_added', function ($data = null) {
        system_limits_block_or_return('leads');
        return $data;
    });

    hooks()->add_action('before_client_added', function ($data) {
        system_limits_block_or_return('customers');
        return $data;
    });

    hooks()->add_action('before_create_staff_member', function ($data) {
        system_limits_block_or_return('staff');
        return $data;
    });

    hooks()->add_action('before_invoice_added', function ($data) {
        system_limits_block_or_return('invoices');
        return $data;
    });

    hooks()->add_action('before_estimate_added', function ($data) {
        system_limits_block_or_return('estimates');
        return $data;
    });

    hooks()->add_action('before_create_proposal', function ($data) {
        system_limits_block_or_return('proposals');
        return $data;
    });

    hooks()->add_action('before_add_project', function ($data) {
        system_limits_block_or_return('projects');
        return $data;
    });
    // Project copy/clone (hook names may vary)
    hooks()->add_action('before_copy_project', function ($data = null) {
        system_limits_block_or_return('projects');
        return $data;
    });
    hooks()->add_action('before_project_copy', function ($data = null) {
        system_limits_block_or_return('projects');
        return $data;
    });
    hooks()->add_action('before_clone_project', function ($data = null) {
        system_limits_block_or_return('projects');
        return $data;
    });


    hooks()->add_action('before_add_task', function ($data) {
        system_limits_block_or_return('tasks');
        return $data;
    });

    hooks()->add_action('before_upload_project_attachment', function ($id = null) {
        system_limits_block_or_return('media');
        return $id;
    });
    hooks()->add_action('before_upload_lead_attachment', function ($id = null) {
        system_limits_block_or_return('media');
        return $id;
    });
    hooks()->add_action('before_upload_client_attachment', function ($id = null) {
        system_limits_block_or_return('media');
        return $id;
    });
}
