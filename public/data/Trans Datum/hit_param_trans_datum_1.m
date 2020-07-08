clc
clear
fin=fopen('File_Titik_Sekutu_1_SBS.txt');
CC=textscan(fin,'%s');
fclose(fin);
nm_file=CC{1};
[jmlfile oo]=size(nm_file);

for i=1:jmlfile
    nama_file=char(nm_file(i));
    disp(nama_file);
    fid=fopen(nama_file);
    %fid=fopen('82KRS_Sekutu');
    H1 = textscan(fid,'%s%s%s%s',1);
    H2 = textscan(fid,'%s%s%s%s',1);
    H3 = textscan(fid,'%s%s%s%s',1);
    H4 = textscan(fid,'%s%s%s%s',1);
    H5 = textscan(fid,'%s%s%s%s%s',1);


    blok = char(H1{4});
    % membaca Datum
    datum = char(H2{4});
    % membaca UTM zone  
    utm_zone = str2num(char(H4{3}));
    % membaca UTM hemisphere 
    hemis = char(H4{4});

    C = textscan(fid,'%s%s%s%s%s');
    titik=(C{1});
    
    xl=str2num(char(C{2}));
    yl=str2num(char(C{3}));
    Xb=str2num(char(C{4})); 
    Yb=str2num(char(C{5}));
    
    xlama=str2num(char(C{2}));
    ylama=str2num(char(C{3}));
    Xbaru=str2num(char(C{4})); 
    Ybaru=str2num(char(C{5}));
    
    
    fclose(fid);
    [r1 c] = size (xl);
   
%         dx=Xb-xl;
%         dy=Yb-yl;
%         n = length(dx);
%         meanx = sum(dx)/n;
%         stdevx = sqrt(sum((dx-meanx).^2/n));
%         meany = sum(dy)/n;
%         stdevy = sqrt(sum((dy-meany).^2/n));
% 
%         j=1;
%         for i=1:r1
%             if (dx(i)-meanx)<stdevx
%                 xlama(j)=xl(i);
%                 Xbaru(j)=Xb(i);
%                 ylama(j)=yl(i);
%                 Ybaru(j)=Yb(i);
%                 j=j+1;
%             else
%                 tanda(i)='A';
%             end
%         end
%         r=j;




    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    dx=Xbaru-xlama;
    dy=Ybaru-ylama;
    n = length(dx);
    meanx = sum(dx)/n;
    stdevx = sqrt(sum((dx-meanx).^2/n));
    meany = sum(dy)/n;
    stdevy = sqrt(sum((dy-meany).^2/n));
    
    

    % r = banyaknya data titik sekutu
    [r c] = size (xlama);

    if r>2
        
        if datum =='Genuk'
            [Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'bessel');
        else
            [Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'grs-67');
        end

        [Ln,Bn] = utm2lb(Xbaru,Ybaru,utm_zone,hemis,'wgs84');

        % mengisi tinggi titik sekutu dengan 0.0 karena tidak ada data tinggi
        % terhadap ellipsoid/datum lama
            for g=1:r
                ho(g)=0.0;
                hn(g)=0.0;
            end

        % Menghitung koordinat 3D XYZ pada sistem lama & baru    
        if datum =='Genuk'
            [Xo,Yo,Zo] = LBH2XYZ (Lo,Bo,ho,'bessel');
        else
            [Xo,Yo,Zo] = LBH2XYZ (Lo,Bo,ho,'grs-67');
        end

        [Xn,Yn,Zn] = LBH2XYZ (Ln,Bn,hn,'wgs84');

        % Menghitung parameter transformasi dengan metode molodensky-badekas
        in_lama(:,1)=Xo(:);
        in_lama(:,2)=Yo(:);
        in_lama(:,3)=Zo(:);
        in_baru(:,1)=Xn(:);
        in_baru(:,2)=Yn(:);
        in_baru(:,3)=Zn(:);
        clear Xo Yo Zo Xn Yn Zn;

        % menghitung parameter transformasi 3D dg Molodensky-Badekas
        [parameter,varx,V,sigma,CM] = molobas (in_lama,in_baru);

        parameter3D(1) = parameter(1);% Tx 
        parameter3D(2) = parameter(2);% Ty 
        parameter3D(3) = parameter(3);% Tz 
        parameter3D(4) = parameter(4);% alfa
        parameter3D(5) = parameter(5);% beta 
        parameter3D(6) = parameter(6);% gamma
        parameter3D(7) = parameter(7);% skala 
        parameter3D(8) = CM(1);
        parameter3D(9) = CM(2);
        parameter3D(10)= CM(3);

        sdv(1) = sqrt(varx(1,1));
        sdv(2) = sqrt(varx(2,2));
        sdv(3) = sqrt(varx(3,3));
        sdv(4) = sqrt(varx(4,4));
        sdv(5) = sqrt(varx(5,5));
        sdv(6) = sqrt(varx(6,6));
        sdv(7) = sqrt(varx(7,7));
        sdv(8) = sqrt(sigma);

        clear in_lama in_baru;
        %end
        % Menghitung parameter transformasi dengan metode Similarity 
        % dengan kuadrat terkecil (Matriks)

        [parameter2D_M,centroid,rmsM,sxxM] = similarityM(xlama,ylama,Xbaru,Ybaru);
        % menambahkan pada array nilai centroid  
        parameter2D_M(5) = centroid(1);
        parameter2D_M(6) = centroid(2);
        % membuat array baru berisi variansi & rms dari parameter
        variansiM(1) = sxxM(1);
        variansiM(2) = sxxM(2);
        variansiM(3) = sxxM(3);
        variansiM(4) = sxxM(4);
        variansiM(5) = rmsM;

        % Menghitung parameter transformasi dengan metode Similarity 
        % tanpa Matriks
        [a,b,Tx,Ty,rms] = similarity2D(xlama,ylama,Xbaru,Ybaru);
        param2Dsim(1) = a;
        param2Dsim(2) = b;
        param2Dsim(3) = Tx;
        param2Dsim(4) = Ty;
        param2Dsim(5) = rms;

