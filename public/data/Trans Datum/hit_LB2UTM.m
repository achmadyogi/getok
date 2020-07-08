clc;

ndata = xlsread('BM_Sekutu-ITB.xls', 'SEKUTU ITB')
%    Lat=[40.3154333; 46.283900; 37.577833; 28.645650; 38.855550; 25.061783];
%    Lon=[-3.4857166; 7.8012333; -119.95525; -17.759533; -94.7990166; 121.640266];
Lat=ndata(:,6);
Lon=ndata(:,7);
[x,y,utmzone] = deg2utm(Lat,Lon,'wgs84');
dx=ndata(:,5)-x;
dy=ndata(:,6)-y;
data_out=[Lat Lon x y];
xlswrite('SekutuITB.xls', data_out, 'Out');