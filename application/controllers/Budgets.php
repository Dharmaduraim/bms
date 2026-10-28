<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Budgets extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata("user_id")) {
            redirect("login");
        }
        $this->load->model("App_model");
    }
    public function index($id = 0)
    {
        if ($this->input->method(true) === "POST") {
            $this->form_validation->set_rules(
                "name",
                "Budget name",
                "required|trim|max_length[150]"
            );
            $this->form_validation->set_rules(
                "amount",
                "Amount",
                "required|numeric|greater_than_equal_to[0]"
            );
            $this->form_validation->set_rules(
                "period_start",
                "Start date",
                "required"
            );
            $this->form_validation->set_rules(
                "period_end",
                "End date",
                "required"
            );
            if ($this->form_validation->run()) {
                $start = $this->input->post("period_start", true);
                $end = $this->input->post("period_end", true);
                if ($end < $start) {
                    $this->session->set_flashdata(
                        "error",
                        "End date cannot be earlier than start date."
                    );
                    redirect("budgets" . ($id ? "/" . $id : ""));
                }
                $data = [
                    "name" => trim($this->input->post("name", true)),
                    "period_type" => in_array(
                        $this->input->post("period_type"),
                        ["monthly", "yearly"],
                        true
                    )
                        ? $this->input->post("period_type")
                        : "monthly",
                    "period_start" => $start,
                    "period_end" => $end,
                    "amount" => round(
                        max(0, (float) $this->input->post("amount")),
                        2
                    ),
                ];
                $id
                    ? $this->App_model->update_record(
                        "budgets",
                        (int) $id,
                        $data
                    )
                    : $this->App_model->insert_record("budgets", $data);
                $this->session->set_flashdata(
                    "message",
                    "Budget saved successfully."
                );
                redirect("budgets");
            }
        }
        $data = [
            "title" => "Budget Management",
            "rows" => $this->App_model->get_budget_summary(),
            "edit" => $id
                ? $this->App_model->get_record("budgets", (int) $id)
                : null,
        ];
        $this->load->view("layouts/header", $data);
        $this->load->view("budgets/index", $data);
        $this->load->view("layouts/footer");
    }
    public function delete($id = 0)
    {
        if ($this->input->method(true) !== "POST") {
            show_error("POST required", 405);
        }
        $this->App_model->delete_record("budgets", (int) $id);
        $this->session->set_flashdata(
            "message",
            "Budget deleted successfully."
        );
        redirect("budgets");
    }
}
