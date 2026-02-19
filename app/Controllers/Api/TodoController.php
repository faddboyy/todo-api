<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TodoModel;
use CodeIgniter\API\ResponseTrait;

class TodoController extends BaseController
{
    use ResponseTrait;

    protected $todoModel;

    public function __construct()
    {
        $this->todoModel = new TodoModel();
    }

    // GET /api/todos
    public function index()
    {
        // $this->request->user didapat dari decoded token di JwtFilter
        $userData = $this->request->user; 
        
        $todos = $this->todoModel->where('user_id', $userData->uid)->findAll();

        return $this->respond([
            'status' => 200,
            'data'   => $todos
        ]);
    }

    // POST /api/todos
    public function create()
    {
        $userData = $this->request->user;
        $json = $this->request->getJSON(); // Ambil JSON dari Astro

        if (!$json || !isset($json->title)) {
            return $this->fail("Judul tugas wajib diisi");
        }

        $data = [
            'user_id'      => $userData->uid,
            'title'        => $json->title,
            'description'  => $json->description ?? '',
            'is_completed' => 0
        ];

        if ($this->todoModel->insert($data)) {
            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Todo berhasil ditambahkan'
            ]);
        }

        return $this->fail("Gagal menyimpan data");
    }

    // PUT /api/todos/(:id)
    public function update($id = null)
    {
        $userData = $this->request->user;
        $json = $this->request->getJSON(); // Gunakan getJSON untuk update

        $todo = $this->todoModel->where(['id' => $id, 'user_id' => $userData->uid])->first();

        if (!$todo) return $this->failNotFound('Data tidak ditemukan atau akses ditolak');

        $data = [
            'title'        => $json->title ?? $todo['title'],
            'description'  => $json->description ?? $todo['description'],
            'is_completed' => $json->is_completed ?? $todo['is_completed'],
        ];

        $this->todoModel->update($id, $data);

        return $this->respond([
            'status'  => 200,
            'message' => 'Todo berhasil diperbarui'
        ]);
    }

    // DELETE /api/todos/(:id)
    public function delete($id = null)
    {
        $userData = $this->request->user;
        
        // Pastikan todo yang dihapus adalah milik user yang login
        $todo = $this->todoModel->where(['id' => $id, 'user_id' => $userData->uid])->first();

        if (!$todo) return $this->failNotFound('Data tidak ditemukan');

        $this->todoModel->delete($id);

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Todo berhasil dihapus'
        ]);
    }
}