%         % Menghitung parameter transformasi dengan metode Polinom LAUF2D 
%         [parameter2DLAUF,centroid,rmsL,sxxL] = LAUF2D(xlama,ylama,Xbaru,Ybaru);
% 
%         % menambahkan pada array nilai centroid
%         parameter2DLAUF(7) = centroid(1);
%         parameter2DLAUF(8) = centroid(2);
%         % membuat array baru berisi variansi & rms dari parameter
%         variansiL(1) = sxxL(1);
%         variansiL(2) = sxxL(2);
%         variansiL(3) = sxxL(3);
%         variansiL(4) = sxxL(4);
%         variansiL(5) = sxxL(5);
%         variansiL(6) = sxxL(6);
%         variansiL(7) = rmsL;
% 
%         % Hitungan parameter Polinom LAUF2D berakhir di sini
%         % Menulis file paramter dg 4 metode di atas lengkap

        out_lengkap = 'hasil1.txt';

        fid1=fopen(out_lengkap,'wt');

        for i=1:3
            fprintf(fid1, '%20.15f \n',parameter3D(i));
        end 
        for i=4:6
            fprintf(fid1, '%25.22f \n',parameter3D(i));
        end 
        fprintf(fid1, '%18.16f \n',parameter3D(7));
        for i=8:10
            fprintf(fid1, '%20.12f \n',parameter3D(i));
        end    
        % variansi, sigma & rms
        for i=1:3
            fprintf(fid1, '%20.15f \n',sdv(i));
        end
        for i=4:6
            fprintf(fid1, '%25.22f \n',sdv(i));
        end
        fprintf(fid1, '%18.16f \n',sdv(7));
        fprintf(fid1, '%18.16f \n',sdv(8));
        for i=1:4
            fprintf(fid1, '%20.15f \n',parameter2D_M(i));
        end
        for i=5:6
            fprintf(fid1, '%20.12f \n',parameter2D_M(i));
        end
        % variansi, sigma & rms
        for i=1:4
            fprintf(fid1, '%20.15f \n',variansiM(i));
        end
        fprintf(fid1, '%18.16f \n',variansiM(5));
        % parameter 2D similarity non-matriks
        for i=1:4        
            fprintf(fid1, '%20.15f \n',param2Dsim(i));
        end
        fprintf(fid1, '%18.16f \n',param2Dsim(5));    

