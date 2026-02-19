<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\Services;

class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $key    = getenv('JWT_SECRET') ?: 'kunci_rahasia_default_123';
        $header = $request->getServer('HTTP_AUTHORIZATION');

        if (!$header) {
            return Services::response()->setJSON(['message' => 'Token Required'])->setStatusCode(401);
        }

        // Ambil token dari format "Bearer <token>"
        $token = str_replace('Bearer ', '', $header);

        try {
            // VERSI TERBARU: Harus pakai new Key()
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
            // Masukkan data user ke request agar bisa dibaca Controller
            $request->user = $decoded;
        } catch (\Exception $e) {
            return Services::response()->setJSON([
                'message' => 'Invalid Token',
                'error'   => $e->getMessage()
            ])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}