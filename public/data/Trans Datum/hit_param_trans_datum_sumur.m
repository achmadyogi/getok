
clc
clear all
fin=fopen('File_Ttk_Sekutu.txt');
CC=textscan(fin,'%s');
fclose(fin);
nm_file=CC{1};
[jmlfile oo]=size(nm_file);
% indx=input('Parameter hasil hitungan ke? [1,2,...] : ','s');

%             fout_ket = input('Masukan nama file output : ');
  fout_ket = 'out.txt';
            fid2=fopen(fout_ket,'wt');

for i=1:jmlfile
    nama_file=char(nm_file(i));
    disp(nama_file);
    fid=fopen(nama_file);
   
    % membaca header file input
    H1 = textscan(fid,'%s%s%s%s',1);
    H2 = textscan(fid,'%s%s%s%s',1);
    H3 = textscan(fid,'%s%s%s%s',1);
    H4 = textscan(fid,'%s%s%s%s',1);
    H5 = textscan(fid,'%s%s%s%s%s',1);

    % menyimpan nama blok
    blok = char(H1{4});
    % menyimpan nama Datum
    datum = char(H2{4});
    % menyimpan nama UTM zone  
    utm_zone = str2num(char(H4{3}));
    % menyimpan nama UTM hemisphere 
    hemis = char(H4{4});

    C = textscan(fid,'%s%s%s%s%s');
    % menyimpan nama titik
    titik=(C{1});
    
    % menyimpan koordinat lama titik sekutu
    xlama=str2num(char(C{2}));
    ylama=str2num(char(C{3}));
    Xbaru=str2num(char(C{4})); 
    Ybaru=str2num(char(C{5}));
    
    
    fclose(fid);
    r = length(xlama);% jumlah titik sekutu
    
    if r<3       

        dx=Xbaru-xlama;
        dy=Ybaru-ylama;
                
%         fout_ket = [blok,'_pmtr_xx.txt'];
%         fid2=fopen(fout_ket,'wt');
        fprintf(fid2,'    \n');
        fprintf(fid2, '=================================================================\n');    
        fprintf(fid2, 'Transformasi Datum/Koordinat  \n');
        fprintf(fid2, '-----------------------------------------------------------------\n');
        fprintf(fid2,'    \n');   
        fprintf(fid2,'Nama file titik sekutu : %s \n',nama_file); 
        fprintf(fid2,'Datum Asal : %s \n',datum);
        fprintf(fid2,'Datum Baru : DGN-95/WGS-84 \n');
        fprintf(fid2,'Koordinat Titik Sekutu : UTM Zone %d %s \n',utm_zone,hemis);
        fprintf(fid2,'==================================================================================================\n');    
        fprintf(fid2,'No   Sumur                xlama          ylama          Xbaru         Ybaru        dx      dy     \n');
        fprintf(fid2,'--------------------------------------------------------------------------------------------------\n');
        
        for i=1:r
            fprintf(fid2,'%2d %15s %14.3f %14.3f %14.3f %14.3f %8.2f %7.2f \n',i,char(titik(i)),xlama(i),ylama(i),Xbaru(i),Ybaru(i),dx(i),dy(i));
        end
        % tanda untuk data yang perlu perhatian
        tanda_x=' ';
        tanda_y=' ';
        if r==2 
            if (abs(dx(1)-dx(2)))>10 % membandingkan 2 data sj
                tanda_x = 'xxx';
            end
            
            if (abs(dy(1)-dy(2)))>10 % membandingkan 2 data sj
                tanda_y = '###';
            end
        end;
        
        fprintf(fid2, '--------------------------------------------------------------------------------------------------\n');
        fprintf(fid2,'    \n');
        fprintf(fid2, '==========================================\n'); 
        if r==1 
            fprintf(fid2, 'Parameter Translasi \n');
            fprintf(fid2, '------------------------------------------\n');
            fprintf(fid2, 'dx  = %10.3f m \n',dx(i));
            fprintf(fid2, 'dy  = %10.3f m \n',dy(i));
        end
        
        if r==2
            fprintf(fid2, 'Parameter Translasi Rata-Rata             \n');
            fprintf(fid2, '------------------------------------------\n'); 
            fprintf(fid2, 'dx  = %10.3f m %5s  \n',mean(dx),tanda_x);
            fprintf(fid2, 'dy  = %10.3f m %5s  \n',mean(dy),tanda_y);
        end
        fprintf(fid2, '------------------------------------------\n');
        if eq(tanda_x,'xxx')
            fprintf(fid2, 'dx berbeda %6.2f m, perlu verifikasi lanjutan  \n',(abs(dx(1)-dx(2))));
        end
        if eq(tanda_y,'###')
            fprintf(fid2, 'dy berbeda %6.2f m, perlu verifikasi lanjutan  \n',(abs(dy(1)-dy(2))));
        end
        fclose(fid2);
        clear xlama xl  Xbaru Xb ylama yl Ybaru Yb dx dy
    end
    
    ttk_tolak=[];
    xl_tolak=[];yl_tolak=[];
    Xb_tolak=[];Yb_tolak=[];
    
    if r>2
      hit_ulang='y'; % menhitung ulang dengan memilih titik sekutu yg valid
      while hit_ulang=='y'
            
        dx=Xbaru-xlama;
        dy=Ybaru-ylama;
        n=length(dx);
        r=n;%  banyaknya data titik sekutu
        n_ttk=n;
        meanx = sum(dx)/n;
        stdevx = sqrt(sum((dx-meanx).^2/n));
        meany = sum(dy)/n;
        stdevy = sqrt(sum((dy-meany).^2/n));
    
