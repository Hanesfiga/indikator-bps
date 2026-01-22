class DataIndikator {
  final int id;
  final int indikatorId;
  final int kategoriId;
  final int tahun;
 

  DataIndikator({
    required this.id,
    required this.indikatorId,
    required this.kategoriId,
    required this.tahun,
    
    
  });

  factory DataIndikator.fromJson(Map<String, dynamic> json) {
    return DataIndikator(
      id: json['id'],
      indikatorId: json['indikator_id'],
      kategoriId: json['kategori_id'],
      tahun: json['tahun']
      
    );
  }
}
