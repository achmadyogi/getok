% Fungsi mencari radius lengkung vertikal utama (N). Nilai N akan maksimum
% di ekuator dan minimum di kutub. Dibuat oleh Teguh Purnama Sidiq pada 
% tanggal 19 Juli 2007. Dikutip dari modul kuliah IHG 2 Departemen Teknik 
% Geodesi dan Geomatika ITB (by: Kosasih Prijatna dan Wedyanto Kuntjoro).

% Fungsi ini memerlukan input sumbu panjang ellipsoid (a), nilai
% penggepengannya (f), dan lintang (L) titik target. Outputnya radius 
% lengkung vertikal utama (N) dalam satuan meter.

function N = primverrad (a,f,L)

[e,dumy] = eksentrisitas (a,f);

N = a/sqrt(1-(e^2)*(sin(L))^2);