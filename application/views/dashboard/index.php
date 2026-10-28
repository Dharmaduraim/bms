<div class="page-heading mb-4">
  <div>
    <h1>
      Financial Dashboard
    </h1>
    <p>
      Overview of invoices, expenses, budgets and cash collection.
    </p>
  </div>
</div>
<div class="row g-3 mb-4">
  <?php $cards=array(
 array('Sales','sales','bi-graph-up-arrow','Total invoiced sales'),
 array('Purchases','purchases','bi-cart3','Total purchase value'),
 array('Expenses','expenses','bi-cash-stack','Recorded operating expenses'),
 array('Net position','profit','bi-pie-chart','Sales - purchases - expenses')
); foreach($cards as $card): ?>
    <div class="col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="bi <?= $card[2] ?>
            ">
          </i>
        </div>
        <div class="text-muted small mb-1">
          <?= $card[0] ?>
          </div>
          <div class="fs-3 fw-bold">
            ₹
            <?= number_format((float)$stats[$card[1]],2) ?>
            </div>
            <div class="small text-muted mt-2">
              <?= $card[3] ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          </div>
          <div class="row g-4">
            <div class="col-xl-8">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <span>
                    Recent sales invoices
                  </span>
                  <a href="<?= site_url('sales-invoices') ?>
                    " class="btn btn-sm btn-outline-primary">View all
                  </a>
                </div>
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead>
                      <tr>
                        <th>
                          Invoice
                        </th>
                        <th>
                          Customer
                        </th>
                        <th>
                          Date
                        </th>
                        <th>
                          Total
                        </th>
                        <th>
                          Status
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($recent_sales as $row): ?>
                        <tr>
                          <td>
                            <a class="fw-semibold text-decoration-none" href="<?= site_url('sales-invoices/view/'.$row->
                              id) ?>">
                              <?= html_escape($row->
                                invoice_no) ?>
                              </a>
                            </td>
                            <td>
                              <?= html_escape($row->
                                party_name ?: '-') ?>
                              </td>
                              <td>
                                <?= html_escape($row->
                                  invoice_date) ?>
                                </td>
                                <td>
                                  ₹
                                  <?= number_format((float)$row->
                                    total,2) ?>
                                  </td>
                                  <td>
                                    <span class="status-badge status-<?= strtolower(str_replace(' ','-',$row->
                                      status)) ?>">
                                      <?= html_escape($row->
                                        status) ?>
                                      </span>
                                    </td>
                                  </tr>
                                  <?php endforeach; ?>
                                    <?php if(!$recent_sales): ?>
                                      <tr>
                                        <td colspan="5" class="empty-state">
                                          Sales invoices will appear here after you create them.
                                        </td>
                                      </tr>
                                      <?php endif; ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                              <div class="col-xl-4">
                                <div class="card h-100">
                                  <div class="card-header">
                                    Budget health
                                  </div>
                                  <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                      <span>
                                        Total budget
                                      </span>
                                      <strong>
                                        ₹
                                        <?= number_format((float)$stats['budgets'],2) ?>
                                        </strong>
                                      </div>
                                      <div class="d-flex justify-content-between mb-3">
                                        <span>
                                          Total expenses
                                        </span>
                                        <strong>
                                          ₹
                                          <?= number_format((float)$stats['expenses'],2) ?>
                                          </strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                          <span>
                                            Remaining
                                          </span>
                                          <strong class="<?= $stats['budget_remaining']<0?'text-danger':'text-success' ?>
                                            ">₹
                                            <?= number_format((float)$stats['budget_remaining'],2) ?>
                                            </strong>
                                          </div>
                                          <hr>
                                          <?php foreach(array_slice($budgets,0,4) as $b): $pct=$b['amount']>
                                            0?min(100,($b['spent']/$b['amount'])*100):0; ?>
                                            <div class="mb-3">
                                              <div class="d-flex justify-content-between small mb-1">
                                                <span>
                                                  <?= html_escape($b['name']) ?>
                                                  </span>
                                                  <span>
                                                    <?= number_format($pct,0) ?>
                                                      %
                                                    </span>
                                                  </div>
                                                  <div class="progress">
                                                    <div class="progress-bar" style="width:<?= $pct ?>
                                                      %">
                                                    </div>
                                                  </div>
                                                </div>
                                                <?php endforeach; ?>
                                                  <?php if(!$budgets): ?>
                                                    <p class="text-muted mb-0">
                                                      Create a budget to start tracking planned versus actual spend.
                                                    </p>
                                                    <?php endif; ?>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>