<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata("user_id")) {
            redirect("login");
        }
        $this->load->model("App_model");
    }
    public function index()
    {
        $data["title"] = "Reports & Analytics";
        $data["report"] = $this->App_model->report_summary();
        $data["monthly"] = $this->App_model->monthly_financials(6);
        $data["budgets"] = $this->App_model->get_budget_summary();
        $this->load->view("layouts/header", $data);
        $this->load->view("reports/index", $data);
        $this->load->view("layouts/footer");
    }
}
