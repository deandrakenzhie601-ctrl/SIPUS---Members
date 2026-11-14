<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$member = $member ?? null;
$errors = $errors ?? [];
$isEdit = $member !== null;
?>

<div class="mb-4">
    <h3><?= $isEdit ? 'Edit Anggota' : 'Tambah Anggota' ?></h3>
    <p class="text-muted">
        <?= $isEdit
            ? 'Perbarui informasi anggota perpustakaan.'
            : 'Masukkan informasi anggota baru.' ?>
    </p>
</div>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Periksa kembali data berikut:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $isEdit
            ? site_url('members/update/' . $member['id'])
            : site_url('members/create') ?>"
              method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="member_code" class="form-label">Kode Anggota</label>
                <input type="text" id="member_code" name="member_code"
                       class="form-control" maxlength="30"
                       value="<?= esc(old('member_code', $member['member_code'] ?? '')) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Anggota</label>
                <input type="text" id="name" name="name" class="form-control"
                       maxlength="150"
                       value="<?= esc(old('name', $member['name'] ?? '')) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="class_name" class="form-label">Kelas</label>
                <input type="text" id="class_name" name="class_name"
                       class="form-control" maxlength="50"
                       value="<?= esc(old('class_name', $member['class_name'] ?? '')) ?>"
                       placeholder="Contoh: X IPA 1">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control"
                       maxlength="30"
                       value="<?= esc(old('phone', $member['phone'] ?? '')) ?>"
                       placeholder="Contoh: 081234567890">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status Anggota</label>
                <?php $status = old('status', $member['status'] ?? 'active') ?>
                <select id="status" name="status" class="form-select" required>
                    <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>
                        Aktif
                    </option>
                    <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Anggota' ?>
            </button>
            <a href="<?= site_url('members') ?>" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