%         if isequal(datum,'Genuk')
%             [Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'bessel');
%         elseif isequal(datum,'ID-74')
%             [Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'grs-67');
%         else
% 			[Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'hayford');
%         end
% 
%         [Ln,Bn] = utm2lb(Xbaru,Ybaru,utm_zone,hemis,'wgs84');
      
        
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

        out_lengkap = 'hasil1.txt';

        fid1=fopen(out_lengkap,'wt');

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

        fclose(fid1);% tutup file output 1
        
        % Menampilkan hasil
        fprintf( '=================================================================\n');    
        fprintf( 'Transformasi Datum/Koordinat  \n');
        fprintf( '-----------------------------------------------------------------\n');
        fprintf('    \n');   
        fprintf('Nama file titik sekutu : %s \n',nama_file); 
        fprintf('Datum Asal : %s \n',datum);
        fprintf('Datum Baru : DGN-95/WGS-84 \n');
        fprintf('Koordinat Titik Sekutu : UTM Zone %d %s \n',utm_zone,hemis);
        fprintf('========================================================================================================\n');    
        fprintf('No     Titik        xlama       ylama      Xbaru       Ybaru      dx      dy     ddx      ddy  \n');
        fprintf('--------------------------------------------------------------------------------------------------------\n');
        
        for i=1:r
            if (abs(dx(i)-meanx)-stdevx)>stdevx || (abs(dy(i)-meany)-stdevy)>stdevy
                tanda = '***';
            else
                tanda=' ';
            end
            fprintf( '%2d %15s %10.3f %11.3f %10.3f %11.3f %6.2f %6.2f %5.2f %5.2f %4s \n',i,char(titik(i)),xlama(i),ylama(i),Xbaru(i),Ybaru(i),dx(i),dy(i),abs(dx(i)-meanx)-stdevx,abs(dy(i)-meany)-stdevy,tanda);
        end
        fprintf( '-------------------------------------------------------------------------------------------------------\n');
        fprintf('Rata-rata          :                                                %6.2f  %6.2f\n',meanx,meany);
        
        fprintf('Standard deviasi x = %6.2f \n',stdevx);
        fprintf('Standard deviasi y = %6.2f \n',stdevy);
        fprintf('    \n');
        fprintf('    \n'); 
        if ~isempty(xl_tolak)
            fprintf('Titik sekutu yang tidak digunakan   \n');
            fprintf('===========================================================================================\n');    
            fprintf('No   Titik          xlama          ylama          Xbaru         Ybaru      dx      dy      \n');
            fprintf('-------------------------------------------------------------------------------------------\n');
        
            for i=1:length(xl_tolak)
                fprintf('%2d %12s %13.3f %13.3f %13.3f %13.3f %7.2f %7.2f   \n',...
                    i,char(ttk_tolak(i)),xl_tolak(i),yl_tolak(i),Xb_tolak(i),Yb_tolak(i),...
                    Xb_tolak(i)-xl_tolak(i),Yb_tolak(i)-yl_tolak(i));
            end
            fprintf('-------------------------------------------------------------------------------------------n');
        end
        
        Rotasi1 = 180/pi*3600*atan(-parameter2D_M(2)/parameter2D_M(1));
        skala1 = sqrt(parameter2D_M(1)^2+parameter2D_M(2)^2);
        % parameter 2D similarity Least Square
        fprintf( '========================================================================\n');
        fprintf( 'Parameter Transformasi Koordinat 2D Similarity \n');
        fprintf( '    (LeastSquare dan menggunakan Centroid)\n');
        fprintf( '------------------------------------------------------------------------\n');
        fprintf( 'a            = %20.17f +/- %20.17f \n',parameter2D_M(1),variansiM(1));    
        fprintf( 'b            = %20.17f +/- %20.17f \n',parameter2D_M(2),variansiM(2));
        fprintf( 'Tx           = %20.15f +/- %20.17f m\n',parameter2D_M(3),variansiM(3));    
        fprintf( 'Ty           = %20.15f +/- %20.17f m\n',parameter2D_M(4),variansiM(4));
        fprintf( 'Rotasi       = %20.12f detik\n',Rotasi1); 
        fprintf( 'Skala        = %20.12f \n',skala1); 
        fprintf( 'centroid-x   = %20.3f m\n',parameter2D_M(5));    
        fprintf( 'centroid-y   = %20.3f m\n',parameter2D_M(6));
        fprintf( 'RMS          = %20.3f m\n',variansiM(5));
        fprintf( '------------------------------------------------------------------------\n');
        fprintf('    \n');
        
        Rotasi2 = 180/pi*3600*atan(-param2Dsim(2)/param2Dsim(1));
        skala2 = sqrt(param2Dsim(1)^2+param2Dsim(2)^2);
        % parameter 2D similarity Non-Least Square
        fprintf( '========================================================================\n');
        fprintf( 'Parameter Transformasi Koordinat 2D Similarity non-LeastSquare\n');
        fprintf( '    (Non-LeastSquare dan tanpa Centroid)\n');
        fprintf( '------------------------------------------------------------------------\n');
        fprintf( 'a            = %20.17f \n',param2Dsim(1));    
        fprintf( 'b            = %20.17f \n',param2Dsim(2));
        fprintf( 'Tx           = %20.15f m\n',param2Dsim(3));    
        fprintf( 'Ty           = %20.15f m\n',param2Dsim(4));
        fprintf( 'Rotasi       = %20.12f detik\n',Rotasi2); 
        fprintf( 'Skala        = %20.12f \n',skala2); 
        fprintf( 'RMS          = %20.3f m\n',param2Dsim(5));
        fprintf( '------------------------------------------------------------------------\n');
        fprintf('    \n');
        % akhir print ke layar 
        
