<?php

namespace App\Models;

use CodeIgniter\Model;

class FormModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'registration';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'name',
        'email',
        'password'
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



    public function insertUser($uuid, $name, $email, $password)
    {
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'user_id' => $uuid,
            'name' => $name,
            'email' => $email,
            'password' => $encryptedPassword
        ];

        return $this->insert($data);
    }

    public function getUser($email, $password=null)
    {
        // $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

        // return $this->insert($data);
        $user = $this->where('email', $email)->first();
        $decryptPwd = password_verify($password, $user['password']);
   
        if ($decryptPwd) {
            return $user;
        }
        return false;
    }

    public function findUser($email) {
        $user = $this->where('email', $email)->first();
        if($user) {
            return $user;
        }
        return false;
    }


    function updatePassword($user_id,$pwd)
    {
        $encryptedPassword = password_hash($pwd, PASSWORD_DEFAULT);
        $data = [
            'password' => $encryptedPassword
        ];
        // $updateUserPwd = $this->where('user_id', $user_id)->update($data);

        if($this->update($user_id,$data)) {
            return true;
        }
        return false;
    }
}
