<?php
defined('BASEPATH') or exit('No direct script access allowed');

class System_limits extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!is_admin()) {
            access_denied('system_limits');
        }

        $this->load->helper('system_limits/system_limits');
        $this->load->model('system_limits/System_limits_model', 'sl_model');

        // Load module language file (module path)
        $this->lang->load('system_limits', 'english', false, true, module_dir_path('system_limits', ''));
    }

    public function index()
    {
        if ($this->input->post()) {
            $resources = ['leads','staff','customers','proposals','estimates','invoices','projects','tasks','media'];

            foreach ($resources as $r) {
                $limit_value = $this->input->post('limit_'.$r);
                $is_enabled  = $this->input->post('enabled_'.$r) ? 1 : 0;
                $this->sl_model->upsert($r, $limit_value, $is_enabled);
            }

            set_alert('success', _l('system_limits_saved'));
            redirect(admin_url('system_limits'));
        }

        $data['title']  = _l('system_limits_menu');
        $data['limits'] = $this->sl_model->get_all();

        $this->load->view('settings', $data);
    }
}
