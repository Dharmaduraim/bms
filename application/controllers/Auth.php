<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this
            ->load
            ->model('App_model', 'app_model');
    }
    public function index()
    {
        if ($this
            ->session
            ->userdata('user_id')) redirect('dashboard');
        $data['error'] = '';
        if ($this
            ->input
            ->method(true) === 'POST')
        {
            $this
                ->form_validation
                ->set_rules('email', 'Email', 'required|valid_email|trim');
            $this
                ->form_validation
                ->set_rules('password', 'Password', 'required');
            if ($this
                ->form_validation
                ->run())
            {
                $email = $this
                    ->input
                    ->post('email', true);
                $password = (string)$this
                    ->input
                    ->post('password');
                $user = $this
                    ->app_model
                    ->find_user($email);
                $valid = false;
                if ($user)
                {
                    if (password_get_info($user->password) ['algo']) $valid = password_verify($password, $user->password);
                    else $valid = hash_equals((string)$user->password, $password);
                }
                if ($valid)
                {
                    if (!password_get_info($user->password) ['algo']) $this
                        ->app_model
                        ->update_record('users', $user->id, array(
                        'password' => password_hash($password, PASSWORD_DEFAULT)
                    ));
                    $this
                        ->session
                        ->sess_regenerate(true);
                    $this
                        ->session
                        ->set_userdata(array(
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_role' => $user->role
                    ));
                    redirect('dashboard');
                    return;
                }
                $data['error'] = 'Invalid email or password.';
            }
            else $data['error'] = validation_errors();
        }
        $this
            ->load
            ->view('auth/login', $data);
    }
    public function logout()
    {
        $this
            ->session
            ->sess_destroy();
        redirect('login');
    }
}

