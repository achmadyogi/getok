% Fungsi mencari nilai eksentrisitas 1 dan 2 (e1,e2).Dibuat oleh Teguh 
% Purnama Sidiq pada tanggal 19 Juli 2007. Dikutip dari modul kuliah IHG 2 
% Departemen Teknik Geodesi dan Geomatika ITB (by: Kosasih Prijatna dan 
% Wedyanto Kuntjoro).

% Fungsi ini memerlukan input sumbu panjang ellipsoid (a), dan nilai
% penggepengannya (f). Outputnya nilai eksentrisitas 1 dan 2 (e1,e2).

function [e1,e2] = eksentrisitas (a,f)

e1 = (2*f-f^2)^0.5;
e2 = (e1^2/(1-e1^2))^0.5;