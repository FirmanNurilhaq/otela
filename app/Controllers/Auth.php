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
        $userModel = new UserModel();

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            // Menggunakan md5() sesuai permintaan
            'password'     => md5($this->request->getPost('password')),
            'no_telp'      => $this->request->getPost('no_telp'),
            'role'         => 'pelanggan', // otomatis pelanggan
        ];

        // Simpan data pengguna ke database
        if ($userModel->insert($data)) {
            // Jika berhasil, kirim email selamat datang
            $this->_sendWelcomeEmail($data);
        }

        return redirect()->to(base_url('login'))->with('message', 'Registrasi berhasil, silakan login.');
    }
    private function _sendWelcomeEmail(array $userData)
    {
        // Pastikan API Key Brevo sudah ada di file .env
        $apiKey = getenv('BREVO_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'Brevo API Key is not set in .env file.');
            return; // Hentikan proses jika key tidak ada
        }

        $url = "https://api.brevo.com/v3/smtp/email";
        $headers = [
            "accept"       => "application/json",
            "api-key"      => $apiKey,
            "content-type" => "application/json",
        ];

        // Konten email yang akan dikirim
        $htmlContent = "<html><body>
            <h1>Selamat Datang di Otela!</h1>
            <p>Halo <strong>" . esc($userData['nama_lengkap']) . "</strong>,</p>
            <p>Terima kasih telah bergabung dengan kami. Akun Anda telah berhasil dibuat.</p>
            <p>Nikmati berbagai pilihan keripik terenak langsung dari produsennya. Jelajahi toko kami dan temukan rasa favorit Anda!</p>
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
            <p>Selamat menikmati,<br><strong>Tim Otela</strong></p>
        </body></html>";

        // Struktur data yang akan dikirim ke API Brevo
        $emailData = [
            "sender"      => ["name" => "Toko Otela", "email" => "renaamelianti8@gmail.com"],
            "to"          => [["email" => $userData['email'], "name" => $userData['nama_lengkap']]],
            "subject"     => "Selamat Datang di Otela!",
            "htmlContent" => $htmlContent,
        ];

        try {
            $client = \Config\Services::curlrequest();
            // Kirim request ke API Brevo
            $client->request('POST', $url, ['headers' => $headers, 'json' => $emailData, 'timeout' => 5]);
        } catch (\Exception $e) {
            // Catat error jika pengiriman email gagal
            log_message('error', '[BREVO] Welcome email failed for ' . $userData['email'] . ': ' . $e->getMessage());
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
        $password = md5($this->request->getPost('password'));

        $user = $userModel->where('email', $email)->where('password', $password)->first();

        if ($user) {
            session()->set('user', $user);
            
            session()->setFlashdata('show_welcome_modal', true);

            return redirect()->to(base_url('beranda'));
        } else {
            return redirect()->to(base_url('login'))->with('message', 'Login gagal, periksa email dan password.');
        }
    }

    public function beranda()
    {
        if (!session()->get('user')) {
            return redirect()->to(base_url('login'));
        }
        return view('beranda');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
