<div class="page-heading mb-4">
  <div>
    <h1>
      Budget Management
    </h1>
    <p>
      Create monthly or yearly budgets and monitor spend against each allocation.
    </p>
  </div>
</div>
<div class="row g-4">
  <div class="col-xl-4">
    <div class="card">
      <div class="card-header">
        <?= $edit?'Edit budget':'Create budget' ?>
        </div>
        <div class="card-body">
          <form method="post" action="<?= site_url('budgets'.($edit?'/'.$edit['id']:'')) ?>
            ">
            <input type="hidden" name="<?= $this->
            security->get_csrf_token_name() ?>" value="
            <?= $this->
              security->get_csrf_hash() ?>">
              <div class="mb-3">
                <label class="form-label">
                  Budget name
                </label>
                <input class="form-control" name="name" required maxlength="150" value="<?= html_escape($edit['name']??'') ?>
                " placeholder="Marketing - October">
              </div>
              <div class="mb-3">
                <label class="form-label">
                  Period
                </label>
                <select class="form-select" name="period_type">
                  <option value="monthly" <?= ($edit['period_type']??'monthly')==='monthly'?'selected':'' ?>
                    >Monthly
                  </option>
                  <option value="yearly" <?= ($edit['period_type']??'')==='yearly'?'selected':'' ?>
                    >Yearly
                  </option>
                </select>
              </div>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label class="form-label">
                    Start date
                  </label>
                  <input class="form-control" type="date" name="period_start" required value="<?= html_escape($edit['period_start']??date('Y-m-01')) ?>
                  ">
                </div>
                <div class="col-sm-6">
                  <label class="form-label">
                    End date
                  </label>
                  <input class="form-control" type="date" name="period_end" required value="<?= html_escape($edit['period_end']??date('Y-m-t')) ?>
                  ">
                </div>
              </div>
              <div class="mt-3 mb-4">
                <label class="form-label">
                  Budget amount
                </label>
                <div class="input-group">
                  <span class="input-group-text">
                    ₹
                  </span>
                  <input class="form-control" type="number" name="amount" min="0" step="0.01" required value="<?= html_escape($edit['amount']??'') ?>
                  ">
                </div>
              </div>
              <button class="btn btn-primary">
                Save budget
              </button>
              <?php if($edit): ?>
                <a class="btn btn-outline-secondary" href="<?= site_url('budgets') ?>
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
                Budget performance
              </div>
              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>
                        Budget
                      </th>
                      <th>
                        Period
                      </th>
                      <th>
                        Allocated
                      </th>
                      <th>
                        Spent
                      </th>
                      <th>
                        Remaining
                      </th>
                      <th>
                        Usage
                      </th>
                      <th class="text-end">
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($rows as $row): $spent=(float)$row['spent']; $amount=(float)$row['amount']; $remaining=$amount-$spent; $pct=$amount>
                      0?($spent/$amount)*100:0; ?>
                      <tr>
                        <td>
                          <strong>
                            <?= html_escape($row['name']) ?>
                            </strong>
                            <div class="small text-muted text-capitalize">
                              <?= html_escape($row['period_type']) ?>
                              </div>
                            </td>
                            <td class="small">
                              <?= html_escape($row['period_start']) ?>
                                <br>
                                <?= html_escape($row['period_end']) ?>
                                </td>
                                <td>
                                  ₹
                                  <?= number_format($amount,2) ?>
                                  </td>
                                  <td>
                                    ₹
                                    <?= number_format($spent,2) ?>
                                    </td>
                                    <td class="<?= $remaining<0?'text-danger fw-semibold':'text-success fw-semibold' ?>
                                      ">₹
                                      <?= number_format($remaining,2) ?>
                                      </td>
                                      <td style="min-width:130px">
                                        <div class="d-flex justify-content-between small">
                                          <span>
                                            <?= number_format($pct,0) ?>
                                              %
                                            </span>
                                            <?php if($pct>
                                              100): ?>
                                              <span class="text-danger">
                                                Over
                                              </span>
                                              <?php endif; ?>
                                              </div>
                                              <div class="progress">
                                                <div class="progress-bar <?= $pct>
                                                  100?'bg-danger':'' ?>" style="width:
                                                  <?= min(100,$pct) ?>
                                                    %">
                                                  </div>
                                                </div>
                                              </td>
                                              <td class="text-end text-nowrap">
                                                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('budgets/'.$row['id']) ?>
                                                  ">Edit
                                                </a>
                                                <form class="d-inline delete-form" method="post" action="<?= site_url('budgets/delete/'.$row['id']) ?>
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
                                                    <td colspan="7" class="empty-state">
                                                      Create a budget to begin planned-versus-actual tracking.
                                                    </td>
                                                  </tr>
                                                  <?php endif; ?>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>