<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata("user_id")) {
            redirect("login");
        }
    }
    public function currency()
    {
        $base = strtoupper(trim((string) $this->input->get("base", true)));
        $allowed = ["INR", "USD", "EUR", "GBP", "SGD", "AUD"];
        if (!in_array($base, $allowed, true)) {
            $base = "INR";
        }
        $quotes = array_values(
            array_filter($allowed, function ($code) use ($base) {
                return $code !== $base;
            })
        );
        $url =
            "https://api.frankfurter.dev/v2/rates?base=" .
            rawurlencode(strtolower($base)) .
            "&quotes=" .
            rawurlencode(strtolower(implode(",", $quotes)));
        $rates = [];
        $rateDate = "";
        $error = "";
        $raw = false;

        if (function_exists("curl_init")) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 4,
                CURLOPT_TIMEOUT => 6,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $raw = curl_exec($ch);
            $http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($http < 200 || $http >= 300) {
                $raw = false;
            }
        }
        if ($raw === false && ini_get("allow_url_fopen")) {
            $context = stream_context_create([
                "http" => ["timeout" => 6, "ignore_errors" => true],
                "ssl" => ["verify_peer" => true, "verify_peer_name" => true],
            ]);
            $raw = @file_get_contents($url, false, $context);
        }
        if ($raw !== false) {
            $json = json_decode($raw, true);
            if (is_array($json)) {
                foreach ($json as $row) {
                    if (isset($row["quote"], $row["rate"])) {
                        $rates[strtoupper($row["quote"])] =
                            (float) $row["rate"];
                        if (!$rateDate && !empty($row["date"])) {
                            $rateDate = $row["date"];
                        }
                    }
                };
            }
        }
        if (!$rates) {
            $error =
                "Exchange-rate service is temporarily unavailable. Please try again later.";
        }
        $data = [
            "title" => "Currency Exchange API",
            "base" => $base,
            "rates" => $rates,
            "rate_date" => $rateDate,
            "error" => $error,
        ];
        $this->load->view("layouts/header", $data);
        $this->load->view("api/currency", $data);
        $this->load->view("layouts/footer");
    }
}
