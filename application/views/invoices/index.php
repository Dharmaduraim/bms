<?php $isSales=$type==='sales'; $partyId=!empty($edit)?($edit[$party_key]??''):''; $editItems=!empty($edit['items'])?$edit['items']:array(array()); ?>
  <div class="page-heading mb-4">
    <div>
      <h1>
        <?= html_escape($title) ?>
        </h1>
        <p>
          Create invoices with item-level tax, discounts and automatic totals.
        </p>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header">
        <?= $edit?'Edit '.$edit['invoice_no']:'Create invoice' ?>
        </div>
        <div class="card-body">
          <form method="post" action="<?= site_url($route.($edit?'/'.$edit['id']:'')) ?>
            " id="invoiceForm">
            <input type="hidden" name="<?= $this->
            security->get_csrf_token_name() ?>" value="
            <?= $this->
              security->get_csrf_hash() ?>">
              <div class="row g-3 mb-4">
                <div class="col-md-3">
                  <label class="form-label">
                    Invoice number
                  </label>
                  <input class="form-control" name="invoice_no" required maxlength="50" value="<?= html_escape($edit['invoice_no']??'') ?>
                  " placeholder="
                  <?= $isSales?'SI-1001':'PI-1001' ?>
                    ">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">
                      <?= html_escape($party_label) ?>
                      </label>
                      <select class="form-select" name="<?= $party_key ?>
                        " required>
                        <option value="">
                          Choose
                          <?= strtolower($party_label) ?>
                          </option>
                          <?php foreach($parties as $p): ?>
                            <option value="<?= (int)$p['id'] ?>
                              "
                              <?= (string)$partyId===(string)$p['id']?'selected':'' ?>
                                >
                                <?= html_escape($p['name']) ?>
                                </option>
                                <?php endforeach; ?>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">
                                  Invoice date
                                </label>
                                <input class="form-control" type="date" name="invoice_date" required value="<?= html_escape($edit['invoice_date']??date('Y-m-d')) ?>
                                ">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">
                                  Due date
                                </label>
                                <input class="form-control" type="date" name="due_date" value="<?= html_escape($edit['due_date']??'') ?>
                                ">
                              </div>
                            </div>
                            <div class="table-responsive">
                              <table class="table invoice-items-table align-middle">
                                <thead>
                                  <tr>
                                    <th style="min-width:170px">
                                      Product / service
                                    </th>
                                    <th style="min-width:210px">
                                      Description
                                    </th>
                                    <th>
                                      Qty
                                    </th>
                                    <th>
                                      Unit price
                                    </th>
                                    <th>
                                      Tax %
                                    </th>
                                    <th class="text-end">
                                      Line total
                                    </th>
                                    <th>
                                    </th>
                                  </tr>
                                </thead>
                                <tbody id="invoiceItems">
                                  <?php foreach($editItems as $item): ?>
                                    <tr class="invoice-item-row">
                                      <td>
                                        <select class="form-select product-select" name="item_product_id[]">
                                          <option value="">
                                            Custom item
                                          </option>
                                          <?php foreach($products as $p): ?>
                                            <option value="<?= (int)$p['id'] ?>
                                              " data-name="
                                              <?= html_escape($p['name']) ?>
                                                " data-price="
                                                <?= (float)$p['unit_price'] ?>
                                                  " data-tax="
                                                  <?= (float)$p['tax_rate'] ?>
                                                    "
                                                    <?= (string)($item['product_id']??'')===(string)$p['id']?'selected':'' ?>
                                                      >
                                                      <?= html_escape($p['name']) ?>
                                                      </option>
                                                      <?php endforeach; ?>
                                                      </select>
                                                    </td>
                                                    <td>
                                                      <input class="form-control item-description" name="item_description[]" required value="<?= html_escape($item['description']??'') ?>
                                                      ">
                                                    </td>
                                                    <td>
                                                      <input class="form-control item-qty" type="number" min="0.01" step="0.01" name="item_quantity[]" value="<?= html_escape($item['quantity']??'1') ?>
                                                      ">
                                                    </td>
                                                    <td>
                                                      <input class="form-control item-price" type="number" min="0" step="0.01" name="item_unit_price[]" value="<?= html_escape($item['unit_price']??'0') ?>
                                                      ">
                                                    </td>
                                                    <td>
                                                      <input class="form-control item-tax" type="number" min="0" max="100" step="0.01" name="item_tax_rate[]" value="<?= html_escape($item['tax_rate']??'0') ?>
                                                      ">
                                                    </td>
                                                    <td class="text-end fw-semibold item-total">
                                                      ₹0.00
                                                    </td>
                                                    <td>
                                                      <button class="btn btn-sm btn-outline-danger remove-item" type="button">
                                                        <i class="bi bi-trash">
                                                        </i>
                                                      </button>
                                                    </td>
                                                  </tr>
                                                  <?php endforeach; ?>
                                                  </tbody>
                                                </table>
                                              </div>
                                              <button class="btn btn-sm btn-outline-primary mb-4" type="button" id="addInvoiceItem">
                                                <i class="bi bi-plus-lg">
                                                </i>
                                                Add item
                                              </button>
                                              <div class="row g-3">
                                                <div class="col-lg-7">
                                                  <label class="form-label">
                                                    Notes
                                                  </label>
                                                  <textarea class="form-control" name="notes" rows="4" placeholder="Payment terms, delivery notes or internal reference">
                                                    <?= html_escape($edit['notes']??'') ?>
                                                    </textarea>
                                                  </div>
                                                  <div class="col-lg-5">
                                                    <div class="invoice-summary">
                                                      <div>
                                                        <span>
                                                          Subtotal
                                                        </span>
                                                        <strong id="sumSubtotal">
                                                          ₹0.00
                                                        </strong>
                                                      </div>
                                                      <div>
                                                        <span>
                                                          Tax
                                                        </span>
                                                        <strong id="sumTax">
                                                          ₹0.00
                                                        </strong>
                                                      </div>
                                                      <div class="align-items-center">
                                                        <span>
                                                          Discount
                                                        </span>
                                                        <div class="input-group input-group-sm summary-input">
                                                          <span class="input-group-text">
                                                            ₹
                                                          </span>
                                                          <input id="invoiceDiscount" class="form-control" name="discount" type="number" min="0" step="0.01" value="<?= html_escape($edit['discount']??'0') ?>
                                                          ">
                                                        </div>
                                                      </div>
                                                      <div class="grand-total">
                                                        <span>
                                                          Total
                                                        </span>
                                                        <strong id="sumTotal">
                                                          ₹0.00
                                                        </strong>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="mt-4">
                                                  <button class="btn btn-primary px-4">
                                                    Save invoice
                                                  </button>
                                                  <?php if($edit): ?>
                                                    <a class="btn btn-outline-secondary" href="<?= site_url($route) ?>
                                                      ">Cancel
                                                    </a>
                                                    <?php endif; ?>
                                                    </div>
                                                  </form>
                                                </div>
                                              </div>
                                              <div class="card">
                                                <div class="card-header">
                                                  Invoice records
                                                </div>
                                                <div class="table-responsive">
                                                  <table class="table align-middle mb-0">
                                                    <thead>
                                                      <tr>
                                                        <th>
                                                          Invoice
                                                        </th>
                                                        <th>
                                                          <?= html_escape($party_label) ?>
                                                          </th>
                                                          <th>
                                                            Date
                                                          </th>
                                                          <th>
                                                            Due
                                                          </th>
                                                          <th>
                                                            Total
                                                          </th>
                                                          <th>
                                                            Status
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
                                                              <a class="fw-semibold text-decoration-none" href="<?= site_url($route.'/view/'.$row['id']) ?>
                                                                ">
                                                                <?= html_escape($row['invoice_no']) ?>
                                                                </a>
                                                              </td>
                                                              <td>
                                                                <?= html_escape($row['party_name']??'-') ?>
                                                                </td>
                                                                <td>
                                                                  <?= html_escape($row['invoice_date']) ?>
                                                                  </td>
                                                                  <td>
                                                                    <?= html_escape($row['due_date']?:'-') ?>
                                                                    </td>
                                                                    <td>
                                                                      ₹
                                                                      <?= number_format((float)$row['total'],2) ?>
                                                                      </td>
                                                                      <td>
                                                                        <span class="status-badge status-<?= strtolower(str_replace(' ','-',$row['status'])) ?>
                                                                          ">
                                                                          <?= html_escape($row['status']) ?>
                                                                          </span>
                                                                        </td>
                                                                        <td class="text-end text-nowrap">
                                                                          <a class="btn btn-sm btn-outline-secondary" href="<?= site_url($route.'/view/'.$row['id']) ?>
                                                                            ">View
                                                                          </a>
                                                                          <a class="btn btn-sm btn-outline-primary" href="<?= site_url($route.'/'.$row['id']) ?>
                                                                            ">Edit
                                                                          </a>
                                                                          <form class="d-inline delete-form" method="post" action="<?= site_url($route.'/delete/'.$row['id']) ?>
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
                                                                                Your first
                                                                                <?= strtolower($title) ?>
                                                                                  will appear here.
                                                                                </td>
                                                                              </tr>
                                                                              <?php endif; ?>
                                                                              </tbody>
                                                                            </table>
                                                                          </div>
                                                                        </div>