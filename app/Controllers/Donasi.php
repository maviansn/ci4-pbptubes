<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelDonasi;
use CodeIgniter\API\ResponseTrait;

class Donasi extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $modelDns = new ModelDonasi();
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
        $modelDns = new ModelDonasi();
        $data = $modelDns->orLike('iddonasi', $cari)->orLike('namadonasi', $cari)->get()->getResult();
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
        $modelDns = new ModelDonasi();
        $noid = $this->request->getPost("iddonasi");
        $nama = $this->request->getPost("namadonasi");
        $jenis = $this->request->getPost("jenisdonasi");
        $jumlah = $this->request->getPost("jumlahkumpul");
        $jumlahtarget = $this->request->getPost("targetdonasi");
        $tgldonasi = $this->request->getPost("tglakhir");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'iddonasi' => [
                'rules' => 'is_unique[donasi.iddonasi]',
                'label' => 'Nomor ID Donasi',
                'error' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("iddonasi"),
            ];

            return $this->respond($response, 404);
        } else {
            $modelDns->insert([
                'iddonasi' => $noid,
                'namadonasi' => $nama,
                'jenisdonasi' => $jenis,
                'jumlahkumpul' => $jumlah,
                'targetdonasi' => $jumlahtarget,
                'tglakhir' => $tgldonasi,
            ]);

            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];

            return $this->respond($response, 201);
        }
    }

    public function update($donasiid = null)
    {
        $model = new ModelDonasi();
        $data = [
            'namadonasi' => $this->request->getVar("namadonasi"),
            'jenisdonasi' => $this->request->getVar("jenisdonasi"),
            'tglakhir' => $this->request->getVar("tglakhir"),
        ];
        $data = $this->request->getRawInput();
        $model->update($donasiid, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan ID $donasiid berhasil diperbaharui"
        ];
        return $this->respond($response);
    }

    public function delete($donasiid = null)
    {
        $modelDns = new ModelDonasi();

        $cekData = $modelDns->find($donasiid);
        if ($cekData) {
            $modelDns->delete($donasiid);
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