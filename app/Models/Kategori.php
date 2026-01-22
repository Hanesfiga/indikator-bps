<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'indikator_id',
        'nama_kategori',
        'deskripsi',
        'tahun',
        'gambar', // ditambahkan
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    public function dataIndikators()
    {
        return $this->hasMany(DataIndikator::class);
    }
}
