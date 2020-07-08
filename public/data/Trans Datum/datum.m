% List datum sebagai input ke berbagai fungsi di program transformasi
% koordinat. fungsi ini memiliki input nama datum (nm_datum), dan mengembalikan nilai
% sumbu panjang ellipsoid (a) dan penggepengan ellipsoid (f). Dibuat oleh
% Teguh Purnama Sidiq pada 19 Juli 2007. Dikutip dari berbagai sumber.

function [a f] = datum (nm_datum)

switch lower(nm_datum)
    case {'wgs84','wgs 84','world geodetic system 84','wgs 1984','WGS84','WGS-84'}
        a = 6378137.0;
        f = 1/298.257223563;
    case {'Bessel','bessel','besel','bessel 1841','besel 1841'}
        a = 6377397.155;
        f = 1/299.1528128156;
    case {'airy','airy 1830','airy1830'}
        a = 6377563;
        f = 1/299.325;
    case {'everest','everest 1830','everest1830'}
        a = 6377276.0;
        f = 1/300.802;
    case {'clarke','clarke 1866','clarke1866'}
        a = 6378206.0;
        f = 1/294.978;
    case {'helmert','helmert 1907','helmert1907'}
        a = 6379200.0;
        f = 1/298.300;
    case {'hayford','hayford 1909','hayford1909'}
        a = 6378388.0;
        f = 1/297.000;
    case {'nad-27','nad 27'}
        a = 6378206.4;
        f = 1/294.9786982;
    case {'krassovsky','krassovsky 1948','krassovsky1948','krassovsky48'...
            'krassovsky 48'}
        a = 6378245.0;
        f = 1/298.300;
    case {'wgs 1960','wgs 60','wgs-60'}
        a = 6378165.0;
        f = 1/298.3;
    case {'wgs 1966','wgs 66','wgs-66'}
        a = 6378145.0;
        f = 1/298.25;
    case {'wgs 1972','wgs 72','wgs-72'}
        a = 6378135.0;
        f = 1/298.26;
    case {'grs 1967','grs 67','grs-67'}
        a = 6378160.0;
        f = 1/298.247167427;
    case {'grs 1980','grs 80','grs-80'}
        a = 6378137.0;
        f = 1/298.257222101;
end