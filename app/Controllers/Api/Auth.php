<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    // POST /api/register
    public function register()
    {
        $json = $this->request->getJSON();
        
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $model = new UserModel();
        $model->save([
            'username' => $json->username,
            'email'    => $json->email,
            'password' => password_hash($json->password, PASSWORD_BCRYPT),
        ]);

        return $this->respondCreated(['message' => 'Registrasi berhasil']);
    }

    // POST /api/login
    public function login()
    {
        $json = $this->request->getJSON();
        
        if (!$json) {
            return $this->fail("Data JSON tidak ditemukan");
        }

        $email    = $json->email ?? '';
        $password = $json->password ?? '';

        $model = new UserModel();
        $user  = $model->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Email atau Password salah');
        }

        // Ambil secret key dari .env
        $key = getenv('JWT_SECRET') ?: 'kunci_rahasia_default_123';
        
        $payload = [
            'iat'  => time(),
            'exp'  => time() + (60 * 60 * 24), // Berlaku 24 jam
            'uid'  => $user['id'],
            'email'=> $user['email']
        ];

        // Generate Token
        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond([
            'message' => 'Login Berhasil',
            'token'   => $token,
            'user'    => [
                'id'       => $user['id'],
                'username' => $user['username']
            ]
        ]);
    }
}