clear all
    fid=fopen('90AR_skt.txt');
    %fid=fopen('82KRK_Sekutu.txt');
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
    fclose(fid);
    [r c] = size (xl);
    dx=Xb-xl;
    dy=Yb-yl;
    n = length(dx);
    meanx = sum(dx)/n;
    stdevx = sqrt(sum((dx-meanx).^2/n));
    meany = sum(dy)/n;
    stdevy = sqrt(sum((dy-meany).^2/n));
    
    j=1;
    for i=1:r
        if (dx(i)-meanx)<stdevx
            xlama(j,:)=xl(i,:);
            Xbaru(j,:)=Xb(i,:);
            ylama(j,:)=yl(i,:);
            Ybaru(j,:)=Yb(i,:);
            j=j+1;
        else
            tanda(i)='A';
        end
    end
      
    for i=1:r
            i,char(titik(i)),xl(i),yl(i),Xb(i),Yb(i),dx(i),dy(i);
            disp(tanda(i));
        end
            
