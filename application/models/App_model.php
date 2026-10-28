<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model
{
    public function find_user($email)
    {
        return $this->db->where('email', $email)->get('users')->row();
    }

    public function dashboard_stats()
    {
        $sales = (float) ($this->db->select_sum('total')->get('sales_invoices')->row()->total ?? 0);
        $purchases = (float) ($this->db->select_sum('total')->get('purchase_invoices')->row()->total ?? 0);
        $expenses = (float) ($this->db->select_sum('amount')->get('expenses')->row()->amount ?? 0);
        $budgets = (float) ($this->db->select_sum('amount')->get('budgets')->row()->amount ?? 0);
        $salesPaid = (float) ($this->db->select_sum('amount')->where('invoice_type', 'sales')->get('payments')->row()->amount ?? 0);
        $purchasePaid = (float) ($this->db->select_sum('amount')->where('invoice_type', 'purchase')->get('payments')->row()->amount ?? 0);
        return array(
            'sales' => $sales,
            'purchases' => $purchases,
            'expenses' => $expenses,
            'budgets' => $budgets,
            'sales_paid' => $salesPaid,
            'purchase_paid' => $purchasePaid,
            'profit' => $sales - $purchases - $expenses,
            'budget_remaining' => $budgets - $expenses,
        );
    }

    public function recent_sales()
    {
        return $this->db->select('sales_invoices.*, customers.name AS party_name')
            ->from('sales_invoices')
            ->join('customers', 'customers.id=sales_invoices.customer_id', 'left')
            ->order_by('sales_invoices.id', 'DESC')->limit(5)->get()->result();
    }

    public function get_records($table)
    {
        return $this->db->order_by('id', 'DESC')->get($table)->result_array();
    }

    public function get_record($table, $id)
    {
        return $this->db->get_where($table, array('id' => $id))->row_array();
    }

    public function insert_record($table, $data)
    {
        if (!$data) return FALSE;
        if (!$this->db->insert($table, $data)) return FALSE;
        return $this->db->insert_id() ?: TRUE;
    }

    public function update_record($table, $id, $data)
    {
        if (!$data) return FALSE;
        return $this->db->where('id', $id)->update($table, $data);
    }

    public function delete_record($table, $id)
    {
        return $this->db->where('id', $id)->delete($table);
    }

    public function get_invoice_list($type)
    {
        $sales = $type === 'sales';
        $table = $sales ? 'sales_invoices' : 'purchase_invoices';
        $partyTable = $sales ? 'customers' : 'suppliers';
        $partyKey = $sales ? 'customer_id' : 'supplier_id';

        return $this->db->select($table . '.*, ' . $partyTable . '.name AS party_name')
            ->from($table)
            ->join($partyTable, $partyTable . '.id=' . $table . '.' . $partyKey, 'left')
            ->order_by($table . '.id', 'DESC')
            ->get()->result_array();
    }

    public function get_invoice($type, $id)
    {
        $sales = $type === 'sales';
        $table = $sales ? 'sales_invoices' : 'purchase_invoices';
        $partyTable = $sales ? 'customers' : 'suppliers';
        $partyKey = $sales ? 'customer_id' : 'supplier_id';
        $row = $this->db->select($table . '.*, ' . $partyTable . '.name AS party_name, ' . $partyTable . '.email AS party_email, ' . $partyTable . '.phone AS party_phone, ' . $partyTable . '.address AS party_address')
            ->from($table)
            ->join($partyTable, $partyTable . '.id=' . $table . '.' . $partyKey, 'left')
            ->where($table . '.id', $id)->get()->row_array();
        if (!$row) return NULL;
        $row['items'] = $this->db->where('invoice_type', $type)->where('invoice_id', $id)->order_by('id', 'ASC')->get('invoice_items')->result_array();
        return $row;
    }

    public function save_invoice($type, $id, $payload, $items)
    {
        $table = $type === 'sales' ? 'sales_invoices' : 'purchase_invoices';
        $this->db->trans_start();
        if ($id) {
            $this->db->where('id', $id)->update($table, $payload);
            $invoiceId = $id;
            $this->db->where('invoice_type', $type)->where('invoice_id', $invoiceId)->delete('invoice_items');
        } else {
            $this->db->insert($table, $payload);
            $invoiceId = (int) $this->db->insert_id();
        }
        foreach ($items as $item) {
            $item['invoice_type'] = $type;
            $item['invoice_id'] = $invoiceId;
            $this->db->insert('invoice_items', $item);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $invoiceId : FALSE;
    }

    public function delete_invoice($type, $id)
    {
        $table = $type === 'sales' ? 'sales_invoices' : 'purchase_invoices';
        $this->db->trans_start();
        $this->db->where('invoice_type', $type)->where('invoice_id', $id)->delete('invoice_items');
        $this->db->where('invoice_type', $type)->where('invoice_id', $id)->delete('payments');
        $this->db->where('id', $id)->delete($table);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_payments($type, $invoiceId)
    {
        return $this->db->where('invoice_type', $type)->where('invoice_id', $invoiceId)->order_by('payment_date', 'DESC')->order_by('id', 'DESC')->get('payments')->result_array();
    }

    public function invoice_paid_amount($type, $invoiceId)
    {
        $row = $this->db->select_sum('amount')->where('invoice_type', $type)->where('invoice_id', $invoiceId)->get('payments')->row();
        return (float) ($row->amount ?? 0);
    }

    public function refresh_invoice_status($type, $invoiceId)
    {
        $table = $type === 'sales' ? 'sales_invoices' : 'purchase_invoices';
        $invoice = $this->get_record($table, $invoiceId);
        if (!$invoice) return FALSE;
        $paid = $this->invoice_paid_amount($type, $invoiceId);
        $total = (float) $invoice['total'];
        if ($total > 0 && $paid >= $total - 0.005) $status = 'Paid';
        elseif ($paid > 0) $status = 'Partially Paid';
        elseif (!empty($invoice['due_date']) && $invoice['due_date'] < date('Y-m-d')) $status = 'Overdue';
        else $status = 'Pending';
        return $this->db->where('id', $invoiceId)->update($table, array('status' => $status));
    }

    public function get_budget_summary()
    {
        return $this->db->select('budgets.*, COALESCE(SUM(expenses.amount),0) AS spent')
            ->from('budgets')->join('expenses', 'expenses.budget_id=budgets.id', 'left')
            ->group_by('budgets.id')->order_by('budgets.period_start', 'DESC')->get()->result_array();
    }

    public function get_expenses_with_budget()
    {
        return $this->db->select('expenses.*, budgets.name AS budget_name')->from('expenses')
            ->join('budgets', 'budgets.id=expenses.budget_id', 'left')
            ->order_by('expenses.expense_date', 'DESC')->order_by('expenses.id', 'DESC')->get()->result_array();
    }

    public function report_summary()
    {
        $sales = (float) ($this->db->select_sum('total')->get('sales_invoices')->row()->total ?? 0);
        $purchases = (float) ($this->db->select_sum('total')->get('purchase_invoices')->row()->total ?? 0);
        $expenses = (float) ($this->db->select_sum('amount')->get('expenses')->row()->amount ?? 0);
        $paidSales = (float) ($this->db->select_sum('amount')->where('invoice_type', 'sales')->get('payments')->row()->amount ?? 0);
        $pendingSales = max(0, $sales - $paidSales);
        return array('sales'=>$sales,'purchases'=>$purchases,'expenses'=>$expenses,'paid_sales'=>$paidSales,'pending_sales'=>$pendingSales,'net'=>$sales-$purchases-$expenses);
    }

    public function monthly_financials($months = 6)
    {
        $result = array();
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = date('Y-m', strtotime('-' . $i . ' months'));
            $start = $month . '-01';
            $end = date('Y-m-t', strtotime($start));
            $sales = (float) ($this->db->select_sum('total')->where('invoice_date >=', $start)->where('invoice_date <=', $end)->get('sales_invoices')->row()->total ?? 0);
            $purchases = (float) ($this->db->select_sum('total')->where('invoice_date >=', $start)->where('invoice_date <=', $end)->get('purchase_invoices')->row()->total ?? 0);
            $expenses = (float) ($this->db->select_sum('amount')->where('expense_date >=', $start)->where('expense_date <=', $end)->get('expenses')->row()->amount ?? 0);
            $result[] = array('label'=>date('M Y', strtotime($start)),'sales'=>$sales,'purchases'=>$purchases,'expenses'=>$expenses);
        }
        return $result;
    }
}
