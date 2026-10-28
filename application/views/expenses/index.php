<div class="page-heading mb-4">
  <div>
    <h1>
      Expense Tracking
    </h1>
    <p>
      Record business expenses and assign them to budgets for real-time monitoring.
    </p>
  </div>
</div>
<div class="row g-4">
  <div class="col-xl-4">
    <div class="card">
      <div class="card-header">
        <?= $edit?'Edit expense':'Add expense' ?>
        </div>
        <div class="card-body">
          <form method="post" action="<?= site_url('expenses'.($edit?'/'.$edit['id']:'')) ?>
            ">
            <input type="hidden" name="<?= $this->
            security->get_csrf_token_name() ?>" value="
            <?= $this->
              security->get_csrf_hash() ?>">
              <div class="mb-3">
                <label class="form-label">
                  Title
                </label>
                <input class="form-control" name="title" required maxlength="150" value="<?= html_escape($edit['title']??'') ?>
                " placeholder="Office internet bill">
              </div>
              <div class="mb-3">
                <label class="form-label">
                  Budget
                </label>
                <select class="form-select" name="budget_id">
                  <option value="">
                    Unassigned
                  </option>
                  <?php foreach($budgets as $b): ?>
                    <option value="<?= (int)$b['id'] ?>
                      "
                      <?= (string)($edit['budget_id']??'')===(string)$b['id']?'selected':'' ?>
                        >
                        <?= html_escape($b['name']) ?>
                        </option>
                        <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="row g-3">
                        <div class="col-sm-6">
                          <label class="form-label">
                            Date
                          </label>
                          <input class="form-control" type="date" name="expense_date" required value="<?= html_escape($edit['expense_date']??date('Y-m-d')) ?>
                          ">
                        </div>
                        <div class="col-sm-6">
                          <label class="form-label">
                            Amount
                          </label>
                          <div class="input-group">
                            <span class="input-group-text">
                              ₹
                            </span>
                            <input class="form-control" type="number" name="amount" min="0.01" step="0.01" required value="<?= html_escape($edit['amount']??'') ?>
                            ">
                          </div>
                        </div>
                      </div>
                      <div class="mt-3 mb-3">
                        <label class="form-label">
                          Category
                        </label>
                        <input class="form-control" name="category" maxlength="100" value="<?= html_escape($edit['category']??'') ?>
                        " placeholder="Utilities">
                      </div>
                      <div class="mb-4">
                        <label class="form-label">
                          Notes
                        </label>
                        <textarea class="form-control" name="notes" rows="3">
                          <?= html_escape($edit['notes']??'') ?>
                          </textarea>
                        </div>
                        <button class="btn btn-primary">
                          Save expense
                        </button>
                        <?php if($edit): ?>
                          <a class="btn btn-outline-secondary" href="<?= site_url('expenses') ?>
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
                          Expense records
                        </div>
                        <div class="table-responsive">
                          <table class="table align-middle mb-0">
                            <thead>
                              <tr>
                                <th>
                                  Date
                                </th>
                                <th>
                                  Expense
                                </th>
                                <th>
                                  Category
                                </th>
                                <th>
                                  Budget
                                </th>
                                <th>
                                  Amount
                                </th>
                                <th class="text-end">
                                  Actions
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach($rows as $row): ?>
                                <tr>
                                  <td>
                                    <?= html_escape($row['expense_date']) ?>
                                    </td>
                                    <td>
                                      <strong>
                                        <?= html_escape($row['title']) ?>
                                        </strong>
                                        <?php if($row['notes']): ?>
                                          <div class="small text-muted text-truncate" style="max-width:260px">
                                            <?= html_escape($row['notes']) ?>
                                            </div>
                                            <?php endif; ?>
                                            </td>
                                            <td>
                                              <?= html_escape($row['category']?:'-') ?>
                                              </td>
                                              <td>
                                                <?= html_escape($row['budget_name']?:'Unassigned') ?>
                                                </td>
                                                <td class="fw-semibold">
                                                  ₹
                                                  <?= number_format((float)$row['amount'],2) ?>
                                                  </td>
                                                  <td class="text-end text-nowrap">
                                                    <a class="btn btn-sm btn-outline-primary" href="<?= site_url('expenses/'.$row['id']) ?>
                                                      ">Edit
                                                    </a>
                                                    <form class="d-inline delete-form" method="post" action="<?= site_url('expenses/delete/'.$row['id']) ?>
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
                                                        <td colspan="6" class="empty-state">
                                                          Expense entries will appear here as you record spending.
                                                        </td>
                                                      </tr>
                                                      <?php endif; ?>
                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>