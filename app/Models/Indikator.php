<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    protected $fillable = [
        'nama_indikator',
        'slug',
        'deskripsi'
    ];

    public function kategoris()
{
    return $this->hasMany(Kategori::class, 'indikator_id');
}


    public function dataIndikators()
    {
        return $this->hasMany(DataIndikator::class);
    }
}