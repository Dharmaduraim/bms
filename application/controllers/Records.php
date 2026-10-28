<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Records extends CI_Controller
{
    private $types = [
        "customers" => [
            "table" => "customers",
            "title" => "Customers",
            "fields" => [
                "name" => "Name",
                "email" => "Email",
                "phone" => "Phone",
                "address" => "Address",
            ],
        ],
        "suppliers" => [
            "table" => "suppliers",
            "title" => "Suppliers",
            "fields" => [
                "name" => "Name",
                "email" => "Email",
                "phone" => "Phone",
                "address" => "Address",
            ],
        ],
        "products" => [
            "table" => "products",
            "title" => "Products / Services",
            "fields" => [
                "name" => "Name",
                "sku" => "SKU",
                "unit_price" => "Unit price",
                "tax_rate" => "Tax rate",
            ],
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata("user_id")) {
            redirect("login");
        }
        $this->load->model("App_model");
    }

    public function _remap($method, $params = [])
    {
        if ($method === "delete") {
            $this->delete($params[0] ?? "", (int) ($params[1] ?? 0));
            return;
        }
        if (!isset($this->types[$method])) {
            show_404();
        }
        $this->manage($method, (int) ($params[0] ?? 0));
    }

    private function manage($type, $id = 0)
    {
        $cfg = $this->types[$type];
        if ($this->input->method(true) === "POST") {
            $this->form_validation->set_rules(
                "name",
                "Name",
                "required|trim|max_length[150]"
            );
            if (
                in_array($type, ["customers", "suppliers"], true) &&
                trim((string) $this->input->post("email")) !== ""
            ) {
                $this->form_validation->set_rules(
                    "email",
                    "Email",
                    "valid_email|trim|max_length[150]"
                );
            }
            if ($type === "products") {
                $this->form_validation->set_rules(
                    "unit_price",
                    "Unit price",
                    "required|numeric|greater_than_equal_to[0]"
                );
                $this->form_validation->set_rules(
                    "tax_rate",
                    "Tax rate",
                    "numeric|greater_than_equal_to[0]|less_than_equal_to[100]"
                );
            }
            if ($this->form_validation->run()) {
                $payload = [];
                foreach ($cfg["fields"] as $field => $label) {
                    $value = $this->input->post($field, true);
                    if ($value !== null) {
                        $payload[$field] = is_string($value)
                            ? trim($value)
                            : $value;
                    }
                }
                if ($type === "products") {
                    $payload["unit_price"] = round(
                        max(0, (float) $payload["unit_price"]),
                        2
                    );
                    $payload["tax_rate"] = round(
                        max(0, min(100, (float) $payload["tax_rate"])),
                        2
                    );
                }
                $id
                    ? $this->App_model->update_record(
                        $cfg["table"],
                        $id,
                        $payload
                    )
                    : $this->App_model->insert_record($cfg["table"], $payload);
                $this->session->set_flashdata(
                    "message",
                    rtrim($cfg["title"], "s") . " saved successfully."
                );
                redirect($type);
            }
        }
        $data = [
            "title" => $cfg["title"],
            "type" => $type,
            "fields" => $cfg["fields"],
            "rows" => $this->App_model->get_records($cfg["table"]),
            "edit" => $id
                ? $this->App_model->get_record($cfg["table"], $id)
                : null,
        ];
        if ($id && !$data["edit"]) {
            show_404();
        }
        $this->load->view("layouts/header", $data);
        $this->load->view("records/index", $data);
        $this->load->view("layouts/footer");
    }

    public function delete($type = "", $id = 0)
    {
        if (!isset($this->types[$type])) {
            show_404();
        }
        if ($this->input->method(true) !== "POST") {
            show_error("POST required", 405);
        }
        if (
            !$this->App_model->delete_record($this->types[$type]["table"], $id)
        ) {
            $this->session->set_flashdata(
                "error",
                "This record is in use and cannot be deleted."
            );
        } else {
            $this->session->set_flashdata(
                "message",
                "Record deleted successfully."
            );
        }
        redirect($type);
    }
}