%         % parameter 2D LAUF polinom
% 
%         for i=1:6        
%             fprintf(fid1, '%20.15f \n',parameter2DLAUF(i));
%         end
%         for i=7:8
%             fprintf(fid1, '%20.12f \n',parameter2DLAUF(i));
%         end
% 
% 
%         % variansi, sigma & rms LAUF
% 
%         for i=1:6        
%             fprintf(fid1, '%20.15f \n',variansiL(i));
%         end
%         fprintf(fid1, '%18.16f \n',variansiL(7));

        fclose(fid1);% tutup file output 1



        % Simpan hasil dalam file text
        fout_ket = [blok,'_prmtr.txt'];
        fid2=fopen(fout_ket,'wt');
        fprintf(fid2, '=================================================================\n');    
        fprintf(fid2, 'Transformasi Datum/Koordinat  \n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');   
        fprintf(fid2,'Nama file titik sekutu : %s \n',nama_file); 
        fprintf(fid2,'Datum Asal : %s \n',datum);
        fprintf(fid2,'Datum Baru : DGN-95/WGS-84 \n');
        fprintf(fid2,'Koordinat Titik Sekutu : UTM Zone %d %s \n',utm_zone,hemis);
        fprintf(fid2,'=========================================================================================================\n');    
        fprintf(fid2,'No   Titik          xlama          ylama          Xbaru         Ybaru     ddx    stdx   ddy   stdy       \n');
        fprintf(fid2,'---------------------------------------------------------------------------------------------------------\n');
        for i=1:r
            if (abs(dx(i)-meanx)-stdevx)>stdevx || (abs(dy(i)-meany)-stdevy)>stdevy
                tanda = '***';
            else
                tanda='';
            end
            fprintf(fid2, '%2d %12s %13.3f %13.3f %13.3f %13.3f %6.2f %6.2f %6.2f %6.2f %4s \n',i,char(titik(i)),xlama(i),ylama(i),Xbaru(i),Ybaru(i),abs(dx(i)-meanx)-stdevx,stdevx,abs(dy(i)-meany)-stdevy,stdevy,tanda);
        end
        fprintf(fid2, '--------------------------------------------------------------------------------------------------------\n');
        fprintf(fid2,'    \n');
        fprintf(fid2,'    \n');  

        fprintf(fid2, '=================================================================\n');    
        fprintf(fid2, 'Parameter Transformasi Datum 3D Mododensky-Badekas \n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2, 'DX      = %25.15f +/- %20.18f m\n',parameter3D(1),sdv(1));
        fprintf(fid2, 'DY      = %25.15f +/- %20.18f m\n',parameter3D(2),sdv(2));
        fprintf(fid2, 'DZ      = %25.15f +/- %20.18f m\n',parameter3D(3),sdv(3));
        fprintf(fid2, 'Alpha   = %25.22f +/- %20.18f rad\n',parameter3D(4),sdv(4));
        fprintf(fid2, 'Betha   = %25.22f +/- %20.18f rad\n',parameter3D(5),sdv(5));
        fprintf(fid2, 'Gamma   = %25.22f +/- %20.18f rad\n',parameter3D(6),sdv(6));
        fprintf(fid2, 'Skala   = %25.17f +/- %20.18f m\n',parameter3D(7),sdv(7));
        fprintf(fid2, 'CM-X    = %25.3f m\n',parameter3D(8));
        fprintf(fid2, 'CM-Y    = %25.3f m\n',parameter3D(8));
        fprintf(fid2, 'CM-Z    = %25.3f m\n',parameter3D(10));   
        fprintf(fid2, 'RMS     = %25.3f m\n',sdv(8));
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');    

        Rotasi1 = 180/pi*3600*atan(-parameter2D_M(2)/parameter2D_M(1));
        skala1 = sqrt(parameter2D_M(1)^2+parameter2D_M(2)^2);
        % parameter 2D similarity Least Square
        fprintf(fid2, '=================================================================\n');
        fprintf(fid2, 'Parameter Transformasi Koordinat 2D Similarity \n');
        fprintf(fid2, '    (LeastSquare dan menggunakan Centroid)\n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2, 'a            = %20.17f +/- %20.17f \n',parameter2D_M(1),variansiM(1));    
        fprintf(fid2, 'b            = %20.17f +/- %20.17f \n',parameter2D_M(2),variansiM(2));
        fprintf(fid2, 'Tx           = %20.15f +/- %20.17f m\n',parameter2D_M(3),variansiM(3));    
        fprintf(fid2, 'Ty           = %20.15f +/- %20.17f m\n',parameter2D_M(4),variansiM(4));
        fprintf(fid2, 'Rotasi       = %20.12f detik\n',Rotasi1); 
        fprintf(fid2, 'Skala        = %20.12f \n',skala1); 
        fprintf(fid2, 'centroid-x   = %20.3f m\n',parameter2D_M(5));    
        fprintf(fid2, 'centroid-y   = %20.3f m\n',parameter2D_M(6));
        fprintf(fid2, 'RMS          = %20.3f m\n',variansiM(5));
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');
        
        Rotasi2 = 180/pi*3600*atan(-param2Dsim(2)/param2Dsim(1));
        skala2 = sqrt(param2Dsim(1)^2+param2Dsim(2)^2);
        % parameter 2D similarity Non-Least Square
        fprintf(fid2, '=================================================================\n');
        fprintf(fid2, 'Parameter Transformasi Koordinat 2D Similarity non-LeastSquare\n');
        fprintf(fid2, '    (Non-LeastSquare dan tanpa Centroid)\n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2, 'a            = %20.17f \n',param2Dsim(1));    
        fprintf(fid2, 'b            = %20.17f \n',param2Dsim(2));
        fprintf(fid2, 'Tx           = %20.15f m\n',param2Dsim(3));    
        fprintf(fid2, 'Ty           = %20.15f m\n',param2Dsim(4));
        fprintf(fid2, 'Rotasi       = %20.12f detik\n',Rotasi2); 
        fprintf(fid2, 'Skala        = %20.12f \n',skala2); 
        fprintf(fid2, 'RMS          = %20.3f m\n',param2Dsim(5));
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');

%         fprintf(fid2, '=================================================================\n');    
%         fprintf(fid2, 'Parameter Transformasi Koordinat 2D LAUF Polinom \n');
%         fprintf(fid2, '-----------------------------------------------------------------\n');
%         fprintf(fid2, 'a1         = %20.17f +/- %20.18f \n',parameter2DLAUF(1),variansiL(1));
%         fprintf(fid2, 'a2         = %20.17f +/- %20.18f \n',parameter2DLAUF(2),variansiL(2));
%         fprintf(fid2, 'a3         = %20.17f +/- %20.18f \n',parameter2DLAUF(3),variansiL(3));
%         fprintf(fid2, 'a4         = %20.17f +/- %20.18f \n',parameter2DLAUF(4),variansiL(4));
%         fprintf(fid2, 'c1         = %20.15f +/- %20.18f m\n',parameter2DLAUF(5),variansiL(5));
%         fprintf(fid2, 'c2         = %20.15f +/- %20.18f m\n',parameter2DLAUF(6),variansiL(6));
%         fprintf(fid2, 'centroid-x = %20.3f m\n',parameter2DLAUF(7));
%         fprintf(fid2, 'centroid-x = %20.3f m\n',parameter2DLAUF(8));
%         fprintf(fid2, 'RMS        = %20.3f m\n',variansiL(7));
%         fprintf(fid2, '-----------------------------------------------------------------\n');
%         fprintf(fid2,'    \n'); 

        fclose(fid2);
    else
        
        xlama=xl;
        Xbaru=Xb;
        ylama=yl;
        Ybaru=Yb;
        dx=Xbaru-xlama;
        dy=Ybaru-ylama;
                
        fout_ket = [blok,'_pmtr_xx.txt'];
        fid2=fopen(fout_ket,'wt');
        fprintf(fid2, '=================================================================\n');    
        fprintf(fid2, 'Transformasi Datum/Koordinat  \n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');   
        fprintf(fid2,'Nama file titik sekutu : %s \n',nama_file); 
        fprintf(fid2,'Datum Asal : %s \n',datum);
        fprintf(fid2,'Datum Baru : DGN-95/WGS-84 \n');
        fprintf(fid2,'Koordinat Titik Sekutu : UTM Zone %d %s \n',utm_zone,hemis);
        fprintf(fid2,'================================================================================\n');    
        fprintf(fid2,'No   Titik            xlama          ylama          Xbaru          Ybaru        \n');
        fprintf(fid2,'--------------------------------------------------------------------------------\n');
        for i=1:r
            fprintf(fid2, '%2d %12s %14.3f %14.3f %14.3f %14.3f \n',i,char(titik(i)),xlama(i),ylama(i),Xbaru(i),Ybaru(i));
        end
        if r==2 
            if (abs(dx(1)-dx(2)))>20
                tanda_x = 'xxx';
            else
                tanda_x='';
            end
            
            if (abs(dy(1)-dy(2)))>20
                tanda_y = '###';
            else
                tanda_y='';
            end
        end
        
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');
        fprintf(fid2,'    \n');  

        fprintf(fid2, '=================================================================\n');    
        fprintf(fid2, 'Parameter Translasi \n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        for i=1:r
            fprintf(fid2, 'dx      = %15.3f m %5s  \n',dx(i),tanda_x);
            fprintf(fid2, 'dy      = %15.3f m %5s  \n',dy(i),tanda_y);
        end
        if r==2
           fprintf(fid2, '-----------------------------------------------------------------\n');
           fprintf(fid2, 'dx rata-rata      = %15.3f  m\n',mean(dx));
           fprintf(fid2, 'dy rata-rata      = %15.3f  m\n',mean(dy));
        end
        fprintf(fid2, '-----------------------------------------------------------------\n');
        if tanda_x=='xxx'
            fprintf(fid2, 'dx berbeda cukup berarti, perlu verifikasi lanjutan  \n');
        end
        if tanda_y=='###'
            fprintf(fid2, 'dy berbeda cukup berarti, perlu verifikasi lanjutan  \n');
        end
        fclose(fid2);
        
    end
end