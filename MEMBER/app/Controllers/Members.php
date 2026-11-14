<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Members extends BaseController
{
    public function index()
    {
        $model = new MemberModel();

        return view('members/index', [
            'members' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('members/form', [
            'member' => null,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create()
    {
        $model = new MemberModel();

        $className = trim((string) $this->request->getPost('class_name'));
        $phone = trim((string) $this->request->getPost('phone'));
        $status = trim((string) $this->request->getPost('status'));

        $data = [
            'member_code' => trim((string) $this->request->getPost('member_code')),
            'name'        => trim((string) $this->request->getPost('name')),
            'class_name'  => $className !== '' ? $className : null,
            'phone'       => $phone !== '' ? $phone : null,
            'status'      => $status !== '' ? $status : 'active',
        ];

        if ($model->insert($data) === false) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/members')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new MemberModel();
        $member = $model->find($id);

        if (! $member) {
            return redirect()->to('/members')
                ->with('error', 'Data anggota tidak ditemukan.');
        }

        return view('members/form', [
            'member' => $member,
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update($id)
    {
        $model = new MemberModel();
        $member = $model->find($id);

        if (! $member) {
            return redirect()->to('/members')
                ->with('error', 'Data anggota tidak ditemukan.');
        }

        $model->setRulesForUpdate((int) $id);

        $className = trim((string) $this->request->getPost('class_name'));
        $phone = trim((string) $this->request->getPost('phone'));

        $data = [
            'member_code' => trim((string) $this->request->getPost('member_code')),
            'name'        => trim((string) $this->request->getPost('name')),
            'class_name'  => $className !== '' ? $className : null,
            'phone'       => $phone !== '' ? $phone : null,
            'status'      => $this->request->getPost('status'),
        ];

        if ($model->update($id, $data) === false) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/members')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new MemberModel();
        $member = $model->find($id);

        if (! $member) {
            return redirect()->to('/members')
                ->with('error', 'Data anggota tidak ditemukan.');
        }

        if ($member['status'] === 'inactive') {
            return redirect()->to('/members')
                ->with('error', 'Anggota sudah berstatus tidak aktif.');
        }

        if ($model->update($id, ['status' => 'inactive']) === false) {
            return redirect()->to('/members')
                ->with('error', 'Anggota gagal dinonaktifkan.');
        }

        return redirect()->to('/members')
            ->with('success', 'Anggota berhasil dinonaktifkan.');
    }
}
