<?php
defined('BASEPATH') or exit('No direct script access allowed');

class System_limits_model extends App_Model
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix() . 'system_limits';
    }

    public function get_all()
    {
        if (!$this->db->table_exists($this->table)) {
            return [];
        }
        $this->db->order_by('resource', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function get_limit($resource)
    {
        if (!$this->db->table_exists($this->table)) {
            return null;
        }
        $this->db->where('resource', $resource);
        return $this->db->get($this->table)->row_array();
    }

    public function upsert($resource, $limit_value, $is_enabled)
    {
        if (!$this->db->table_exists($this->table)) {
            return false;
        }

        $this->db->where('resource', $resource);
        $row = $this->db->get($this->table)->row_array();

        $data = [
            'limit_value' => ($limit_value === '' ? null : (int)$limit_value),
            'is_enabled'  => (int)$is_enabled,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($row) {
            $this->db->where('id', $row['id']);
            return $this->db->update($this->table, $data);
        }

        $data['resource'] = $resource;
        return $this->db->insert($this->table, $data);
    }
}
