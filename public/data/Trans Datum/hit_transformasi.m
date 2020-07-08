function varargout = hit_transformasi(varargin)
% HIT_TRANSFORMASI M-file for hit_transformasi.fig
%      HIT_TRANSFORMASI, by itself, creates a new HIT_TRANSFORMASI or raises the existing
%      singleton*.
%
%      H = HIT_TRANSFORMASI returns the handle to a new HIT_TRANSFORMASI or the handle to
%      the existing singleton*.
%
%      HIT_TRANSFORMASI('CALLBACK',hObject,eventData,handles,...) calls the local
%      function named CALLBACK in HIT_TRANSFORMASI.M with the given input arguments.
%
%      HIT_TRANSFORMASI('Property','Value',...) creates a new HIT_TRANSFORMASI or raises the
%      existing singleton*.  Starting from the left, property value pairs are
%      applied to the GUI before hit_transformasi_OpeningFunction gets called.  An
%      unrecognized property name or invalid value makes property application
%      stop.  All inputs are passed to hit_transformasi_OpeningFcn via varargin.
%
%      *See GUI Options on GUIDE's Tools menu.  Choose "GUI allows only one
%      instance to run (singleton)".
%
% See also: GUIDE, GUIDATA, GUIHANDLES

% Edit the above text to modify the response to help hit_transformasi

% Last Modified by GUIDE v2.5 12-Jun-2010 18:35:08

% Begin initialization code - DO NOT EDIT
gui_Singleton = 1;
gui_State = struct('gui_Name',       mfilename, ...
                   'gui_Singleton',  gui_Singleton, ...
                   'gui_OpeningFcn', @hit_transformasi_OpeningFcn, ...
                   'gui_OutputFcn',  @hit_transformasi_OutputFcn, ...
                   'gui_LayoutFcn',  [] , ...
                   'gui_Callback',   []);
if nargin && ischar(varargin{1})
    gui_State.gui_Callback = str2func(varargin{1});
end

if nargout
    [varargout{1:nargout}] = gui_mainfcn(gui_State, varargin{:});
else
    gui_mainfcn(gui_State, varargin{:});
end
% End initialization code - DO NOT EDIT

% --- Executes just before hit_transformasi is made visible.
function hit_transformasi_OpeningFcn(hObject, eventdata, handles, varargin)
% This function has no output args, see OutputFcn.
% hObject    handle to figure
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)
% varargin   command line arguments to hit_transformasi (see VARARGIN)

% Choose default command line output for hit_transformasi
handles.output = hObject;

% Update handles structure
guidata(hObject, handles);

% UIWAIT makes hit_transformasi wait for user response (see UIRESUME)
% uiwait(handles.figure1);


% --- Outputs from this function are returned to the command line.
function varargout = hit_transformasi_OutputFcn(hObject, eventdata, handles) 
% varargout  cell array for returning output args (see VARARGOUT);
% hObject    handle to figure
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)

% Get default command line output from handles structure
varargout{1} = handles.output;



function editkoordlama_Callback(hObject, eventdata, handles)

function editkoordlama_CreateFcn(hObject, eventdata, handles)

if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

function editfileparameter_Callback(hObject, eventdata, handles)

function editfileparameter_CreateFcn(hObject, eventdata, handles)

if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

% --- Executes on button press in btnbrowsekoordlama.
function btnbrowsekoordlama_Callback(hObject, eventdata, handles)

[handles.nm_file_kor_lama,handles.nm_path_kor_lama] = uigetfile({...
    '*.plt','PLT Text Files (*.plt)';...
    '*.txt','Text Files (*.txt)';...
    '*.csv','File CSV (Comma Separated Value, *.csv)'; ...
    '*.*','Semua File (*.*)'},'Pilih File Koordinat Lama');
guidata (hObject,handles);
set (handles.editkoordlama,'String',strcat(handles.nm_path_kor_lama,...
    handles.nm_file_kor_lama));

