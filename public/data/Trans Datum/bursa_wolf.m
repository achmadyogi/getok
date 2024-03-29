function [parameter,varx,V,sigma] = bursa_wolf (lama_xyz,baru_xyz)
% function [parameter,varx,V,sigma] = bursa_wolf (lama_xyz,baru_xyz)
% Program mencari parameter transformasi koordinat
% Menggunakan Model Transformasi bursa-wolf
% proses pencarian parameter menggunakan metode
% hitung perataan parameter
% clear all;
% % dos('TRAN_SISKORD');
% clear;
% A. Data pengamatan
% baru_xyz = load (in_baru);
% lama_xyz = load (in_lama);

disp(' Menjalankan fungsi Bursa-Wolf ');
disp(' ===================================== ');
disp('                                       ');


lng1=length(baru_xyz);
lng2=length(lama_xyz);
lama=lama_xyz(1:lng1,1:3);
Titik=lama_xyz(lng1+1:lng2,1:3);
baru=baru_xyz;


% A.1 Sistem koordinat baru
x=baru(:,1);
y=baru(:,2);
z=baru(:,3);
% A.2 Sistem koordinat lama
X=lama(:,1);
Y=lama(:,2);
Z=lama(:,3);
L=length(X);
% B. Menghitung nilai parameter pendekatan
% B.1 Membentuk matriks desain (A) dan matriks F
% Parameter pendekatan diambil dari parameter transformasi Bursa_Wolf untuk sudut kecil

for i=1:L;
    A(3*i-2,:)=[1 0 0 0 -Z(i) Y(i) X(i)];
    A(3*i-1,:)=[0 1 0 Z(i) 0 -X(i) Y(i)];
    A(3*i,:)=[0 0 1 -Y(i) X(i) 0 Z(i)];
    F(3*i-2,1)=x(i)-X(i);
    F(3*i-1,1)=y(i)-Y(i);
    F(3*i,1)=z(i)-Z(i);
end
% B.2 Membentuk matriks berat
p=zeros(3*L);
for i=1:3*L;
    p(i,i)=1; %/0.001;
end

% Parameter pendekatan

parameter=inv(A'*p*A)*(A'*p*F);

dx=parameter(1,1);
dy=parameter(2,1);
dz=parameter(3,1);
alpha=parameter(4,1);
beta=parameter(5,1);
gamma=parameter(6,1);
s=parameter(7,1);

LO=F-A*parameter; % Nilai pengamatan pendekatan

for i=1:L;
    xo(i)=LO(3*i-2,1);
    yo(i)=LO(3*i-1,1);
    zo(i)=LO(3*i,1);
end

