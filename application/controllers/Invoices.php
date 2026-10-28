<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Invoices extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata("user_id")) {
            redirect("login");
        }
        $this->load->model("App_model");
    }

    public function sales($id = 0)
    {
        $this->manage("sales", (int) $id);
    }

    public function purchases($id = 0)
    {
        $this->manage("purchase", (int) $id);
    }

    private function manage($type, $id = 0)
    {
        $isSales = $type === "sales";
        $table = $isSales ? "sales_invoices" : "purchase_invoices";
        $partyTable = $isSales ? "customers" : "suppliers";
        $partyKey = $isSales ? "customer_id" : "supplier_id";
        $title = $isSales ? "Sales Invoices" : "Purchase Invoices";
        $route = $isSales ? "sales-invoices" : "purchase-invoices";

        if ($this->input->method(true) === "POST") {
            $this->form_validation->set_rules(
                "invoice_no",
                "Invoice number",
                "required|trim|max_length[50]"
            );
            $this->form_validation->set_rules(
                $partyKey,
                $isSales ? "Customer" : "Supplier",
                "required|integer"
            );
            $this->form_validation->set_rules(
                "invoice_date",
                "Invoice date",
                "required"
            );
            $this->form_validation->set_rules(
                "discount",
                "Discount",
                "numeric|greater_than_equal_to[0]"
            );

            $itemDescriptions = (array) $this->input->post("item_description");
            $itemQty = (array) $this->input->post("item_quantity");
            $itemPrice = (array) $this->input->post("item_unit_price");
            $itemTax = (array) $this->input->post("item_tax_rate");
            $itemProduct = (array) $this->input->post("item_product_id");

            if (
                $this->form_validation->run() &&
                !empty(array_filter(array_map("trim", $itemDescriptions)))
            ) {
                $items = [];
                $subtotal = 0;
                $taxTotal = 0;

                foreach ($itemDescriptions as $i => $description) {
                    $description = trim($description);
                    if ($description === "") {
                        continue;
                    }

                    $qty = max(0.01, (float) ($itemQty[$i] ?? 1));
                    $price = max(0, (float) ($itemPrice[$i] ?? 0));
                    $taxRate = max(0, min(100, (float) ($itemTax[$i] ?? 0)));
                    $base = $qty * $price;
                    $taxAmount = $base * ($taxRate / 100);
                    $subtotal += $base;
                    $taxTotal += $taxAmount;

                    $items[] = [
                        "product_id" => !empty($itemProduct[$i])
                            ? (int) $itemProduct[$i]
                            : null,
                        "description" => $description,
                        "quantity" => round($qty, 2),
                        "unit_price" => round($price, 2),
                        "tax_rate" => round($taxRate, 2),
                        "line_total" => round($base + $taxAmount, 2),
                    ];
                }

                $discount = max(0, (float) $this->input->post("discount"));
                $discount = min($discount, $subtotal + $taxTotal);
                $total = max(0, $subtotal + $taxTotal - $discount);
                $dueDate = trim((string) $this->input->post("due_date", true));

                $payload = [
                    "invoice_no" => trim(
                        (string) $this->input->post("invoice_no", true)
                    ),
                    $partyKey => (int) $this->input->post($partyKey),
                    "invoice_date" => $this->input->post("invoice_date", true),
                    "due_date" => $dueDate !== "" ? $dueDate : null,
                    "subtotal" => round($subtotal, 2),
                    "discount" => round($discount, 2),
                    "tax" => round($taxTotal, 2),
                    "total" => round($total, 2),
                    "status" => "Pending",
                    "notes" => trim((string) $this->input->post("notes", true)),
                ];

                if ($id) {
                    $existing = $this->App_model->get_record($table, $id);
                    if (!$existing) {
                        show_404();
                    }
                    $payload["status"] = $existing["status"];
                }

                $savedId = $this->App_model->save_invoice(
                    $type,
                    $id,
                    $payload,
                    $items
                );
                if ($savedId) {
                    $this->App_model->refresh_invoice_status($type, $savedId);
                    $this->session->set_flashdata(
                        "message",
                        "Invoice saved successfully."
                    );
                    redirect($route . "/view/" . $savedId);
                }
                $this->session->set_flashdata(
                    "error",
                    "Unable to save invoice. Invoice number may already exist."
                );
            }
        }

        $data = [
            "title" => $title,
            "type" => $type,
            "route" => $route,
            "party_label" => $isSales ? "Customer" : "Supplier",
            "party_key" => $partyKey,
            "parties" => $this->App_model->get_records($partyTable),
            "products" => $this->App_model->get_records("products"),
            "rows" => $this->App_model->get_invoice_list($type),
            "edit" => $id ? $this->App_model->get_invoice($type, $id) : null,
        ];

        $this->load->view("layouts/header", $data);
        $this->load->view("invoices/index", $data);
        $this->load->view("layouts/footer");
    }

    public function view($type = "sales", $id = 0)
    {
        $type = $type === "purchase" ? "purchase" : "sales";
        $invoice = $this->App_model->get_invoice($type, (int) $id);
        if (!$invoice) {
            show_404();
        }

        $data = [
            "title" => "Invoice " . $invoice["invoice_no"],
            "type" => $type,
            "invoice" => $invoice,
            "payments" => $this->App_model->get_payments($type, (int) $id),
            "paid_amount" => $this->App_model->invoice_paid_amount(
                $type,
                (int) $id
            ),
        ];
        $data["balance"] = max(
            0,
            (float) $invoice["total"] - $data["paid_amount"]
        );

        $this->load->view("layouts/header", $data);
        $this->load->view("invoices/view", $data);
        $this->load->view("layouts/footer");
    }

    public function payment($type = "sales", $id = 0)
    {
        if ($this->input->method(true) !== "POST") {
            show_error("POST required", 405);
        }
        $type = $type === "purchase" ? "purchase" : "sales";
        $invoice = $this->App_model->get_invoice($type, (int) $id);
        if (!$invoice) {
            show_404();
        }

        $amount = max(0, (float) $this->input->post("amount"));
        $paid = $this->App_model->invoice_paid_amount($type, (int) $id);
        $balance = max(0, (float) $invoice["total"] - $paid);

        if ($amount <= 0 || $amount > $balance + 0.001) {
            $this->session->set_flashdata(
                "error",
                "Payment amount must be greater than zero and cannot exceed the invoice balance."
            );
        } else {
            $this->App_model->insert_record("payments", [
                "invoice_type" => $type,
                "invoice_id" => (int) $id,
                "payment_date" =>
                    $this->input->post("payment_date", true) ?: date("Y-m-d"),
                "amount" => round($amount, 2),
                "method" => trim((string) $this->input->post("method", true)),
                "reference_no" => trim(
                    (string) $this->input->post("reference_no", true)
                ),
                "notes" => trim((string) $this->input->post("notes", true)),
            ]);
            $this->App_model->refresh_invoice_status($type, (int) $id);
            $this->session->set_flashdata(
                "message",
                "Payment recorded successfully."
            );
        }
        redirect(
            ($type === "sales" ? "sales-invoices" : "purchase-invoices") .
                "/view/" .
                (int) $id
        );
    }

    public function delete($type = "sales", $id = 0)
    {
        if ($this->input->method(true) !== "POST") {
            show_error("POST required", 405);
        }
        $type = $type === "purchase" ? "purchase" : "sales";
        $this->App_model->delete_invoice($type, (int) $id);
        $this->session->set_flashdata(
            "message",
            "Invoice deleted successfully."
        );
        redirect($type === "sales" ? "sales-invoices" : "purchase-invoices");
    }
}
