function  [Lat,Lon] = utm2lb(xx,yy,utmzone,hemis,nm_datum)
% -------------------------------------------------------------------------
% Modifikasi by Hafzal Hanief
% [Lat,Lon] = utm2deg(x,y,utmzone)
%
% Description: Function to convert vectors of UTM coordinates into Lat/Lon vectors (WGS84).
% Some code has been extracted from UTMIP.m function by Gabriel Ruiz Martinez.
%
% Inputs:
%    x, y , utmzone --> diubah menjadi x,y,utmzone,hemis, nm_datum
%
% Outputs:
%    Lat: Latitude vector.   Degrees.  +ddd.ddddd  WGS84
%    Lon: Longitude vector.  Degrees.  +ddd.ddddd  WGS84
%
% Example 1:
% x=[ 458730;  407652;  239026;  230253;  343898;  362850];
% y=[4462881; 5126289; 4163082; 3171843; 4302284; 2772478];
% utmzone=['30 T'; '32 T'; '11 S'; '28 R'; '15 S'; '51 R'];
% diubah menjadi 
% utmzone=['30'; '32'; '11'; '28'; '15'; '51'];
% hemis=['S';'S';'N' ......]

% [Lat, Lon]=utm2deg(x,y,utmzone)
%Lat =
%   40.31543
%   46.28399
%   37.57782
%   28.64565
%   38.85554
%   25.06188
%Lon =
%   -3.48572
%    7.80122
% -119.95536
%  -17.75954
%  -94.79902
%  121.64037
%
% Example 2: Lat/Lon coordinates in Degrees, Minutes and Seconds
%    LatDMS=dms2mat(deg2dms(Lat));
%
% Author: Rafael Palacios. Universidad Pontificia Comillas.  Apr/06
%-------------------------------------------------------------------------

% Argument checking
disp(' Menjalankan Fungsi UTM ke LB ');
disp(' ============================ ');
disp('                              ');
n=length(xx);

% Memory pre-allocation
%
Lat(n,1)=0
Lon(n,1)=0;


% Main Loop
%

[sa,f] = datum (nm_datum);
sb = sa-(sa*f);
clear f;

for i=1:n
   
   x=xx(i);
   y=yy(i);
   
   zone=utmzone;

   la=Lat(i);
   lo=Lon(i);

%    sa = 6378137.000000 ; sb = 6356752.314245;
  
  
  
   e = ( ( ( sa ^ 2 ) - ( sb ^ 2 ) ) ^ 0.5 ) / sa;
   e2 = ( ( ( sa ^ 2 ) - ( sb ^ 2 ) ) ^ 0.5 ) / sb;
   e2cuadrada = e2 ^ 2;
   c = ( sa ^ 2 ) / sb;
   alpha = ( sa - sb ) / sa;             %f
   ablandamiento = 1 / alpha;   % 1/f

   X = x - 500000;
   
   if hemis == 'S' || hemis == 's'
       Y = y - 10000000;
   else
       Y = y;
   end
    
   S = ( ( zone * 6 ) - 183 ); 
   lat =  Y / ( 6366197.724 * 0.9996 );                                    
   v = ( c / ( ( 1 + ( e2cuadrada * ( cos(lat) ) ^ 2 ) ) ) ^ 0.5 ) * 0.9996;
   a = X / v;
   a1 = sin( 2 * lat );
   a2 = a1 * ( cos(lat) ) ^ 2;
   j2 = lat + ( a1 / 2 );
   j4 = ( ( 3 * j2 ) + a2 ) / 4;
   j6 = ( ( 5 * j4 ) + ( a2 * ( cos(lat) ) ^ 2) ) / 3;
   alfa = ( 3 / 4 ) * e2cuadrada;
   beta = ( 5 / 3 ) * alfa ^ 2;
   gama = ( 35 / 27 ) * alfa ^ 3;
   Bm = 0.9996 * c * ( lat - alfa * j2 + beta * j4 - gama * j6 );
   b = ( Y - Bm ) / v;
   Epsi = ( ( e2cuadrada * a^ 2 ) / 2 ) * ( cos(lat) )^ 2;
   Eps = a * ( 1 - ( Epsi / 3 ) );
   nab = ( b * ( 1 - Epsi ) ) + lat;
   senoheps = ( exp(Eps) - exp(-Eps) ) / 2;
   Delt = atan(senoheps / (cos(nab) ) );
   TaO = atan(cos(Delt) * tan(nab));
   longitude = (Delt *(180 / pi ) ) + S;
   latitude = ( lat + ( 1 + e2cuadrada* (cos(lat)^ 2) - ( 3 / 2 ) * e2cuadrada * sin(lat) * cos(lat) * ( TaO - lat ) ) * ( TaO - lat ) ) * ...
                    (180 / pi);
   
   Lat(i)=latitude;
   Lon(i)=longitude;
   
end