%         simpan=input('Apakah hasil akan disimpan dalam file ? (y/t) [y] : ','s');
%         if isempty(simpan)
%             simpan='y'
%         end
%         if isequal(simpan,'y')
        % Simpan hasil dalam file text
%             indx=input('Parameter hasil hitungan ke? [1,2,...] : ','s');
%             fout_ket = [blok,'_prmtr_',indx,'.txt'];
%             fid2=fopen(fout_ket,'wt');
            fprintf(fid2, '=================================================================\n');    
            fprintf(fid2, 'Transformasi Datum/Koordinat  \n');
            fprintf(fid2, '-----------------------------------------------------------------\n');
            fprintf(fid2,'    \n');   
            fprintf(fid2,'Nama file titik sekutu : %s \n',nama_file); 
            fprintf(fid2,'Datum Asal : %s \n',datum);
            fprintf(fid2,'Datum Baru : DGN-95/WGS-84 \n');
            fprintf(fid2,'Koordinat Titik Sekutu : UTM Zone %d %s \n',utm_zone,hemis);
            fprintf(fid2,'========================================================================================================\n');    
            fprintf(fid2,'No   Titik          xlama          ylama          Xbaru         Ybaru      dx      dy     ddx      ddy  \n');
            fprintf(fid2,'--------------------------------------------------------------------------------------------------------\n');

            for i=1:r
                if (abs(dx(i)-meanx)-stdevx)>stdevx || (abs(dy(i)-meany)-stdevy)>stdevy
                    tanda = '***';
                else
                    tanda=' ';
                end
                fprintf(fid2, '%2d %12s %13.3f %13.3f %13.3f %13.3f %7.2f %7.2f %6.2f %6.2f %4s \n',i,char(titik(i)),xlama(i),ylama(i),Xbaru(i),Ybaru(i),dx(i),dy(i),abs(dx(i)-meanx)-stdevx,abs(dy(i)-meany)-stdevy,tanda);
            end
            fprintf(fid2, '-------------------------------------------------------------------------------------------------------\n');
            fprintf(fid2,'Standard deviasi x = %6.2f \n',stdevx);
            fprintf(fid2,'Standard deviasi y = %6.2f \n',stdevy);
            fprintf(fid2,'    \n');
            fprintf(fid2,'    \n');  
            if ~isempty(xl_tolak)
                fprintf(fid2, 'Titik sekutu yang tidak digunakan   \n');
                fprintf(fid2,'========================================================================================\n');    
                fprintf(fid2,'No   Titik          xlama          ylama          Xbaru         Ybaru      dx      dy   \n');
                fprintf(fid2,'----------------------------------------------------------------------------------------\n');

                for i=1:length(xl_tolak)
                    fprintf(fid2, '%2d %12s %13.3f %13.3f %13.3f %13.3f %7.2f %7.2f   \n',i,char(ttk_tolak(i)),xl_tolak(i),yl_tolak(i),Xb_tolak(i),Yb_tolak(i),...
                        Xb_tolak(i)-xl_tolak(i),Yb_tolak(i)-yl_tolak(i));
                end
                fprintf(fid2, '---------------------------------------------------------------------------------------\n');
            end
            
            Rotasi1 = 180/pi*3600*atan(-parameter2D_M(2)/parameter2D_M(1));
            skala1 = sqrt(parameter2D_M(1)^2+parameter2D_M(2)^2);
            % parameter 2D similarity Least Square
            fprintf(fid2, '========================================================================\n');
            fprintf(fid2, 'Parameter Transformasi Koordinat 2D Similarity \n');
            fprintf(fid2, '    (LeastSquare dan menggunakan Centroid)\n');
            fprintf(fid2, '------------------------------------------------------------------------\n');
            fprintf(fid2, 'a            = %20.17f +/- %20.17f \n',parameter2D_M(1),variansiM(1));    
            fprintf(fid2, 'b            = %20.17f +/- %20.17f \n',parameter2D_M(2),variansiM(2));
            fprintf(fid2, 'Tx           = %20.15f +/- %20.17f m\n',parameter2D_M(3),variansiM(3));    
            fprintf(fid2, 'Ty           = %20.15f +/- %20.17f m\n',parameter2D_M(4),variansiM(4));
            fprintf(fid2, 'Rotasi       = %20.12f detik\n',Rotasi1); 
            fprintf(fid2, 'Skala        = %20.12f \n',skala1); 
            fprintf(fid2, 'centroid-x   = %20.3f m\n',parameter2D_M(5));    
            fprintf(fid2, 'centroid-y   = %20.3f m\n',parameter2D_M(6));
            fprintf(fid2, 'RMS          = %20.3f m\n',variansiM(5));
            fprintf(fid2, '------------------------------------------------------------------------\n');
            fprintf(fid2,'    \n');

            Rotasi2 = 180/pi*3600*atan(-param2Dsim(2)/param2Dsim(1));
            skala2 = sqrt(param2Dsim(1)^2+param2Dsim(2)^2);
            % parameter 2D similarity Non-Least Square
            fprintf(fid2, '========================================================================\n');
            fprintf(fid2, 'Parameter Transformasi Koordinat 2D Similarity non-LeastSquare\n');
            fprintf(fid2, '    (Non-LeastSquare dan tanpa Centroid)\n');
            fprintf(fid2, '------------------------------------------------------------------------\n');
            fprintf(fid2, 'a            = %20.17f \n',param2Dsim(1));    
            fprintf(fid2, 'b            = %20.17f \n',param2Dsim(2));
            fprintf(fid2, 'Tx           = %20.15f m\n',param2Dsim(3));    
            fprintf(fid2, 'Ty           = %20.15f m\n',param2Dsim(4));
            fprintf(fid2, 'Rotasi       = %20.12f detik\n',Rotasi2); 
            fprintf(fid2, 'Skala        = %20.12f \n',skala2); 
            fprintf(fid2, 'RMS          = %20.3f m\n',param2Dsim(5));
            fprintf(fid2, '------------------------------------------------------------------------\n');
            fprintf(fid2,'    \n');

    
