<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('system_limits_menu'); ?></h4>
            <hr class="hr-panel-heading" />

            <?php echo form_open(admin_url('system_limits')); ?>
            <p class="text-muted"><?php echo _l('system_limits_hint'); ?></p>

            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th><?php echo _l('system_limits_resource'); ?></th>
                    <th style="width:160px"><?php echo _l('system_limits_enabled'); ?></th>
                    <th style="width:200px"><?php echo _l('system_limits_limit'); ?></th>
                    <th style="width:120px"><?php echo _l('system_limits_used'); ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $map = [];
                  if (isset($limits) && is_array($limits)) {
                    foreach ($limits as $row) { $map[$row['resource']] = $row; }
                  }

                  $resources = ['leads','staff','customers','proposals','estimates','invoices','projects','tasks','media'];

                  foreach ($resources as $r):
                    $row = isset($map[$r]) ? $map[$r] : ['limit_value'=>null,'is_enabled'=>0];
                    $enabled = isset($row['is_enabled']) && (int)$row['is_enabled'] === 1;
                    $limit_value = isset($row['limit_value']) ? $row['limit_value'] : null;
                    $used = system_limits_usage($r);
                ?>
                  <tr>
                    <td><strong><?php echo _l('system_limits_'.$r); ?></strong></td>
                    <td>
                      <div class="checkbox checkbox-primary">
                        <input type="checkbox" id="enabled_<?php echo $r; ?>" name="enabled_<?php echo $r; ?>" <?php echo $enabled ? 'checked' : ''; ?>>
                        <label for="enabled_<?php echo $r; ?>"><?php echo _l('system_limits_enforce'); ?></label>
                      </div>
                    </td>
                    <td>
                      <input type="number" class="form-control" min="0" name="limit_<?php echo $r; ?>" value="<?php echo html_escape($limit_value); ?>" placeholder="<?php echo _l('system_limits_unlimited'); ?>">
                      <small class="text-muted"><?php echo _l('system_limits_unlimited_note'); ?></small>
                    </td>
                    <td><?php echo (int)$used; ?></td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <button type="submit" class="btn btn-primary"><?php echo _l('system_limits_save'); ?></button>
            <?php echo form_close(); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
