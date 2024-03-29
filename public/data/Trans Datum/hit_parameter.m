function varargout = hit_parameter(varargin)
% hit_parameter M-file for hit_parameter.fig
%      hit_parameter, by itself, creates a new hit_parameter or raises the existing
%      singleton*.
%
%      H = hit_parameter returns the handle to a new hit_parameter or the handle to
%      the existing singleton*.
%
%      hit_parameter('CALLBACK',hObject,eventData,handles,...) calls the local
%      function named CALLBACK in hit_parameter.M with the given input arguments.
%
%      hit_parameter('Property','Value',...) creates a new hit_parameter or raises the
%      existing singleton*.  Starting from the left, property value pairs are
%      applied to the GUI before hit_parameter_OpeningFunction gets called.  An
%      unrecognized property name or invalid value makes property application
%      stop.  All inputs are passed to hit_parameter_OpeningFcn via varargin.
%
%      *See GUI Options on GUIDE's Tools menu.  Choose "GUI allows only one
%      instance to run (singleton)".
%
% See also: GUIDE, GUIDATA, GUIHANDLES

% Edit the above text to modify the response to help hit_parameter

% Last Modified by GUIDE v2.5 10-Jun-2010 10:09:28

% Begin initialization code - DO NOT EDIT

clc;

gui_Singleton = 1;
gui_State = struct('gui_Name',       mfilename, ...
                   'gui_Singleton',  gui_Singleton, ...
                   'gui_OpeningFcn', @hit_parameter_OpeningFcn, ...
                   'gui_OutputFcn',  @hit_parameter_OutputFcn, ...
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


% --- Executes just before hit_parameter is made visible.
function hit_parameter_OpeningFcn(hObject, eventdata, handles, varargin)
% This function has no output args, see OutputFcn.
% hObject    handle to figure
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)
% varargin   command line arguments to hit_parameter (see VARARGIN)

% Choose default command line output for hit_parameter
handles.output = hObject;

% Update handles structure
guidata(hObject, handles);


% UIWAIT makes hit_parameter wait for user response (see UIRESUME)
% uiwait(handles.figParameter);

set (handles.cbox3D,'Value',0);
set (handles.editOutput3D,'String','');
set (handles.editOutput3D,'Enable','off');
set (handles.btnFO3D,'Enable','off');

set (handles.cbox2DM,'Value',0);
set (handles.editOutput2DM,'String','');
set (handles.editOutput2DM,'Enable','off');
set (handles.btnFO2DM,'Enable','off');

set (handles.cbox2D,'Value',0);
set (handles.editOutput2D,'String','');
set (handles.editOutput2D,'Enable','off');
set (handles.btnFO2D,'Enable','off');

set (handles.cbox2DLAUF,'Value',0);
set (handles.editOutput2DLAUF,'String','');
set (handles.editOutput2DLAUF,'Enable','off');
set (handles.btnFO2DLAUF,'Enable','off');

set (handles.cboxAll,'Value',0);
set (handles.editOutputAll,'String','');
set (handles.editOutputAll,'Enable','off');
set (handles.btnFOAll,'Enable','off');

% --- Outputs from this function are returned to the command line.
function varargout = hit_parameter_OutputFcn(hObject, eventdata, handles) 

% Get default command line output from handles structure
varargout{1} = handles.output;


% direktori & file koordinat titik sekutu
function editfileTtkSekutu_Callback(hObject, eventdata, handles)


% Hints: get(hObject,'String') returns contents of editfileTtkSekutu as text
%        str2double(get(hObject,'String')) returns contents of editfileTtkSekutu as a double


% --- Executes during object creation, after setting all properties.
function editfileTtkSekutu_CreateFcn(hObject, eventdata, handles)

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end



% --- Executes on button press in btnfileTtkSekutu.
function btnfileTtkSekutu_Callback(hObject, eventdata, handles)

