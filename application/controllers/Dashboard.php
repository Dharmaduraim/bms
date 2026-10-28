<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Dashboard extends CI_Controller
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
        $data["title"] = "Dashboard";
        $data["stats"] = $this->App_model->dashboard_stats();
        $data["recent_sales"] = $this->App_model->recent_sales();
        $data["budgets"] = $this->App_model->get_budget_summary();
        $this->load->view("layouts/header", $data);
        $this->load->view("dashboard/index", $data);
        $this->load->view("layouts/footer");
    }
}
