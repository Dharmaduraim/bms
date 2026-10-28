<?php $route=$type==='sales'?'sales-invoices':'purchase-invoices'; ?>
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h1 class="h3 mb-1">
        Invoice
        <?= html_escape($invoice['invoice_no']) ?>
        </h1>
        <span class="status-badge status-<?= strtolower(str_replace(' ','-',$invoice['status'])) ?>
          ">
          <?= html_escape($invoice['status']) ?>
          </span>
        </div>
        <div>
          <a class="btn btn-outline-secondary" href="<?= site_url($route) ?>
            ">Back
          </a>
          <a class="btn btn-primary" href="<?= site_url($route.'/'.$invoice['id']) ?>
            ">Edit invoice
          </a>
        </div>
      </div>
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-body p-lg-4">
              <div class="row mb-4">
                <div class="col-sm-6">
                  <div class="text-muted small mb-1">
                    <?= $type==='sales'?'Bill to':'Supplier' ?>
                    </div>
                    <h5>
                      <?= html_escape($invoice['party_name']??'-') ?>
                      </h5>
                      <div class="text-muted small">
                        <?= html_escape($invoice['party_email']??'') ?>
                          <br>
                          <?= html_escape($invoice['party_phone']??'') ?>
                            <br>
                            <?= nl2br(html_escape($invoice['party_address']??'')) ?>
                            </div>
                          </div>
                          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <div class="small text-muted">
                              Invoice date
                            </div>
                            <strong>
                              <?= html_escape($invoice['invoice_date']) ?>
                              </strong>
                              <div class="small text-muted mt-2">
                                Due date
                              </div>
                              <strong>
                                <?= html_escape($invoice['due_date']?:'-') ?>
                                </strong>
                              </div>
                            </div>
                            <div class="table-responsive">
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>
                                      Description
                                    </th>
                                    <th class="text-end">
                                      Qty
                                    </th>
                                    <th class="text-end">
                                      Unit price
                                    </th>
                                    <th class="text-end">
                                      Tax
                                    </th>
                                    <th class="text-end">
                                      Total
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach($invoice['items'] as $item): ?>
                                    <tr>
                                      <td>
                                        <?= html_escape($item['description']) ?>
                                        </td>
                                        <td class="text-end">
                                          <?= number_format((float)$item['quantity'],2) ?>
                                          </td>
                                          <td class="text-end">
                                            ₹
                                            <?= number_format((float)$item['unit_price'],2) ?>
                                            </td>
                                            <td class="text-end">
                                              <?= number_format((float)$item['tax_rate'],2) ?>
                                                %
                                              </td>
                                              <td class="text-end">
                                                ₹
                                                <?= number_format((float)$item['line_total'],2) ?>
                                                </td>
                                              </tr>
                                              <?php endforeach; ?>
                                              </tbody>
                                            </table>
                                          </div>
                                          <div class="row justify-content-end">
                                            <div class="col-sm-6 col-md-5">
                                              <div class="invoice-summary">
                                                <div>
                                                  <span>
                                                    Subtotal
                                                  </span>
                                                  <strong>
                                                    ₹
                                                    <?= number_format((float)$invoice['subtotal'],2) ?>
                                                    </strong>
                                                  </div>
                                                  <div>
                                                    <span>
                                                      Tax
                                                    </span>
                                                    <strong>
                                                      ₹
                                                      <?= number_format((float)$invoice['tax'],2) ?>
                                                      </strong>
                                                    </div>
                                                    <div>
                                                      <span>
                                                        Discount
                                                      </span>
                                                      <strong>
                                                        - ₹
                                                        <?= number_format((float)$invoice['discount'],2) ?>
                                                        </strong>
                                                      </div>
                                                      <div class="grand-total">
                                                        <span>
                                                          Total
                                                        </span>
                                                        <strong>
                                                          ₹
                                                          <?= number_format((float)$invoice['total'],2) ?>
                                                          </strong>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <?php if(!empty($invoice['notes'])): ?>
                                                    <hr>
                                                    <div class="small text-muted">
                                                      Notes
                                                    </div>
                                                    <p class="mb-0">
                                                      <?= nl2br(html_escape($invoice['notes'])) ?>
                                                      </p>
                                                      <?php endif; ?>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="col-lg-4">
                                                    <div class="card mb-4">
                                                      <div class="card-header">
                                                        Payment summary
                                                      </div>
                                                      <div class="card-body">
                                                        <div class="d-flex justify-content-between mb-2">
                                                          <span>
                                                            Total
                                                          </span>
                                                          <strong>
                                                            ₹
                                                            <?= number_format((float)$invoice['total'],2) ?>
                                                            </strong>
                                                          </div>
                                                          <div class="d-flex justify-content-between mb-2">
                                                            <span>
                                                              Paid
                                                            </span>
                                                            <strong class="text-success">
                                                              ₹
                                                              <?= number_format((float)$paid_amount,2) ?>
                                                              </strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                              <span>
                                                                Balance
                                                              </span>
                                                              <strong class="<?= $balance>
                                                                0?'text-danger':'text-success' ?>">₹
                                                                <?= number_format((float)$balance,2) ?>
                                                                </strong>
                                                              </div>
                                                            </div>
                                                          </div>
                                                          <?php if($balance>
                                                            0): ?>
                                                            <div class="card mb-4">
                                                              <div class="card-header">
                                                                Record payment
                                                              </div>
                                                              <div class="card-body">
                                                                <form method="post" action="<?= site_url($route.'/payment/'.$invoice['id']) ?>
                                                                  ">
                                                                  <input type="hidden" name="<?= $this->
                                                                  security->get_csrf_token_name() ?>" value="
                                                                  <?= $this->
                                                                    security->get_csrf_hash() ?>">
                                                                    <div class="mb-3">
                                                                      <label class="form-label">
                                                                        Amount
                                                                      </label>
                                                                      <input class="form-control" type="number" name="amount" min="0.01" max="<?= $balance ?>
                                                                      " step="0.01" value="
                                                                      <?= number_format($balance,2,'.','') ?>
                                                                        " required>
                                                                      </div>
                                                                      <div class="mb-3">
                                                                        <label class="form-label">
                                                                          Payment date
                                                                        </label>
                                                                        <input class="form-control" type="date" name="payment_date" value="<?= date('Y-m-d') ?>
                                                                        " required>
                                                                      </div>
                                                                      <div class="mb-3">
                                                                        <label class="form-label">
                                                                          Method
                                                                        </label>
                                                                        <select class="form-select" name="method">
                                                                          <option>
                                                                            Bank Transfer
                                                                          </option>
                                                                          <option>
                                                                            Cash
                                                                          </option>
                                                                          <option>
                                                                            Card
                                                                          </option>
                                                                          <option>
                                                                            UPI
                                                                          </option>
                                                                          <option>
                                                                            Cheque
                                                                          </option>
                                                                        </select>
                                                                      </div>
                                                                      <div class="mb-3">
                                                                        <label class="form-label">
                                                                          Reference
                                                                        </label>
                                                                        <input class="form-control" name="reference_no" maxlength="100">
                                                                      </div>
                                                                      <button class="btn btn-primary w-100">
                                                                        Add payment
                                                                      </button>
                                                                    </form>
                                                                  </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                  <div class="card">
                                                                    <div class="card-header">
                                                                      Payment history
                                                                    </div>
                                                                    <div class="list-group list-group-flush">
                                                                      <?php foreach($payments as $p): ?>
                                                                        <div class="list-group-item">
                                                                          <div class="d-flex justify-content-between">
                                                                            <strong>
                                                                              ₹
                                                                              <?= number_format((float)$p['amount'],2) ?>
                                                                              </strong>
                                                                              <span class="small text-muted">
                                                                                <?= html_escape($p['payment_date']) ?>
                                                                                </span>
                                                                              </div>
                                                                              <div class="small text-muted">
                                                                                <?= html_escape($p['method']?:'Payment') ?>
                                                                                  <?= $p['reference_no']?' · '.html_escape($p['reference_no']):'' ?>
                                                                                  </div>
                                                                                </div>
                                                                                <?php endforeach; ?>
                                                                                  <?php if(!$payments): ?>
                                                                                    <div class="list-group-item text-muted small">
                                                                                      Payment activity will appear here.
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                    </div>
                                                                                  </div>
                                                                                </div>
                                                                              </div>