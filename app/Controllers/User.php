<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $modelMhs = new ModelUser();
        $data = $modelMhs->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];
        return $this->respond($response, 200);
    }

    public function show($cari = null)
    {
        $modelUser = new ModelUser();
        $data = $modelUser->orLike('userid', $cari)->orLike('username', $cari)->get()->getResult();
        if (count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        } else if (count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        } else {
            return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
        }
    }

    public function create()
    {
        $modelUser = new ModelUser();
        $noid = $this->request->getPost("userid");
        $nama = $this->request->getPost("username");
        $passuser = $this->request->getPost("password");
        $emailuser = $this->request->getPost("email");
        $usertgllahir = $this->request->getPost("tgllahir");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'iduser' => [
                'rules' => 'is_unique[user.userid]',
                'label' => 'Nomor ID User',
                'error' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("userid"),
            ];

            return $this->respond($response, 404);
        } else {
            $modelUser->insert([
                'userid' => $noid,
                'username' => $nama,
                'password' => $passuser,
                'email' => $emailuser,
                'tgllahir' => $usertgllahir,
            ]);

            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];

            return $this->respond($response, 201);
        }
    }

    public function update($userid = null)
    {
        $model = new ModelUser();
        $data = [
            'username' => $this->request->getVar("username"),
            'password' => $this->request->getVar("password"),
            'email' => $this->request->getVar("email"),
            'tgllahir' => $this->request->getVar("tgllahiir"),
        ];
        $data = $this->request->getRawInput();
        $model->update($userid, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan NIM $userid berhasil dibaharukan"
        ];
        return $this->respond($response);
    }

    public function delete($userid = null)
    {
        $modelUser = new ModelUser();

        $cekData = $modelUser->find($userid);
        if ($cekData) {
            $modelUser->delete($userid);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}