%             fclose(fid2);
%         end % pilihan untuk simpan dalam file txt
                 
      
        hit_ulang=input('Apakah akan menghitung ulang ? (y/t) [t] : ','s');
        if isempty(hit_ulang)
            hit_ulang='t';
        end

        if isequal(hit_ulang,'y') % memilih titik sekutu yg tidak digunakan dlm hitungan
            no_data=str2num(input('Pilih no titik sekutu yg tidak dipakai! [1,2,3...] : ','s'));
            ttk_temp=titik;
            x_temp=xlama;
            y_temp=ylama;
            X_temp=Xbaru;
            Y_temp=Ybaru;
    
            clear xlama ylama Xbaru Ybaru titik % menghapus memory berisi data
                
            if no_data==1
               titik=ttk_temp(2:n_ttk); 
               xlama=x_temp(2:n_ttk);
               ylama=y_temp(2:n_ttk);
               Xbaru=X_temp(2:n_ttk);
               Ybaru=Y_temp(2:n_ttk);
            elseif n_ttk==no_data
               titik=ttk_temp(1:n_ttk-1);  
               xlama=x_temp(1:n_ttk-1);
               ylama=y_temp(1:n_ttk-1);
               Xbaru=X_temp(1:n_ttk-1);
               Ybaru=Y_temp(1:n_ttk-1);  
            else
               titik(1:no_data-1)=ttk_temp(1:no_data-1);titik(no_data:n_ttk-1)=ttk_temp(no_data+1:n_ttk); 
               xlama(1:no_data-1)=x_temp(1:no_data-1);xlama(no_data:n_ttk-1)=x_temp(no_data+1:n_ttk);
               ylama(1:no_data-1)=y_temp(1:no_data-1);ylama(no_data:n_ttk-1)=y_temp(no_data+1:n_ttk);
               Xbaru(1:no_data-1)=X_temp(1:no_data-1);Xbaru(no_data:n_ttk-1)=X_temp(no_data+1:n_ttk);
               Ybaru(1:no_data-1)=Y_temp(1:no_data-1);Ybaru(no_data:n_ttk-1)=Y_temp(no_data+1:n_ttk);
            end
                
            ttk_tolak=[ttk_tolak ttk_temp(no_data)];
            xl_tolak=[xl_tolak x_temp(no_data)];
            yl_tolak=[yl_tolak y_temp(no_data)];
            Xb_tolak=[Xb_tolak X_temp(no_data)];
            Yb_tolak=[Yb_tolak Y_temp(no_data)];
        else
            hit_ulang='t';
        end
        
  
               
      end % while : perhitungan ulang dg memilih titik sekutu
    end % jumlah titik sekutu > 2
end % hitung file input baru
fclose(fid2);