% --- Executes on button press in btnbrowsefilepara.
function btnbrowsefilepara_Callback(hObject, eventdata, handles)
 
[handles.nm_file_param,handles.nm_path_param] = uigetfile({...
    '*.txt','Text Files (*.txt)';...
    '*.csv','File CSV (Comma Separated Value, *.csv)'; ...
    '*.*','Semua File (*.*)'},'Pilih File Koordinat Parameter');
guidata (hObject,handles);

set (handles.editfileparameter,'String',strcat(handles.nm_path_param,...
    handles.nm_file_param));

function editfilekoordoutput_Callback(hObject, eventdata, handles)
function editfilekoordoutput_CreateFcn(hObject, eventdata, handles)
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end


% --- Executes on button press in btnkoordbaru.
function btnkoordbaru_Callback(hObject, eventdata, handles)
set (handles.editfilekoordoutput,'String','');
dd=pwd;
handles.direktori = uigetdir (dd,'Set Direktori File Output');
guidata(hObject,handles);
set (handles.editfilekoordoutput,'String',handles.direktori);

function btnproses_Callback(hObject, eventdata, handles)
clc;
if isempty(get(handles.editkoordlama,'String'))
    errordlg ('Anda belum memasukkan nama input file koordinat lama');
elseif isempty(get(handles.editfileparameter,'String'))
    errordlg ('Anda belum memasukkan nama input file koordinat titik berat');
elseif isempty(get(handles.editfilekoordoutput,'String'))
    errordlg ('Nama file output belum anda masukkan');
end

if isempty (get(handles.editfilekoordoutput,'String'));
    errordlg ('Nama file output belum anda masukkan');
end

f_kor_lama = get (handles.editkoordlama,'String');
f_param = get (handles.editfileparameter,'String');

% membaca data *.plt file
fin = fopen(f_kor_lama);
    C = textscan(fin,'%d%f%f%n%s%s%s','delimiter',',');
    ttk = C{1};
    xlama = C{2};
    ylama = C{3};
    hlama = C{4};
    cod = C{5};
    linename = C{6};
    unit = C{7};
fclose(fin);
% membaca file parameter gabungan
param = load (f_param);
% mengambil parameter transformasi 3D Molodensky-Badekas
param3D = param(1:7);
koord_CM = param(8:10); % koordinat centroid



% memilih UTM zone dg popupmenu  
pilih = get(handles.popupUTMzone,'Value');
switch pilih
    case 1
        utm_zone = 46;
    case 2
        utm_zone = 47;
    case 3
        utm_zone = 48;
    case 4
        utm_zone = 49;
    case 5
        utm_zone = 50;
    case 6
        utm_zone = 51;
    case 7
        utm_zone = 52;
    case 8
        utm_zone = 53;    
end

% memilih UTM hemisphere dg popupmenu
pilih = get(handles.popuphemis,'Value');
switch pilih
    case 1
        hemis = 'N';
    case 2
        hemis = 'S';
end


[r c] = size (ttk);
% Menghitung LB dari UTM dgn  h=0.0
[Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'bessel');
    
for g=1:r
    % [Lo(g),Bo(g)] = utm2lb(xlama(g),ylama(g),utm_zone,'bessel')
    ho(g)=0.0;
end

% Menghitung koordinat ECEF XYZ dari LBh di datum lama
[Xo,Yo,Zo] = LBH2XYZ (Lo,Bo,ho,'bessel');
% memindahkan data ke satu matrik
xyz_input(:,1) = Xo(:);
xyz_input(:,2) = Yo(:);
xyz_input(:,3) = Zo(:);
[Xn,Yn,Zn] = inv_molobas (xyz_input,param3D,koord_CM);
%  
% % xyz = [x y z]; % memindahkan variable
% 
[Ln,Bn,hn] = ECEF2LBh(Xn,Yn,Zn,'wgs84');
[x3D,y3D,zoneutm] = deg2utm(Ln,Bn,'wgs84');

