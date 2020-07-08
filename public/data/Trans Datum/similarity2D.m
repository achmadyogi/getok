function [a,b,Tx,Ty,rms] = similarity2D(xlama,ylama,Xbaru,Ybaru)

disp(' Menjalankan fungsi Similarity2D');
disp(' Metode hitungan normal');

[r c]=size(xlama);
cdx = Xbaru - xlama;

if cdx > 999
	xo=xlama;
	yo=ylama;
	Xo=Xbaru;
	Yo=Ybaru;
	% tdk menghitung koordinat centroid masing sistem koordinat
	xcentroid = 0.0;
	ycentroid = 0.0;
	Xcentroid = 0.0;
	Ycentroid = 0.0;
	
else
	
	% menghitung koordinat centroid masing sistem koordinat
	xcentroid = sum(xlama)/r;
	ycentroid = sum(ylama)/r;
	Xcentroid = sum(Xbaru)/r;
	Ycentroid = sum(Ybaru)/r;

	% Menghitung selisih masing2 titik sekutu terhadap centroid    
	xo=xlama-xcentroid;
	yo=ylama-ycentroid;
	Xo=Xbaru-Xcentroid;
	Yo=Ybaru-Ycentroid;
end 

for i=1:r
     Io(i) = xo(i)*Xo(i);
     IIo(i) = yo(i)*Yo(i);
     IIIo(i) = Yo(i)*xo(i);
     IVo(i) = Xo(i)*yo(i);
     dx2(i)=xo(i)^2;
     dy2(i)=yo(i)^2;
end

I = sum(Io);
II = sum(IIo);
III = sum(IIIo);
IV = sum(IVo);
V=sum(dx2)+sum(dy2);
 
% menghitung parameter 
a = (I + II)/V;
b = (III - IV)/V;
 
Tx = Xcentroid - (a*xcentroid - b*ycentroid);
Ty = Ycentroid - (b*xcentroid + a*ycentroid);
 

% menghitung pernedaan titik sekutu hasil transformasi
% dengan hasil ukuran 
for i=1:r
     vx(i) = Xbaru(i)-(a*xlama(i)-b*ylama(i)+Tx);
     vy(i) = Ybaru(i)-(a*ylama(i)+b*xlama(i)+Ty);
     VIo(i)=vx(i)^2;
     VIIo(i)=vy(i)^2;
   
end
 VI=sum(VIo);
 VII=sum(VIIo);
 
 % RMS
 sigma=(VI+VII)/(2*r-4);
 rms=sqrt(sigma);
 
 
 