set (handles.editfileTtkSekutu,'String','');
[handles.nm_file_lama,handles.nm_path_lama] = uigetfile({'*.txt',...
    'Text Files (*.txt)';...
    '*.csv','File CSV (Comma Separated Value, *.csv)'; ...
    '*.*','Semua File (*.*)'},'Pilih File Input Koordinat Lama');
guidata (hObject,handles);

set (handles.editfileTtkSekutu,'String',strcat(handles.nm_path_lama,...
    handles.nm_file_lama));

% --- Executes on button press in pushbutton2.
function pushbutton2_Callback(hObject, eventdata, handles)

% set (handles.edit2,'String',strcat(handles.nm_path_baru,...
%     handles.nm_file_baru));

% File Parameter 3D in cbox3D.
function cbox3D_Callback(hObject, eventdata, handles)

% Hint: get(hObject,'Value') returns toggle state of cbox3D

if get(handles.cbox3D,'Value') ~= 1
    set (handles.editOutput3D,'Enable','off');
    set (handles.btnFO3D,'Enable','off');
else
    set (handles.editOutput3D,'Enable','on');
    set (handles.btnFO3D,'Enable','on');
end

% File Parameter 2D(Matriks) in cbox2DM
function cbox2DM_Callback(hObject, eventdata, handles)

% Hint: get(hObject,'Value') returns toggle state of cbox2DM

if get(handles.cbox2DM,'Value') ~= 1
    set (handles.editOutput2DM,'Enable','off');
    set (handles.btnFO2DM,'Enable','off');
else
    set (handles.editOutput2DM,'Enable','on');
    set (handles.btnFO2DM,'Enable','on');
end

% File Parameter 2D(non-Matriks) in cbox2D
function cbox2D_Callback(hObject, eventdata, handles)

% Hint: get(hObject,'Value') returns toggle state of cbox2D

if get(handles.cbox2D,'Value') ~= 1
    set (handles.editOutput2D,'Enable','off');
    set (handles.btnFO2D,'Enable','off');
else
    set (handles.editOutput2D,'Enable','on');
    set (handles.btnFO2D,'Enable','on');
end

function edit4_Callback(hObject, eventdata, handles)

% Hints: get(hObject,'String') returns contents of edit4 as text
%        str2double(get(hObject,'String')) returns contents of edit4 as a double

% File Parameter LAUF2D in cbox2DLAUF.
function cbox2DLAUF_Callback(hObject, eventdata, handles)

% Hint: get(hObject,'Value') returns toggle state of cbox2DLAUF

if get(handles.cbox2DLAUF,'Value') ~= 1
    set (handles.editOutput2DLAUF,'Enable','off');
    set (handles.btnFO2DLAUF,'Enable','off');
else
    set (handles.editOutput2DLAUF,'Enable','on');
    set (handles.btnFO2DLAUF,'Enable','on');
end

% --- Executes on button press in cboxAll.
function cboxAll_Callback(hObject, eventdata, handles)

% Hint: get(hObject,'Value') returns toggle state of cboxAll

if get(handles.cboxAll,'Value') ~= 1
    set (handles.editOutputAll,'Enable','off');
    set (handles.btnFOAll,'Enable','off');
else
    set (handles.editOutputAll,'Enable','on');
    set (handles.btnFOAll,'Enable','on');
end

% direktori & file parameter 3D Molodensky-Badekas
function editOutput3D_Callback(hObject, eventdata, handles)

% Hints: get(hObject,'String') returns contents of editOutput3D as text
%        str2double(get(hObject,'String')) returns contents of editOutput3D as a double


% --- Executes during object creation, after setting all properties.
function editOutput3D_CreateFcn(hObject, eventdata, handles)

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end


% --- Executes on button press in btnFO3D.
function pushbutton4_Callback(hObject, eventdata, handles)

set (handles.editOutput3D,'String','');
dd = pwd;
handles.direktori_parameter = uigetdir (dd,...
    'Set Direktori File Output Parameter');
