<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataIndikator extends Model
{
    protected $fillable = [
        'indikator_id',
        'kategori_id',
        'tahun',
        'nilai',
        'satuan',
        'deskripsi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id', 'id');
    }
}
