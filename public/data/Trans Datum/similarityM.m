function [parameter,centroid,rms,sxx] = similarityM(xlama,ylama,Xbaru,Ybaru)

disp(' Menjalankan Fungsi SimilarityM');
disp(' Metode hitungan menggunakan kuadrat terkecil');

[r c] = size (xlama);
 
cdx = Xbaru - xlama;
if cdx > 999
	xcentroid=0.0;
    ycentroid=0.0;
else
	xcentroid=(sum(xlama)+sum(Xbaru))/(2*r);
	ycentroid=(sum(ylama)+sum(Ybaru))/(2*r);
end


% Dipersiapkan untuk perhitungan tanpa centroid
% yaitu menghitung dari koordinat lokal langsung ke UTM WGS84
% PakaiCentroid ();
% %jawaban=input(' Apakah menggunakan centroid ? ');
% 
% if (jawaban == 'Y') 
%     xcentroid=(sum(xlama)+sum(Xbaru))/(2*r);
%     ycentroid=(sum(ylama)+sum(Ybaru))/(2*r);
% else 
%     xcentroid=0.0;
%     ycentroid=0.0;
% end



centroid=[xcentroid ycentroid];
    
xo=xlama-xcentroid;
yo=ylama-ycentroid;
Xo=Xbaru-xcentroid;
Yo=Ybaru-ycentroid;
 
for j=1:r  
        % Mengisi matriks A
        A(2*j-1,1)=xo(j);
        A(2*j-1,2)=-yo(j);
        A(2*j-1,3)=1;
        A(2*j-1,4)=0;
        
        A(2*j,1)=yo(j);
        A(2*j,2)=xo(j);
        A(2*j,3)=0;
        A(2*j,4)=1;
        
        F(2*j-1)=Xo(j);
        F(2*j)=Yo(j);
end

 
 parameter=inv(A'*A)*(A'*F');
 
 
V=A*parameter-F';
sigma=(V'*V)/(2*r-4);
rms=sqrt(sigma);
varx=sigma*inv(A'*A);
sxx=sqrt(diag(varx));


