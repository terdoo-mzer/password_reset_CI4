<?php

namespace App\Models;

use CodeIgniter\Model;

class ResetPasswordModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'resetpassword';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'token',
        'is_used',
        'expiry'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function inserData($user_id, $token, $is_used, $expiry) {
        $data = [
            'user_id' => $user_id,
            'token' => $token,
            'is_used' => $is_used,
            'expiry' => $expiry
        ];

        if($this->insert($data, $returnID = true)){
            return $this->getInsertID();
        };

        return false;
    }


    function findUser($id) {
        $user = $this->where('id', $id)->first();
        if($user) {
            return $user;
        }
        return false;
    }

    function update_is_used($id) {
        $data = [
            'is_used' => true
        ];
        if( $this->update($id,$data)) {
            return true;
        }
        return false;
    }
}
