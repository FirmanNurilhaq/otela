<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        if (session()->get('user')) {
            return redirect()->to(base_url('beranda'));
        }
        return view('register');
    }

    public function saveRegister()
    {
        // 1. Aturan Validasi (Sangat penting untuk ditambahkan)
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'email'        => 'required|valid_email|is_unique[user.email]',
            'no_telp'      => 'required|numeric|min_length[10]',
            'password'     => 'required|min_length[6]',
            'konfirmasi_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Siapkan Data dan Token
        $userModel = new UserModel();
        $token = bin2hex(random_bytes(32)); // Buat token acak yang aman
        $tokenExpires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token berlaku 1 jam

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'password'     => md5($this->request->getPost('password')),
            'no_telp'      => $this->request->getPost('no_telp'),
            'role'         => 'pelanggan',
            'status'       => 'pending', // Status awal adalah pending
            'verification_token' => $token,
            'token_expires_at'   => $tokenExpires
        ];

        // 3. Simpan data pengguna ke database
        if ($userModel->insert($data)) {
            // 4. Jika berhasil, kirim email verifikasi (bukan welcome email)
            $this->_sendVerificationEmail($data);

            return redirect()->to(base_url('login'))->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi akun.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Registrasi gagal, terjadi kesalahan sistem.');
        }
    }

    private function _sendVerificationEmail(array $userData)
    {
        $apiKey = getenv('BREVO_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'Brevo API Key is not set in .env file.');
            return;
        }

        // Buat link verifikasi yang akan dikirim
        $verificationLink = site_url('auth/verifikasi/' . $userData['verification_token']);

        // Data yang akan dikirim ke view email
        $emailViewData = [
            'nama_lengkap' => $userData['nama_lengkap'],
            'link_verifikasi' => $verificationLink
        ];

        // Render view email menjadi string HTML
        $htmlContent = view('emails/template_verifikasi', $emailViewData);

        $url = "https://api.brevo.com/v3/smtp/email";
        $headers = [
            "accept"       => "application/json",
            "api-key"      => $apiKey,
            "content-type" => "application/json",
        ];

        $emailData = [
            "sender"      => ["name" => "Toko Otela", "email" => "renaamelianti8@gmail.com"],
            "to"          => [["email" => $userData['email'], "name" => $userData['nama_lengkap']]],
            "subject"     => "Aktivasi Akun Otela Anda",
            "htmlContent" => $htmlContent,
        ];

        try {
            $client = \Config\Services::curlrequest();
            $client->request('POST', $url, ['headers' => $headers, 'json' => $emailData, 'timeout' => 5]);
        } catch (\Exception $e) {
            log_message('error', '[BREVO] Verification email failed for ' . $userData['email'] . ': ' . $e->getMessage());
        }
    }

    public function verifikasiEmail($token)
    {
        if (empty($token)) {
            return redirect()->to('/login')->with('error', 'Token verifikasi tidak valid.');
        }

        $userModel = new UserModel();

        // 1. Cari user berdasarkan token
        $user = $userModel->where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token verifikasi tidak ditemukan.');
        }

        // 2. Cek apakah token sudah kedaluwarsa
        if (strtotime($user['token_expires_at']) < time()) {
            // Opsional: Hapus user agar bisa daftar lagi, atau berikan opsi kirim ulang token
            $userModel->delete($user['id_user']);
            return redirect()->to('/register')->with('error', 'Token verifikasi sudah kedaluwarsa. Silakan daftar ulang.');
        }

        // 3. Jika semua valid, update status user menjadi 'aktif'
        $dataUpdate = [
            'status' => 'aktif',
            'verification_token' => null, // Kosongkan token setelah digunakan
            'token_expires_at' => null
        ];

        if ($userModel->update($user['id_user'], $dataUpdate)) {
            return redirect()->to('/login')->with('success', 'Verifikasi berhasil! Akun Anda telah diaktifkan. Silakan login.');
        } else {
            return redirect()->to('/login')->with('error', 'Gagal mengaktifkan akun. Silakan hubungi support.');
        }
    }

    public function login()
    {
        if (session()->get('user')) {
            return redirect()->to(base_url('beranda'));
        }
        return view('login');
    }

    public function cekLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        // Mengenkripsi password inputan dengan md5 untuk dicocokkan dengan database
        $password = md5($this->request->getPost('password'));

        // Mencari user berdasarkan email dan password md5
        $user = $userModel->where('email', $email)->where('password', $password)->first();

        // Cek jika user ditemukan
        if ($user) {

            // Tambahan: cek status user
            if ($user['status'] !== 'aktif') {
                return redirect()->to(base_url('login'))->with('error', 'Akun Anda belum aktif. Silakan cek email untuk verifikasi.');
            }

            session()->set('user', $user);
            session()->setFlashdata('show_welcome_modal', true);
            return redirect()->to(base_url('beranda'));
        } else {
            return redirect()->to(base_url('login'))->with('error', 'Login gagal, email atau password salah.');
        }
    }

    public function beranda()
    {
        if (!session()->get('user')) {
            return redirect()->to(base_url('login'));
        }
        // Logika untuk beranda bisa ditambahkan di sini
        return view('beranda');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
