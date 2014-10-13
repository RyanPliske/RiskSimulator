dm 'log;clear;output;clear;';

proc iml;

      start CorrMatrix(tmp);

            N=NROW(tmp);             *Number of observations;
            SUM=tmp[+, ];            *sum of each column;
            x=tmp`*tmp-SUM`*SUM/N;     *Corrected crossproducts matrix;
            S=x/(N-1);             *Sample covariance matrix ;
            XBAR=SUM/N;            *Sample mean vector;
            SCALE=INV(SQRT(DIAG(x)));
            T=SCALE*x*SCALE;       *Sample correlation matrix;

            return (T);

      finish;

      start AvgCorr(tmp,w);

            T=CorrMatrix(tmp);       *Sample correlation matrix;

            rho_avg=(w[1,]*T*w[1,]`-w[1,]*w[1,]`)/(1-w[1,]*w[1,]`); *calculate the average correlation;
      
            return (rho_avg);

      finish;

    start StDev(tmp);
    *calculate standard deviation of the columns;

        *farm variance;
        N=NROW(tmp);             *Number of observations;
        avg=tmp[+, ]/n;            *average for each column;
        x=tmp[##,];             *Sum of squares for column;
        return (sqrt((x-n*Avg#Avg)/(n-1)));

    finish;

    start NearCorr(tmp);
    *find the nearest correlation matrix;

        old=tmp; j=1;

        do until (check<10**-20);
                  print j;
                  j=j+1;
            call eigen(val, vec, old);
            P=diag(val[,1])<>0; *make sure all eigenvalues are at least 0;
            new=vec*P*vec`;
            do i = 1 to nrow(new); *set diagonals to 1;
                new[i,i]=1;
            end;
            temp=new-old;
            check=sqrt(abs(temp[##,##])/old[##,##]);
            old=new;
        end;

        *make sure nonzero eigenvectors before we go;
        call eigen(val, vec, old);
        P=diag(val[,1])<>0; *make sure all eigenvalues are at least 0;
        new=vec*P*vec`;

        tmp=new;

    finish;

    n=500; *number of farms in sample;
    n_s=500; *number of simulations;
    w=j(n_s,n,1/n); *make weights equal for now;
      *w[1:n_s,1]=1;
    *w[1:n_s,2:n]=0/99;
      /*w1=j(n_s,189,850/189);
      w2=j(n_s,342,7807/342);
      w3=j(n_s,41,2418/41);
      w4=j(n_s,99,8244/99);
      w5=j(n_s,59,6809/59);
      w6=j(n_s,72,11259/72);
      w7=j(n_s,53,10540/53);
      w8=j(n_s,27,6410/27);
      w9=j(n_s,170,61039/170);
      w10=j(n_s,225,161400/225);
      w11=j(n_s,177,239195/177);
      w12=j(n_s,59,160013/59);
      *w12=j(n_s,58,2000*58)||j(n_s,1,160013-2000*58);
      w=w1||w2||w3||w4||w5||w6||w7||w8||w9||w10||w11||w12;
      w_sum=w[1,+];
      w=w*(1/w_sum);
      n=ncol(w);*/

    Ml=150; *mean yield lower;
    Mu=200; *mean yield uppper;
    Sl=25; *standard deviation lower;
    Su=45; *standard deviation upper;
    Cl=.5; *correlation lower;
    Cu=.9; *correlation upper;

    *create a correlation matrix;
      C=uniform(j(n,n,555))*(Cu-Cl)+j(n,n,Cl); *fill correlation matrix with values;
      /*do x_ = 0 to 9; *spatial correlation;
            do y_ = 1 to 10;
                  do x = 0 to 9;
                        do y = 1 to 10;
                              if abs(x-x_)+abs(y-y_)=0 then c[x_*10+y_,x*10+y]=1;
                              else if abs(x-x_)+abs(y-y_)=1 then c[x_*10+y_,x*10+y]=.9;
                              else if abs(x-x_)+abs(y-y_)=2 then c[x_*10+y_,x*10+y]=.7;
                              else c[x_*10+y_,x*10+y]=.5;
                        end;
                  end;
            end;
      end;*/
      /*do x = 1 to 100; *uplands/bottomlands;
            do y = 1 to 100;
                  if x > 90 & y > 90 then c[x,y]=.9;
                  else if y <= 90 & x <= 90 then c[x,y]=.7;
                  else c[x,y]=.5;
            end;
      end;*/
      /*do x = 1 to 100; *negative correlation;
            do y = x to 100;
                  C[x,y]=.9-.4/n*(y-1);
                  C[y,x]=C[x,y];
            end;
      end;*/
      do i = 1 to nrow(C); *set diagonals to 1;
        C[i,i]=1;
    end;
    call NearCorr(C);
    rho_avg=(w[1,]*C*w[1,]`-w[1,]*w[1,]`)/(1-w[1,]*w[1,]`); *calculate the average correlation;

    *farm mean yield;
    M=uniform(j(1,n,1324))*(Mu-Ml)+j(1,n,Ml); *set yields within range;
      *M=(uniform(j(1,n-10,1324))*(Mu-Ml)+j(1,n-10,Ml)+120)||(uniform(j(1,10,1324))*(Mu-Ml)+j(1,10,Ml)+160); *uplands/bottomlands;

    *Use Iman and Conover to generate random yields;
        call eigen(val, vec, C); *create the eigenvector and eigenvalue of the corr matrix;
        P=diag(val[,1]<>0); *diagnolize the eigenvalues;
        P=vec*sqrt(P[,]); *take the square root of the eigenvalues;

        rnd=uniform(j(n_s,n,1234)); *syntax: j(rows, columns, seed);
        do i = 1 to ncol(rnd); *rank matrix;
            rnk=rnk||rank(rnd[,i]);
        end;
            rnk=rnk[,]/(nrow(rnk)+1); *convert ranks to uniform deviates;
        rnk=probit(rnk[,]); *convert uniform deviates to standard normals;
        CSND=rnk*t(P);

            *uniform distribution;
            YF_raw=repeat((1:n_s)`/(n_s+1)`,1,n);
            YF_raw=YF_raw#(STD*sqrt(12))+(M-std*sqrt(3));
            do i = 1 to n; *sort farm yields;
                  YF=YF||YF_raw[rank(CSND[,i]),i];
            end;

            /*Use Gaussian copula; *this doesn't really work now;
        call eigen(val, vec, C); *create the eigenvector and eigenvalue of the corr matrix;
        P=diag(val[,1]<>0); *diagnolize the eigenvalues;
        P=vec*sqrt(P[,]); *take the square root of the eigenvalues;

        rnd=uniform(j(n_s,n,1234)); *syntax: j(rows, columns, seed);
        rnk=probit(rnd[,]); *convert uniform deviates to standard normals;
        CSND=rnk*t(P);

            *create chi square/df for each row in gaussian copula to convert it to t;
            df=1; *degrees of freedom for t copula;
            d=j(n_s,1,.);
            call randgen(d,'CHISQUARE',df);
            d=repeat(sqrt(d/df),1,ncol(CSND)); *divide by degrees of freedom;
            CSND=CSND[,]/d[,];
            u=probt(CSND[,],df,0);
            CSND=probit(u); *makes this into normal draws;*/

        STD=uniform(j(1,n,13247))*(Su-Sl)+j(1,n,Sl);*create vector of standard deviations;
            /*do i = 1 to 100; *upland/bottomland;
                  if i > 90 then STD[1,i]=STD[1,i]+40;
                  else STD[1,i]=STD[1,i]+25;
            end;*/
            /*do i = 1 to 100; *negative correlation;
                  std[1,i]=25+30/n*(i-1);
            end;*/

            *beta distribution;
            /*l=j(nrow(m),ncol(m),0); 
            u=j(nrow(m),ncol(m),1.2)#m;
            x_bar=(m-l)/(u-l);
            v=(std#std)/((u-l)#(u-l));
            a=x_bar#(x_bar#(j(nrow(m),ncol(m),1)-x_bar)/v-j(nrow(m),ncol(m),1));
            a=shape(a,nrow(rnk),ncol(a));
            b=(j(nrow(m),ncol(m),1)-x_bar)#(x_bar#(j(nrow(m),ncol(m),1)-x_bar)/v-j(nrow(m),ncol(m),1));
            b=shape(b,nrow(rnk),ncol(b));
            YF_raw=betainv(repeat((1:n_s)`/(n_s+1)`,1,n),a[,],b[,])#(shape(u,nrow(rnk),ncol(u))-shape(l,nrow(rnk),ncol(l)))+shape(l,nrow(rnk),ncol(l)); *farm yields ;
            do i = 1 to n; *sort farm yields;
                  YF=YF||YF_raw[rank(CSND[,i]),i];
            end;*/

            *normal distribution;
            *YF=M+CSND#STD; *farm yields;

            

        YC=j(n_s,n,.);
        YC=w#YF; *county yields;
        YC=YC[,+];

    SDF=stdev(YF)#w[1,];
    SDF=SDF[,+];
    SDC=StDev(YC);
    rho=(SDC/SDF)**2;
      
      rho_avg_act=AvgCorr(YF,w);
      w_sum=w[1,+];
FLoss=((m*.75-YF)<>0)*5.68;
*FLoss=FLoss[,]/(5.68*m[,]); *prem rate;
FLoss=FLoss#w;
FLoss=FLoss[:,+];
aYC=(YC[,]-YC[:,1])/sqrt(rho_avg_act)+YC[:,1];
ACLoss=((j(nrow(aYC),ncol(aYC),aYC[:,1])*.75-aYC)<>0)*5.68;
*ACLoss=ACLoss[,]/(5.68*j(nrow(aYC),ncol(aYC),aYC[:,1]));  *prem rate;
ACLoss=ACLoss[:,+];
CLoss=((j(nrow(YC),ncol(YC),YC[:,1])*.75-YC)<>0)*5.68;
*CLoss=CLoss[,]/(5.68*j(nrow(YC),ncol(YC),YC[:,1]));  *prem rate;
CLoss=CLoss[:,+];

      print SDF SDC rho rho_avg rho_avg_act w_sum FLoss ACLoss CLoss;

    create temp from m; append from m; close temp;
   

quit;
