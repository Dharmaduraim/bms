<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Expenses extends CI_Controller
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
                "title",
                "Title",
                "required|trim|max_length[150]"
            );
            $this->form_validation->set_rules(
                "expense_date",
                "Expense date",
                "required"
            );
            $this->form_validation->set_rules(
                "amount",
                "Amount",
                "required|numeric|greater_than[0]"
            );
            if ($this->form_validation->run()) {
                $budgetId = (int) $this->input->post("budget_id");
                $data = [
                    "budget_id" => $budgetId > 0 ? $budgetId : null,
                    "title" => trim($this->input->post("title", true)),
                    "expense_date" => $this->input->post("expense_date", true),
                    "amount" => round(
                        max(0, (float) $this->input->post("amount")),
                        2
                    ),
                    "category" => trim(
                        (string) $this->input->post("category", true)
                    ),
                    "notes" => trim((string) $this->input->post("notes", true)),
                ];
                $id
                    ? $this->App_model->update_record(
                        "expenses",
                        (int) $id,
                        $data
                    )
                    : $this->App_model->insert_record("expenses", $data);
                $this->session->set_flashdata(
                    "message",
                    "Expense saved successfully."
                );
                redirect("expenses");
            }
        }
        $data = [
            "title" => "Expense Tracking",
            "rows" => $this->App_model->get_expenses_with_budget(),
            "budgets" => $this->App_model->get_records("budgets"),
            "edit" => $id
                ? $this->App_model->get_record("expenses", (int) $id)
                : null,
        ];
        $this->load->view("layouts/header", $data);
        $this->load->view("expenses/index", $data);
        $this->load->view("layouts/footer");
    }
    public function delete($id = 0)
    {
        if ($this->input->method(true) !== "POST") {
            show_error("POST required", 405);
        }
        $this->App_model->delete_record("expenses", (int) $id);
        $this->session->set_flashdata(
            "message",
            "Expense deleted successfully."
        );
        redirect("expenses");
    }
}
