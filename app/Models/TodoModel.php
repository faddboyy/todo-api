<?php

namespace App\Models;

use CodeIgniter\Model;

class TodoModel extends Model
{
    protected $table            = 'todos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'title', 'description', 'is_completed'];
    protected $useTimestamps    = true;

    // Fungsi bantuan untuk mengambil todo berdasarkan user tertentu
    public function getTodoByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }
}