guidata(hObject,handles);
set (handles.editOutput3D,'String',handles.direktori_parameter);


% --- Executes during object creation, after setting all properties.
function edit6_CreateFcn(hObject, eventdata, handles)

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

% direktori & file parameter 2D (Matriks & Centroid)
function editOutput2DM_Callback(hObject, eventdata, handles)

% Hints: get(hObject,'String') returns contents of editOutput2DM as text
%        str2double(get(hObject,'String')) returns contents of editOutput2DM as a double


% --- Executes during object creation, after setting all properties.
function editOutput2DM_CreateFcn(hObject, eventdata, handles)

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end


% --- Executes on button press in btnFO2DM.
function btnFO2DM_Callback(hObject, eventdata, handles)

set (handles.editOutput2DM,'String','');
dd = pwd;
handles.direktori_varx = uigetdir (dd,...
    'Set Direktori File Matriks Variansi');
guidata(hObject,handles);
set (handles.editOutput2DM,'String',handles.direktori_varx);

% --- Executes during object creation, after setting all properties.
function edit8_CreateFcn(hObject, eventdata, handles)

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

% direktori & file parameter 2D (non-Matriks)
function editOutput2D_Callback(hObject, eventdata, handles)

% Hints: get(hObject,'String') returns contents of editOutput2D as text
%        str2double(get(hObject,'String')) returns contents of editOutput2D as a double


% --- Executes during object creation, after setting all properties.
function editOutput2D_CreateFcn(hObject, eventdata, handles)

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end


% --- Executes on button press in btnFO2D.
function pushbutton8_Callback(hObject, eventdata, handles)

set (handles.editOutput2D,'String','');
dd = pwd;
handles.direktori_V = uigetdir (dd,...
    'Set Direktori File Matriks V');
guidata(hObject,handles);
set (handles.editOutput2D,'String',handles.direktori_V);

function editOutput2DLAUF_Callback(hObject, eventdata, handles)

% Hints: get(hObject,'String') returns contents of editOutput2DLAUF as text
%        str2double(get(hObject,'String')) returns contents of editOutput2DLAUF as a double


