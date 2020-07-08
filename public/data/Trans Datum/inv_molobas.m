function [x,y,z] = inv_molobas (in_lama,param,CM)

% matriks parameter = [dx;dy;dz;alpha;betha;gamma;skala]
% CM = matriks titik berat (center mass) == [x_cm,y_cm,z_cm]
% in_lama = [x_lama,y_lama,z_lama]

% param(4) = deg2rad(param(4));
% param(5) = deg2rad(param(5));
% param(6) = deg2rad(param(6));

% Matrik Rotasi
Rz=[cos(param(6)) sin(param(6)) 0; -sin(param(6)) cos(param(6)) 0; 0 0 1];
Ry=[cos(param(5)) 0 -sin(param(5)); 0 1 0; sin(param(5)) 0 cos(param(5))];
Rx=[1 0 0; 0 cos(param(4)) sin(param(4)); 0 -sin(param(4)) cos(param(4))];
R=Rz*Ry*Rx;

% R = [1 param(6) -param(5);-param(6) 1 param(4);param(5) -param(4) 1]

% Matriks Translasi
T = [param(1);param(2);param(3)];

% Skala
s = param(7);

CM = reshape (CM,3,1);

[r c] = size (in_lama);

clear c;

for g=1:r
    temp1 = reshape (in_lama(g,:),3,1);

    temp2 = CM+(s*R)*(temp1-CM)+T;

    x(g,1) = temp2 (1);
    y(g,1) = temp2 (2);
    z(g,1) = temp2 (3);
end