% Iterasi dimulai
for k=1:10;
    for i=1:L;
        a(3*i-2,1)=1;
        a(3*i-2,2)=0;
        a(3*i-2,3)=0;
        a(3*i-2,4)=(s*(Y(i))*(cos(alpha)*sin(beta)*cos(gamma)-sin(gamma)*sin(alpha)))+(s*(Z(i))*(sin(gamma)*cos(alpha)+cos(gamma)*sin(beta)*sin(alpha)));
        a(3*i-2,5)=(s*(X(i))*(-1*cos(gamma)*sin(beta)))+(s*(Y(i))*(cos(gamma)*cos(beta)*sin(alpha)))+(s*(Z(i))*(-1*cos(gamma)*cos(beta)*cos(alpha)));
        a(3*i-2,6)=(s*(X(i))*(-1*sin(gamma)*cos(beta)))+(s*(Y(i))*(-1*sin(gamma)*sin(beta)*sin(alpha)+cos(gamma)*cos(alpha)))+(s*(Z(i))*(cos(gamma)*sin(alpha)+sin(gamma)*sin(beta)*cos(alpha)));
        a(3*i-2,7)=(X(i))*(cos(gamma)*cos(beta))+(Y(i))*(cos(gamma)*sin(beta)*sin(alpha)+sin(gamma)*cos(alpha))+(Z(i))*(sin(gamma)*sin(alpha)-cos(gamma)*sin(beta)*cos(alpha));

        a(3*i-1,1)=0;
        a(3*i-1,2)=1;
        a(3*i-1,3)=0;
        a(3*i-1,4)=s*(Y(i))*(-1*cos(gamma)*sin(alpha)-sin(gamma)*sin(beta)*cos(alpha))+s*(Z(i))*(-1*sin(gamma)*sin(beta)*sin(alpha)+cos(gamma)*cos(alpha));
        a(3*i-1,5)=s*(X(i))*(sin(gamma)*sin(beta))+s*(Y(i))*(-1*sin(gamma)*cos(beta)*sin(alpha))+s*(Z(i))*(sin(gamma)*cos(beta)*cos(alpha));
        a(3*i-1,6)=-1*s*(X(i))*(cos(gamma)*cos(beta))+s*(Y(i))*(-1*sin(gamma)*cos(alpha)-cos(gamma)*sin(beta)*sin(alpha))+s*(Z(i))*(cos(gamma)*sin(beta)*cos(alpha)-sin(gamma)*sin(alpha));
        a(3*i-1,7)=(X(i))*(-1*sin(gamma)*cos(beta))+(Y(i))*(cos(gamma)*cos(alpha)-sin(gamma)*sin(beta)*sin(alpha))+(Z(i))*(sin(gamma)*sin(beta)*cos(alpha)+cos(gamma)*sin(alpha));

        a(3*i,1)=0;
        a(3*i,2)=0;
        a(3*i,3)=1;
        a(3*i,4)=-1*s*(Y(i))*(cos(beta)*cos(alpha))+s*(Z(i))*(-1*cos(beta)*sin(alpha));
        ta=(((cos(gamma))^2)*cos(beta))+(((sin(gamma))^2)*cos(beta));
        tb=cos(gamma)*sin(beta)*sin(alpha)+sin(gamma)*cos(alpha);
        tc=cos(gamma)*cos(alpha)-sin(gamma)*sin(beta)*sin(alpha);
        td=sin(gamma)*sin(alpha)-cos(gamma)*sin(beta)*cos(alpha);
        te=sin(gamma)*sin(beta)*cos(alpha)+cos(gamma)*sin(alpha);
        a(3*i,5)=s*(X(i))*ta+s*(Y(i))*(cos(gamma)*tb-tc*sin(gamma))+s*(Z(i))*(cos(gamma)*td-sin(gamma)*te);
        a(3*i,6)=0;
        a(3*i,7)=(X(i))*sin(beta)-(Y(i))*(cos(beta)*sin(alpha))+(Z(i))*(cos(beta)*cos(alpha));

        F(3*i-2,1)=x(i)-xo(i);
        F(3*i-1,1)=y(i)-yo(i);
        F(3*i,1)  =z(i)-zo(i);
    end

    atpa=a'*p*a;
    R=chol(atpa);
    atpf=a'*p*F;
    %   parameter=R\(R'\atpf);

    parameter=inv(atpa)*atpf;

    dx=parameter(1,1);
    dy=parameter(2,1);
    dz=parameter(3,1);
    alpha=alpha+parameter(4,1);
    beta=beta+parameter(5,1);
    gamma=gamma+parameter(6,1);
    s=s+parameter(7,1);
    %[s parameter(7,1)];

    crit=parameter'*atpa*parameter;
    if crit <1e-16
        [k crit];
        break
    end
    % Mencari nilai pengamatan pendekatan dari parameter pendekatan
    for i=1:L;
        p1=s*cos(beta)*cos(gamma)*(X(i));
        p2=s*(cos(alpha)*sin(gamma)+sin(alpha)*sin(beta)*cos(gamma))*(Y(i));
        p3=s*(sin(alpha)*sin(gamma)-cos(alpha)*sin(beta)*cos(gamma))*(Z(i));
        xo(i)=p1+p2+p3;

        q1=-s*(cos(beta)*sin(gamma))*(X(i));
        q2=s*(cos(alpha)*cos(gamma)-sin(alpha)*sin(beta)*sin(gamma))*(Y(i));
        q3=s*(sin(alpha)*cos(gamma)+cos(alpha)*sin(beta)*sin(gamma))*(Z(i));
        yo(i)=q1+q2+q3;

        r1=s*sin(beta)*(X(i));
        r2=-s*sin(alpha)*cos(beta)*(Y(i));
        r3=s*cos(alpha)*cos(beta)*(Z(i));
        zo(i)=r1+r2+r3;
    end
end

V=a*parameter-F;
sigma=(V'*p*V)/(3*L-7);

varx=sigma*inv(a'*p*a);
sxx=sqrt(diag(varx));
parameter=[dx dy dz alpha beta gamma s];
cn=cond(a'*p*a);
for i=1:7;
    for j=1:7;
        kor(i,j)=varx(i,j)/sqrt(varx(i,i)*varx(j,j));
    end
end
V=[V(1:3:3*L),V(2:3:3*L),V(3:3:3*L)];



% Hitung koordinat hasil transformasi
% Matrik Rotasi
Rz=[cos(gamma) sin(gamma) 0; -sin(gamma) cos(gamma) 0; 0 0 1];
Ry=[cos(beta) 0 -sin(beta); 0 1 0; sin(beta) 0 cos(beta)];
Rx=[1 0 0; 0 cos(alpha) sin(alpha); 0 -sin(alpha) cos(alpha)];
R=Rz*Ry*Rx;

% Matrik Translasi
T=[dx dy dz];T=T';

lng3=lng2-lng1;
crd_new=[];
for i=1:lng3
    trans=Titik(i,:);trans=trans';
    trans=T+s*R*(trans);
    crd_new=[crd_new;trans'];

end


%[dx dy dz alpha beta gamma s]

% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\PM87BGD.DAT parameter -ascii -double        % PARAMETER TRANSFORMASI
% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\SM87BGD.DAT sxx -ascii -double              % SIMPANGAN BAKU PARAMETER
% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\VM87BGD.DAT varx -ascii -double             % VARIANSI KOVARIANSI PARAMETER
% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\KM87BGD.DAT kor -ascii -double              % KORELASI ANTAR PARAMETER
% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\RM87BGD.DAT V -ascii -double                % RESIDU
% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\ZM87BGD.DAT sigma -ascii -double            % VARIANSI APOSTERIORI
% save C:\MATLAB6p1\work\PERTAMINA\DOHJBB\CM87BGD.DAT CM -ascii -double               % KOORDINAT TITIK SENTRAL

% save EN-wgsblok1a_xyz.txt crd_new -ascii -double -- coret
% save EN-wgsblok1a_par.txt parameter -ascii -double
% save EN-wgsblok1a_std.txt sxx -ascii -double --coret
% save EN-wgsblok1a_var.txt varx -ascii -double
% save EN-wgsblok1a_cor.txt kor -ascii -double -- coret
% save EN-wgsblok1a_res.txt V -ascii -double
% save EN-wgsblok1a_aps.txt sigma -ascii -double
% save EN-wgsblok1a_xcm.txt CM -ascii -double 
% 