% menghitung selisih
dx3D = x3D - xlama;
dy3D = y3D - ylama;

for i=1:r-1
    jarak(i) = sqrt((xlama(i+1)-xlama(i))^2+(ylama(i+1)-ylama(i))^2);
end
% selesai menghitung dengan  transformasi 3D Molodensky-Badekas



% mengambil parameter transformasi 2D dg Matriks
param2DM = param(19:24);
dxo = xlama-param2DM(5); % untuk menghitung x hsl transformasi
dyo = ylama-param2DM(6);
x2DM = param2DM(1)*dxo-param2DM(2)*dyo+param2DM(3)+param2DM(5);
y2DM = param2DM(2)*dxo+param2DM(1)*dyo+param2DM(4)+param2DM(6);

dx2DM = x2DM-xlama; % selisih hasil 2DM dg lama
dy2DM = y2DM-ylama; % selisih hasil 2DM dg lama
% selesai menghitung dengan  transformasi 2DM Similarity

% mengambil parameter transformasi 2D tanpa Matriks
param2D = param(30:33);


x2D = param2D(1)*xlama-param2D(2)*ylama+param2D(3);
y2D = param2D(2)*xlama+param2D(1)*ylama+param2D(4);

dx2D = x2D-xlama; % selisih hasil 2D dg lama
dy2D = y2D-ylama; % selisih hasil 2D dg lama
% selesai menghitung dengan  transformasi 2D Similarity

% mengambil parameter transformasi 2D Polinom LAUF
param2DLAUF = param(35:42);
% memanggil fungsi transfLAUF2D
[x2DLAUF,y2DLAUF] = transfLAUF2D(xlama,ylama,param2DLAUF);

dx2DLAUF = x2DLAUF-xlama; % selisih hasil 2DLAUF dg lama
dy2DLAUF = y2DLAUF-ylama; % selisih hasil 2DLAUF dg lama
% selesai menghitung dengan  transformasi 2D Polinom LAUF

out_all = get(handles.editfilekoordoutput,'String');

fid=fopen(out_all,'wt');
    for i=1:r
        fprintf(fid, '%6d %6d %12.3f %12.3f %12.3f %12.3f %8.3f %8.3f %12.3f %12.3f %8.3f %8.3f%12.3f %12.3f %8.3f %8.3f%12.3f %12.3f %8.3f %8.3f \n',...
            i,ttk(i),xlama(i),ylama(i),...
            x3D(i),y3D(i),dx3D(i),dy3D(i),...
            x2DM(i),y2DM(i),dx2DM(i),dy2DM(i),...
            x2D(i),y2D(i),dx2D(i),dy2D(i),...
            x2DLAUF(i),y2DLAUF(i),dx2DLAUF(i),dy2DLAUF(i));
    end    
fclose(fid);

msgbox ('Selamat, Pengolahan sudah berhasil');

set (handles.editkoordlama,'String','');
set (handles.editfileparameter,'String','');
set (handles.editfilekoordoutput,'String','');

% --- Executes on button press in btnbantuan.
function btnbantuan_Callback(hObject, eventdata, handles)

open Help\'Pertamina -- Transformasi Koordinat.pdf'

% --- Executes on button press in btnmenu.
function btnmenu_Callback(hObject, eventdata, handles)

Koordinat ();
delete (handles.figure1);

% --- Executes on selection change in popupUTMzone.
function popupUTMzone_Callback(hObject, eventdata, handles)


% --- Executes during object creation, after setting all properties.
function popupUTMzone_CreateFcn(hObject, eventdata, handles)

if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

% --- Executes on selection change in popuphemis.
function popuphemis_Callback(hObject, eventdata, handles)


% --- Executes during object creation, after setting all properties.
function popuphemis_CreateFcn(hObject, eventdata, handles)

if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

% --- Executes on button press in btnselesai.
function btnselesai_Callback(hObject, eventdata, handles)
close;






