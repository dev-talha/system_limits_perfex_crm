<?php
defined('BASEPATH') or exit('No direct script access allowed');

function system_limits_get($resource)
{
    $CI = &get_instance();
    $CI->load->model('system_limits/System_limits_model', 'sl_model');
    return $CI->sl_model->get_limit($resource);
}

function system_limits_usage($resource)
{
    $CI = &get_instance();
    $CI->load->database();

    switch ($resource) {
        case 'leads':      return (int) $CI->db->count_all(db_prefix() . 'leads');
        case 'staff':      return (int) $CI->db->count_all(db_prefix() . 'staff');
        case 'customers':  return (int) $CI->db->count_all(db_prefix() . 'clients');
        case 'proposals':  return (int) $CI->db->count_all(db_prefix() . 'proposals');
        case 'estimates':  return (int) $CI->db->count_all(db_prefix() . 'estimates');
        case 'invoices':   return (int) $CI->db->count_all(db_prefix() . 'invoices');
        case 'projects':   return (int) $CI->db->count_all(db_prefix() . 'projects');
        case 'tasks':      return (int) $CI->db->count_all(db_prefix() . 'tasks');
        case 'media':      return (int) $CI->db->count_all(db_prefix() . 'files');
        default:           return 0;
    }
}

function system_limits_can_add($resource)
{
    if (function_exists('is_super_admin') && is_super_admin()) {
        return [true, null];
    }

    $row = system_limits_get($resource);
    if (!$row || empty($row['is_enabled'])) {
        return [true, null];
    }

    $limit = isset($row['limit_value']) ? (int)$row['limit_value'] : 0;
    if ($limit <= 0) {
        return [true, null];
    }

    $used = system_limits_usage($resource);
    if ($used >= $limit) {
        $label = _l('system_limits_' . $resource);
        return [false, sprintf(_l('system_limits_reached'), $label)];
    }

    return [true, null];
}

function system_limits_block_or_return($resource)
{
    list($ok, $msg) = system_limits_can_add($resource);
    if ($ok) { return true; }

    $CI = &get_instance();
    if ($CI->input->is_ajax_request()) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>$msg]);
        exit;
    }

    set_alert('danger', $msg);
    redirect($_SERVER['HTTP_REFERER'] ?? admin_url());
    exit;
}
