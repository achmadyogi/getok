% Fungsi konversi geodetik2xyz (LBH2XYZ). Dibuat oleh Teguh Purnama Sidiq, tanggal 19 Juli 2007. Di
% sadur dari materi kuliah IHG 2 Departemen Teknik Geodesi dan Geomatikan
% ITB (by: Kosasih Prijatna dan Wedyanto Kuntjoro).

% Konversi dari koordinat geodetik (L,B,h) ke koordinat kartesian (X,Y,Z)

% input (L,B,h) sudah dalam bentuk derajat desimal
function [X,Y,Z] = LBH2XYZ (L,B,h,nm_datum)

disp(' Menjalanakan fungsi LBH ke XYZ ');
disp(' ============================== ');
disp('                                ');

format long g

[r c] = size (L);

[a,f] = datum (nm_datum);
[e,dumy] = eksentrisitas (a,f);

L = deg2rad (L);
B = deg2rad (B);
%h = deg2rad (h);

for g=1:r    
    N(g) = primverrad (a,f,L(g));

    X(g,1) = (N(g)+h(g))*cos(L(g))*cos(B(g));
    Y(g,1) = (N(g)+h(g))*cos(L(g))*sin(B(g));
    Z(g,1) = (N(g)*(1-e^2)+h(g))*sin(L(g));
end