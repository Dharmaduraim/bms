<div class="page-heading mb-4">
  <div>
    <h1>
      <?= html_escape($title) ?>
      </h1>
      <p>
        Manage
        <?= strtolower(html_escape($title)) ?>
          used across invoices and financial records.
        </p>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-header">
            <?= !empty($edit)?'Edit record':'Add record' ?>
            </div>
            <div class="card-body">
              <form method="post" action="<?= site_url($type.(!empty($edit)?'/'.$edit['id']:'')) ?>
                ">
                <input type="hidden" name="<?= $this->
                security->get_csrf_token_name() ?>" value="
                <?= $this->
                  security->get_csrf_hash() ?>">
                  <?php foreach($fields as $field=>
                    $label): $value=$edit[$field]??''; ?>
                    <div class="mb-3">
                      <label class="form-label">
                        <?= html_escape($label) ?>
                        </label>
                        <?php if($field==='address'): ?>
                          <textarea class="form-control" name="<?= $field ?>
                            " rows="3">
                            <?= html_escape($value) ?>
                            </textarea>
                            <?php else: ?>
                              <input class="form-control" name="<?= $field ?>
                              " value="
                              <?= html_escape($value) ?>
                                "
                                <?= $field==='name'?'required':'' ?>
                                  <?= $field==='email'?'type="email"':'' ?>
                                    <?= in_array($field,array('unit_price','tax_rate'),TRUE)?'type="number" min="0" step="0.01"':'' ?>
                                      >
                                      <?php endif; ?>
                                      </div>
                                      <?php endforeach; ?>
                                        <button class="btn btn-primary">
                                          Save
                                        </button>
                                        <?php if(!empty($edit)): ?>
                                          <a class="btn btn-outline-secondary" href="<?= site_url($type) ?>
                                            ">Cancel
                                          </a>
                                          <?php endif; ?>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-xl-8">
                                      <div class="card">
                                        <div class="card-header">
                                          Records
                                        </div>
                                        <div class="table-responsive">
                                          <table class="table align-middle mb-0">
                                            <thead>
                                              <tr>
                                                <?php foreach($fields as $field=>
                                                  $label): ?>
                                                  <th>
                                                    <?= html_escape($label) ?>
                                                    </th>
                                                    <?php endforeach; ?>
                                                      <th class="text-end">
                                                        Actions
                                                      </th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <?php foreach($rows as $row): ?>
                                                      <tr>
                                                        <?php foreach($fields as $field=>
                                                          $label): ?>
                                                          <td>
                                                            <?php if(in_array($field,array('unit_price'),TRUE)): ?>
                                                              ₹
                                                              <?= number_format((float)$row[$field],2) ?>
                                                                <?php elseif($field==='tax_rate'): ?>
                                                                  <?= number_format((float)$row[$field],2) ?>
                                                                    %
                                                                    <?php else: ?>
                                                                      <?= html_escape((string)($row[$field]??'')) ?>
                                                                        <?php endif; ?>
                                                                        </td>
                                                                        <?php endforeach; ?>
                                                                          <td class="text-end text-nowrap">
                                                                            <a class="btn btn-sm btn-outline-primary" href="<?= site_url($type.'/'.$row['id']) ?>
                                                                              ">Edit
                                                                            </a>
                                                                            <form class="d-inline delete-form" method="post" action="<?= site_url('records/delete/'.$type.'/'.$row['id']) ?>
                                                                              ">
                                                                              <input type="hidden" name="<?= $this->
                                                                              security->get_csrf_token_name() ?>" value="
                                                                              <?= $this->
                                                                                security->get_csrf_hash() ?>">
                                                                                <button class="btn btn-sm btn-outline-danger">
                                                                                  Delete
                                                                                </button>
                                                                              </form>
                                                                            </td>
                                                                          </tr>
                                                                          <?php endforeach; ?>
                                                                            <?php if(!$rows): ?>
                                                                              <tr>
                                                                                <td colspan="<?= count($fields)+1 ?>
                                                                                  " class="empty-state">Records will appear here after you add them.
                                                                                </td>
                                                                              </tr>
                                                                              <?php endif; ?>
                                                                              </tbody>
                                                                            </table>
                                                                          </div>
                                                                        </div>
                                                                      </div>
                                                                    </div>