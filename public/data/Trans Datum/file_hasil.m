function [parameter,varx,V,sigma] = file_hasil(parameter3D_BW,parameter3D,parameter2D_M,variansiM,parameter2DLAUF,variansiL)


% Simpan hasil dalam file text
        fout_ket = [blok,'_prmtr.txt'];
        fid2=fopen(fout_ket,'wt');
        fprintf(fid2, '========================================================================================================\n');    
        fprintf(fid2, 'Transformasi Datum/Koordinat  \n');
        fprintf(fid2, '--------------------------------------------------------------------------------------------------------\n');
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

        fprintf(fid2, '========================================================================================================\n');    
        fprintf(fid2, 'Parameter Transformasi Datum 3D Mododensky-Badekas \n');
        fprintf(fid2, '--------------------------------------------------------------------------------------------------------\n');
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
        fprintf(fid2, '--------------------------------------------------------------------------------------------------------\n');
        fprintf(fid2,'    \n');    

        Rotasi1 = 180/pi*3600*atan(-parameter2D_M(2)/parameter2D_M(1));
        skala1 = sqrt(parameter2D_M(1)^2+parameter2D_M(2)^2);
        % parameter 2D similarity Least Square
        fprintf(fid2, '========================================================================================================\n');
        fprintf(fid2, 'Parameter Transformasi Koordinat 2D Similarity \n');
        fprintf(fid2, '    (LeastSquare dan menggunakan Centroid)\n');
        fprintf(fid2, '--------------------------------------------------------------------------------------------------------\n');
        fprintf(fid2, 'a            = %20.17f +/- %20.17f \n',parameter2D_M(1),variansiM(1));    
        fprintf(fid2, 'b            = %20.17f +/- %20.17f \n',parameter2D_M(2),variansiM(2));
        fprintf(fid2, 'Tx           = %20.15f +/- %20.17f m\n',parameter2D_M(3),variansiM(3));    
        fprintf(fid2, 'Ty           = %20.15f +/- %20.17f m\n',parameter2D_M(4),variansiM(4));
        fprintf(fid2, 'Rotasi       = %20.12f detik\n',Rotasi1); 
        fprintf(fid2, 'Skala        = %20.12f \n',skala1); 
        fprintf(fid2, 'centroid-x   = %20.3f m\n',parameter2D_M(5));    
        fprintf(fid2, 'centroid-y   = %20.3f m\n',parameter2D_M(6));
        fprintf(fid2, 'RMS          = %20.3f m\n',variansiM(5));
        fprintf(fid2, '-------------------------------------------------------------------------------------------------------\n');
        fprintf(fid2,'    \n');
        
        Rotasi2 = 180/pi*3600*atan(-param2Dsim(2)/param2Dsim(1));
        skala2 = sqrt(param2Dsim(1)^2+param2Dsim(2)^2);
        % parameter 2D similarity Non-Least Square
        fprintf(fid2, '=======================================================================================================\n');
        fprintf(fid2, 'Parameter Transformasi Koordinat 2D Similarity non-LeastSquare\n');
        fprintf(fid2, '    (Non-LeastSquare dan tanpa Centroid)\n');
        fprintf(fid2, '-------------------------------------------------------------------------------------------------------\n');
        fprintf(fid2, 'a            = %20.17f \n',param2Dsim(1));    
        fprintf(fid2, 'b            = %20.17f \n',param2Dsim(2));
        fprintf(fid2, 'Tx           = %20.15f m\n',param2Dsim(3));    
        fprintf(fid2, 'Ty           = %20.15f m\n',param2Dsim(4));
        fprintf(fid2, 'Rotasi       = %20.12f detik\n',Rotasi2); 
        fprintf(fid2, 'Skala        = %20.12f \n',skala2); 
        fprintf(fid2, 'RMS          = %20.3f m\n',param2Dsim(5));
        fprintf(fid2, '-------------------------------------------------------------------------------------------------------\n');
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