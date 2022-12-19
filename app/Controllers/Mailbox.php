<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelMailbox;
use CodeIgniter\API\ResponseTrait;

class Mailbox extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $modelDns = new ModelMailbox();
        $data = $modelDns->findAll();
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
        $modelMail = new ModelMailbox();
        $data = $modelMail->orLike('id',$cari)->orLike('judul', $cari)->get()->getResult();
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
        $modelDns = new ModelMailbox();
        $id = $this->request->getPost("id");
        $judul = $this->request->getPost("judul");
        $pesan = $this->request->getPost("pesan");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'id' => [
                'rules' => 'is_unique[mailbox.id]',
                'label' => 'Nomor ID Mailbox',
                'error' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("id"),
            ];

            return $this->respond($response, 404);
        } else {
            $modelDns->insert([
                'id' => $id,
                'judul' => $judul,
                'pesan' => $pesan,
            ]);

            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];

            return $this->respond($response, 201);
        }
    }

    public function update($id = null)
    {
        $model = new ModelMailbox();
        $data = [
            'judul' => $this->request->getVar("judul"),
            'pesan' => $this->request->getVar("pesan"),
        ];
        $data = $this->request->getRawInput();
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan ID $id berhasil diperbaharui"
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $modelMail = new ModelMailbox();

        $cekData = $modelMail->find($id);
        if ($cekData) {
            $modelMail->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus "
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