% --- Executes during object creation, after setting all properties.
function editOutput2DLAUF_CreateFcn(hObject, eventdata, handles)
% hObject    handle to editOutput2DLAUF (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    empty - handles not created until after all CreateFcns called

% Hint: edit controls usually have a white background on Windows.
%       See ISPC and COMPUTER.
if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end


% --- Executes on button press in btnFO2DLAUF.
function pushbutton9_Callback(hObject, eventdata, handles)


set (handles.editOutput2DLAUF,'String','');
dd = pwd;
handles.direktori_sigma = uigetdir (dd,...
    'Set Direktori File Simpangan Baku (sigma)');
guidata(hObject,handles);
set (handles.editOutput2DLAUF,'String',handles.direktori_sigma);

function editOutputAll_Callback(hObject, eventdata, handles)

% --- Executes during object creation, after setting all properties.
function editOutputAll_CreateFcn(hObject, eventdata, handles)

if ispc && isequal(get(hObject,'BackgroundColor'), get(0,'defaultUicontrolBackgroundColor'))
    set(hObject,'BackgroundColor','white');
end

% --- Executes on button press in btnFOAll.
function btnFOAll_Callback(hObject, eventdata, handles)

set (handles.editOutputAll,'String','');
dd = pwd;
handles.direktori_CM = uigetdir (dd,...
    'Set Direktori File matriks CM');
guidata(hObject,handles);
set (handles.editOutputAll,'String',handles.direktori_CM);

% --- Executes on button press in pushbutton11.
function pushbutton11_Callback(hObject, eventdata, handles)

set (handles.cbox3D,'Value',1);
set (handles.editOutput3D,'Enable','on');
set (handles.btnFO3D,'Enable','on');

set (handles.cbox2DM,'Value',1);
set (handles.editOutput2DM,'Enable','on');
set (handles.btnFO2DM,'Enable','on');

set (handles.cbox2D,'Value',1);
set (handles.editOutput2D,'Enable','on');
set (handles.btnFO2D,'Enable','on');

set (handles.cbox2DLAUF,'Value',1);
set (handles.editOutput2DLAUF,'Enable','on');
set (handles.btnFO2DLAUF,'Enable','on');

set (handles.cboxAll,'Value',1);
set (handles.editOutputAll,'Enable','on');
set (handles.btnFOAll,'Enable','on');

% --- Executes on button press in pushbutton12.
function pushbutton12_Callback(hObject, eventdata, handles)
% hObject    handle to pushbutton12 (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)

set (handles.cbox3D,'Value',0);
set (handles.editOutput3D,'String','');
set (handles.editOutput3D,'Enable','off');
set (handles.btnFO3D,'Enable','off');

set (handles.cbox2DM,'Value',0);
set (handles.editOutput2DM,'String','');
set (handles.editOutput2DM,'Enable','off');
set (handles.btnFO2DM,'Enable','off');

set (handles.cbox2D,'Value',0);
set (handles.editOutput2D,'String','');
set (handles.editOutput2D,'Enable','off');
set (handles.btnFO2D,'Enable','off');

set (handles.cbox2DLAUF,'Value',0);
set (handles.editOutput2DLAUF,'String','');
set (handles.editOutput2DLAUF,'Enable','off');
set (handles.btnFO2DLAUF,'Enable','off');

set (handles.cboxAll,'Value',0);
set (handles.editOutputAll,'String','');
set (handles.editOutputAll,'Enable','off');
set (handles.btnFOAll,'Enable','off');



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


% --- Executes on button press in btnProses.
function btnProses_Callback(hObject, eventdata, handles)

% asli
% in_lama = get(handles.editfileTtkSekutu,'String');
% in_baru = get(handles.edit2,'String');



% if isempty(in_lama) || isempty(in_baru)
%     errordlg ('Anda belum memasukkan file input/output');
% elseif (c1 == 0) && (c2 == 0) && (c3 == 0) && (c4 == 0) && (c5 == 0)
%     errordlg ('Anda belum memilih/memasukkan nama file output');
% end

% asli
% in_lama = load (in_lama);
% in_baru = load (in_baru);

%% Modifikasi oleh Hafzal Hanief
% baca file dari buku matlab
% [namafile,direktori]=uigetfile('*.txt','Load Data file');
% eval(['cd ''' direktori ''';']);
% finput = fopen(namafile);
% eval(['in_data=load(''' namafile ''')']); 

clc;

finput = get(handles.editfileTtkSekutu,'String');
disp(finput);
% in_baru = get(handles.edit2,'String');
  
in_data=load(finput);
% disp(in_data);
  
  xlama=in_data(:,1);
  ylama=in_data(:,2);
  Xbaru=in_data(:,3);
  Ybaru=in_data(:,4);

% memilih UTM zone dg popupmenu  
val = get(handles.popupUTMzone,'Value');
switch val
    case 1
        utm_zone = 46;% User selected the first item
    case 2
        utm_zone = 47;% User selected the second item
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
val = get(handles.popuphemis,'Value');
switch val
    case 1
        hemis = 'N';
    case 2
        hemis = 'S';
end

%  r = banyaknya data titik sekutu
[r c] = size (in_data);
 
[Lo,Bo] = utm2lb(xlama,ylama,utm_zone,hemis,'bessel');
[Ln,Bn] = utm2lb(Xbaru,Ybaru,utm_zone,hemis,'wgs84');

% mengisi tinggi titik sekutu dengan 0.0 karena tidak ada data tinggi
% terhadap ellipsoid/datum lama
    for g=1:r
        ho(g)=0.0;
        hn(g)=0.0;
    end
   
% Menghitung koordinat 3D XYZ pada sistem lama & baru    
[Xo,Yo,Zo] = LBH2XYZ (Lo,Bo,ho,'bessel');
[Xn,Yn,Zn] = LBH2XYZ (Ln,Bn,hn,'wgs84');
    
% Menghitung parameter transformasi dengan metode molodensky-badekas
in_lama(:,1)=Xo(:);
in_lama(:,2)=Yo(:);
in_lama(:,3)=Zo(:);
in_baru(:,1)=Xn(:);
in_baru(:,2)=Yn(:);
in_baru(:,3)=Zn(:);

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
sdv(4)= sqrt(varx(4,4));
sdv(5) = sqrt(varx(5,5));
sdv(6) = sqrt(varx(6,6));
sdv(7) = sqrt(varx(7,7));
sdv(8) = sqrt(sigma);

cb1 = get(handles.cbox3D,'Value');

% menulis hasil hitungan ke file parameter u hitungan berikutnya
if cb1 == 1
    out_3D = get(handles.editOutput3D,'String');
    %save (out_parameter, 'parameter3D','sdv','-ascii','-double');
    fid=fopen(out_3D,'wt');
    for i=1:3
        fprintf(fid, '%20.15f \n',parameter3D(i));
    end
    for i=4:6
        fprintf(fid, '%25.22f \n',parameter3D(i));
    end
    
    fprintf(fid, '%18.16f \n',parameter3D(7));
    
    for i=8:10
        fprintf(fid, '%20.12f \n',parameter3D(i));
    end

    % variansi, sigma & rms
    for i=1:3
        fprintf(fid, '%20.15f \n',sdv(i));
    end
    for i=4:6
        fprintf(fid, '%25.22f \n',sdv(i));
    end
    
    fprintf(fid, '%18.16f \n',sdv(7));
    fprintf(fid, '%18.16f \n',sdv(8));
    
    fclose(fid);
end

% Tx = parameter(1);
% Ty = parameter(2);
% Tz = parameter(3);
% alfa = parameter(4);
% beta = parameter(5);
% gamma = parameter(6);
% skala = parameter(7);
% Xm = CM(1);
% Ym = CM(2);
% Zm = CM(3);
% Hitungan parameter 3D berakhir di sini

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

% Rotasi = 180/pi*60*atan(-parameter(2)/parameter(1))
% skala = sqrt(a^2+b^2)
% xcentroid=centroid(1)
% ycentroid=centroid(2)

cb2 = get(handles.cbox2DM,'Value');

% Menulis ke file
if cb2 == 1
    out_Sim2D_M = get(handles.editOutput2DM,'String');
    % save (out_Sim2D_M, 'parameter2D_M','variansiM','-ascii','-double');
    
    fid=fopen(out_Sim2D_M,'wt');
    for i=1:4
        fprintf(fid, '%20.15f \n',parameter2D_M(i));
    end
    for i=5:6
        fprintf(fid, '%20.12f \n',parameter2D_M(i));
    end
    
    
    % variansi, sigma & rms
    for i=1:4
        fprintf(fid, '%20.15f \n',variansiM(i));
    end
        
    fprintf(fid, '%18.16f \n',variansiM(5));
    
    fclose(fid);

end
% Hitungan parameter 2D Similatiry Matriks berakhir di sini

% Menghitung parameter transformasi dengan metode Similarity 
% tanpa Matriks
[a,b,Tx,Ty,rms] = similarity2D(xlama,ylama,Xbaru,Ybaru);
param2Dsim(1) = a;
param2Dsim(2) = b;
param2Dsim(3) = Tx;
param2Dsim(4) = Ty;
param2Dsim(5) = rms;

cb3 = get(handles.cbox2D,'Value');


if cb3 == 1
    out_Sim2D = get(handles.editOutput2D,'String');
    % save (out_Sim2D, 'a','b','Tx','Ty','rms', '-ascii','-double');
    fid=fopen(out_Sim2D,'wt');
    for i=1:4
        fprintf(fid, '%20.15f \n',param2Dsim(i));
    end
            
    fprintf(fid, '%18.16f \n',param2Dsim(5));
    
    fclose(fid);
end
% Hitungan parameter 2D Similatiry berakhir di sini

% Menghitung parameter transformasi dengan metode Polinom LAUF2D 
[parameter2DLAUF,centroid,rmsL,sxxL] = LAUF2D(xlama,ylama,Xbaru,Ybaru);

% menambahkan pada array nilai centroid
parameter2DLAUF(7) = centroid(1);
parameter2DLAUF(8) = centroid(2);
% membuat array baru berisi variansi & rms dari parameter
variansiL(1) = sxxL(1);
variansiL(2) = sxxL(2);
variansiL(3) = sxxL(3);
variansiL(4) = sxxL(4);
variansiL(5) = sxxL(5);
variansiL(6) = sxxL(6);
variansiL(7) = rmsL;

cb4 = get(handles.cbox2DLAUF,'Value');

if cb4 == 1
    out_LAUF2D = get(handles.editOutput2DLAUF,'String');
    % save (out_LAUF2D, 'parameter2DLAUF','-ascii','-double');
    fid=fopen(out_LAUF2D,'wt');
    for i=1:6
        fprintf(fid, '%20.15f \n',parameter2DLAUF(i));
    end
    for i=7:8
        fprintf(fid, '%20.12f \n',parameter2DLAUF(i));
    end
    
    
    % variansi, sigma & rms
    for i=1:6
        fprintf(fid, '%20.15f \n',variansiL(i));
    end
        
    fprintf(fid, '%18.16f \n',variansiL(7));
    
    fclose(fid);
    
    
end
% Hitungan parameter Polinom LAUF2D berakhir di sini

% Menulis file paramter dg 4 metode di atas lengkap
cb5 = get(handles.cboxAll,'Value');
if cb5 == 1
    out_lengkap = get(handles.editOutputAll,'String');
    % save (out_lengkap,'parameter3D','sdv','parameter2D_M','variansiM','param2Dsim','parameter2DLAUF','variansiL','-ascii','-double');
    fid=fopen(out_lengkap,'wt');
    for i=1:3
        fprintf(fid, '%20.15f \n',parameter3D(i));
    end
    for i=4:6
        fprintf(fid, '%25.22f \n',parameter3D(i));
    end
    
    fprintf(fid, '%18.16f \n',parameter3D(7));
    
    for i=8:10
        fprintf(fid, '%20.12f \n',parameter3D(i));
    end

    % variansi, sigma & rms
    for i=1:3
        fprintf(fid, '%20.15f \n',sdv(i));
    end
    for i=4:6
        fprintf(fid, '%25.22f \n',sdv(i));
    end
    
    fprintf(fid, '%18.16f \n',sdv(7));
    fprintf(fid, '%18.16f \n',sdv(8));
    
    % parameter 2D similarity matriks
    
    for i=1:4
        fprintf(fid, '%20.15f \n',parameter2D_M(i));
    end
    for i=5:6
        fprintf(fid, '%20.12f \n',parameter2D_M(i));
    end
    
    
    % variansi, sigma & rms
    for i=1:4
        fprintf(fid, '%20.15f \n',variansiM(i));
    end
        
    fprintf(fid, '%18.16f \n',variansiM(5));
    
    % parameter 2D similarity non-matriks
    for i=1:4
        fprintf(fid, '%20.15f \n',param2Dsim(i));
    end
            
    fprintf(fid, '%18.16f \n',param2Dsim(5));
    
    % parameter 2D LAUF polinom
    for i=1:6
        fprintf(fid, '%20.15f \n',parameter2DLAUF(i));
    end
    for i=7:8
        fprintf(fid, '%20.12f \n',parameter2DLAUF(i));
    end
    % variansi, sigma & rms LAUF
    for i=1:6
        fprintf(fid, '%20.15f \n',variansiL(i));
    end
        
    fprintf(fid, '%18.16f \n',variansiL(7));
    
    fclose(fid);


end

msgbox ('Selamat, Pengolahan sudah berhasil');

set (handles.cbox3D,'Value',0);
set (handles.editOutput3D,'String','');
set (handles.editOutput3D,'Enable','off');
set (handles.btnFO3D,'Enable','off');

set (handles.cbox2DM,'Value',0);
set (handles.editOutput2DM,'String','');
set (handles.editOutput2DM,'Enable','off');
set (handles.btnFO2DM,'Enable','off');

set (handles.cbox2D,'Value',0);
set (handles.editOutput2D,'String','');
set (handles.editOutput2D,'Enable','off');
set (handles.btnFO2D,'Enable','off');

set (handles.cbox2DLAUF,'Value',0);
set (handles.editOutput2DLAUF,'String','');
set (handles.editOutput2DLAUF,'Enable','off');
set (handles.btnFO2DLAUF,'Enable','off');

set (handles.cboxAll,'Value',0);
set (handles.editOutputAll,'String','');
set (handles.editOutputAll,'Enable','off');
set (handles.btnFOAll,'Enable','off');

set (handles.editfileTtkSekutu,'String','');


% --- Executes on button press in btnMenuUtama.
function btnMenuUtama_Callback(hObject, eventdata, handles)
% hObject    handle to btnMenuUtama (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)

Koordinat ();
delete(handles.figParameter);

% --- Executes on button press in btnBantuan.
function btnBantuan_Callback(hObject, eventdata, handles)
% hObject    handle to btnBantuan (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)

open Help\'Pertamina -- Mencari Parameter.pdf'

% --- Executes on button press in pushbutton16.
function pushbutton16_Callback(hObject, eventdata, handles)
% hObject    handle to pushbutton16 (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)




% --- Executes on button press in btnReset.
function btnReset_Callback(hObject, eventdata, handles)
% hObject    handle to btnReset (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)

set (handles.editfileTtkSekutu,'String','');

set (handles.edit2,'String','');

set (handles.cbox3D,'Value',0);
set (handles.editOutput3D,'String','');
set (handles.editOutput3D,'Enable','off');
set (handles.btnFO3D,'Enable','off');

set (handles.cbox2DM,'Value',0);
set (handles.editOutput2DM,'String','');
set (handles.editOutput2DM,'Enable','off');
set (handles.btnFO2DM,'Enable','off');

set (handles.cbox2D,'Value',0);
set (handles.editOutput2D,'String','');
set (handles.editOutput2D,'Enable','off');
set (handles.btnFO2D,'Enable','off');

set (handles.cbox2DLAUF,'Value',0);
set (handles.editOutput2DLAUF,'String','');
set (handles.editOutput2DLAUF,'Enable','off');
set (handles.btnFO2DLAUF,'Enable','off');

set (handles.cboxAll,'Value',0);
set (handles.editOutputAll,'String','');
set (handles.editOutputAll,'Enable','off');
set (handles.btnFOAll,'Enable','off');



% --- Executes on button press in btnFO3D.
function btnFO3D_Callback(hObject, eventdata, handles)
% hObject    handle to btnFO3D (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)


% --- Executes on button press in btnFO2DLAUF.
function btnFO2DLAUF_Callback(hObject, eventdata, handles)
% hObject    handle to btnFO2DLAUF (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)


% --- Executes on button press in btnFO2D.
function btnFO2D_Callback(hObject, eventdata, handles)
% hObject    handle to btnFO2D (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)














% --- Executes on button press in btnSelesai.
function btnSelesai_Callback(hObject, eventdata, handles)
% hObject    handle to btnSelesai (see GCBO)
% eventdata  reserved - to be defined in a future version of MATLAB
% handles    structure with handles and user data (see GUIDATA)

close;















