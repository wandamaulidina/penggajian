<!-- <div class="container">
<div class="row">
    <div class="col">
        <h2>Detail Komponen Gaji</h2>
        <div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title"><?= $buku['judul']; ?></h5>
        <p class="card-text"><b>Kelas : </b><?= $buku['kelas']; ?></p>
        <p class="card-text"><small class="text-muted"><b>Penulis : </b><?= $buku['penulis']; ?></small></p>
        <p class="card-text"><small class="text-muted"><b>Penerbit : </b><?= $buku['penerbit']; ?></small></p>

        <a href="/Komponen/edit/<?= $buku['slug']; ?>" class="btn btn-warning">Edit</a>
        <form action="/Komponen/<?= $buku['id']; ?>" method="post" class="d-inline">
        <?= csrf_field(); ?>
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda Yakin Ingin Dihapus?');">Delete</button>
</form>
<br><br>
        <a href="/Komponen" class="btn btn-primary">Kembali ke daftar buku </a>

      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>


 -->