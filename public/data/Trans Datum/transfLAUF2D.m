function [Xbaru,Ybaru] = transfLAUF2D(xlama,ylama,parameter)

a=parameter(1);
b=parameter(2);
c=parameter(3);  
d=parameter(4);
Tx=parameter(5);
Ty=parameter(6);
cent_x=parameter(7);
cent_y=parameter(8);
 
dx=xlama-cent_x;
dy=ylama-cent_y;
  
Xbaru = a*dx-b*dy+c*(dx.^2-dy.^2)-d*2*dx.*dy+Tx+cent_x;
Ybaru = a*dy+b*dx+d*(dx.^2-dy.^2)+c*2*dx.*dy+Ty+cent_y;
Vx=Xbaru-xlama;
Vy=Ybaru-ylama;
  

  