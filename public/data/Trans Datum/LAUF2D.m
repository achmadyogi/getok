function [parameter,centroid,rms,sxx] = LAUF2D(xlama,ylama,Xbaru,Ybaru)

disp(' Menjalankan Fungsi LAUF2D');
disp(' Metode hitungan menggunakan kuadrat terkecil');

[r c] = size (xlama);  
  
xcentroid=(sum(xlama)+sum(Xbaru))/(2*r);
ycentroid=(sum(ylama)+sum(Ybaru))/(2*r);

centroid=[xcentroid ycentroid];

 xo=xlama-xcentroid;
 yo=ylama-ycentroid;
 Xo=Xbaru-xcentroid;
 Yo=Ybaru-ycentroid;
 
for j=1:r  
%        % Mengisi matriks A
        A(2*j-1,1)=xo(j);
        A(2*j-1,2)=-yo(j);
        A(2*j-1,3)=xo(j)^2-yo(j)^2;
        A(2*j-1,4)=-2*xo(j)*yo(j);
        A(2*j-1,5)=1;
        A(2*j-1,6)=0;
        
        A(2*j,1)=yo(j);
        A(2*j,2)=xo(j);
        A(2*j,3)=2*xo(j)*yo(j);
        A(2*j,4)=xo(j)^2-yo(j)^2;
        A(2*j,5)=0;
        A(2*j,6)=1;
        
        F(2*j-1)=Xo(j);
        F(2*j)=Yo(j);
end

parameter=inv(A'*A)*(A'*F');

V=A*parameter-F';
    
sigma=(V'*V)/(2*r-6);
rms=sqrt(sigma);

varx=sigma*inv(A'*A);
sxx=sqrt(diag(varx));