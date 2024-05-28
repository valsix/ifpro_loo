function piutang(name)
{	//script by rizalkhan, copyright protected
	var prefix = name.substr(0,5);
	var sufix = name.substr(7,2);
	
	var saldo_awal_usaha = prefix+'01'+sufix;
	var penyisihan_piut = prefix+'02'+sufix;
	var saldo_awal_piut = prefix+'03'+sufix;
	var saldo_akhir_usaha = prefix+'04'+sufix;
	var penyisihan_akhir = prefix+'05'+sufix;
	var saldo_akhir_piut = prefix+'06'+sufix;
	var rerata_piut = prefix+'07'+sufix;
	var realisasi_pend = prefix+'08'+sufix;
	var reduksi_pend = prefix+'09'+sufix;
	var pend_usaha = prefix+'10'+sufix;
	var jumlah_hari = prefix+'11'+sufix;
	var nilai = prefix+'12'+sufix;
		
	//menghitung saldo awal
	selisih([saldo_awal_usaha,penyisihan_piut],saldo_awal_piut);
	//menghitung saldo akhir
	selisih([saldo_akhir_usaha,penyisihan_akhir],saldo_akhir_piut);
	//rata-rata piutang
	rerata([saldo_awal_piut,saldo_akhir_piut],rerata_piut);
	//pendapatan usaha bersih
	selisih([realisasi_pend,reduksi_pend],pend_usaha);
	//nilai akhir
	relative([rerata_piut,pend_usaha,jumlah_hari],nilai);
	
}
function jumlah_rel(id, idval)
{
	jumlah ([id[0],id[1]],id[2]);
	relative ([id[2],id[3]],idval);
}
function kendali_resiko(name)
{       //V090101T1
        var prefix = name.substr(0,3);//V09
        var sufix = name.substr(name.length - 2,2);//T1
        var temp = '';

        var a = prefix+sufix;
        var b = prefix+'01'+sufix;
        var c = prefix+'0101'+sufix;
        var d = prefix+'02'+sufix;
        var e = prefix+'0201'+sufix;

        var nilai_a = 0;
        var nilai_b = 0;
        var nilai_c = document.getElementById(c).value.replace(/[^0-9.-]/g, '');
        var nilai_d = 0;
        var nilai_e = document.getElementById(e).value.replace(/[^0-9.-]/g, '');

        if(nilai_c == '') nilai_b = 0;
        else if(nilai_c == 0) nilai_b = 100*0.25;
        else if(nilai_c == 1) nilai_b = 75*0.25;
        else if(nilai_c == 2) nilai_b = 50*0.25;
        else if(nilai_c == 3) nilai_b = 25*0.25;
        else nilai_b = 0;

        temp = nilai_b.toString().indexOf('.');
        if (temp>0) nilai_b = nilai_b.toFixed(2);

        if(nilai_e == '') nilai_d = 0;
        else if(nilai_e == 0) nilai_d = 100*0.75;
        else if(nilai_e <= 25000000) nilai_d = 75*0.75;
        else if(nilai_e <= 50000000) nilai_d = 50*0.75;
        else if(nilai_e <= 100000000) nilai_d = 25*0.75;
        else nilai_d = 0;

        temp = nilai_d.toString().indexOf('.');
        if (temp>0) nilai_d = nilai_d.toFixed(2);

        nilai_a = parseFloat(nilai_b) + parseFloat(nilai_d);

        temp = nilai_a.toString().indexOf('.');

        document.getElementById(a).value=CurrencyFormatted(nilai_a);
        document.getElementById(b).value=CurrencyFormatted(nilai_b);
        document.getElementById(d).value=CurrencyFormatted(nilai_d);
}
function kendali_resiko2014(name)
{       //V090101T1
        var prefix = name.substr(0,3);//V09
        var sufix = name.substr(name.length - 2,2);//T1
        var temp = '';

        var a = prefix+sufix;
        var b = prefix+'01'+sufix;
        var c = prefix+'0101'+sufix;
        var d = prefix+'02'+sufix;
        var e = prefix+'0201'+sufix;

        var nilai_a = 0;
        var nilai_b = 0;
        var nilai_c = document.getElementById(c).value.replace(/[^0-9.-]/g, '');
        var nilai_d = 0;
        var nilai_e = document.getElementById(e).value.replace(/[^0-9.-]/g, '');

        if(nilai_c == '') nilai_b = 0;
        else if(nilai_c <=1) nilai_b = 100*0.25;
        else if(nilai_c >=2 && nilai_c <=5) nilai_b = 75*0.25;
        else if(nilai_c >=6 && nilai_c <=9) nilai_b = 50*0.25;
        else if(nilai_c >=10 && nilai_c <=12) nilai_b = 25*0.25;
        else nilai_b = 0;

        temp = nilai_b.toString().indexOf('.');
        if (temp>0) nilai_b = nilai_b.toFixed(2);

        if(nilai_e == '') nilai_d = 0;
        else if(nilai_e == 0) nilai_d = 100*0.75;
        else if(nilai_e >= 1 && nilai_e <= 100000000 ) nilai_d = 75*0.75;
        else if(nilai_e >= 100000001 && nilai_e <= 1000000000 ) nilai_d = 50*0.75;
        else if(nilai_e >= 1000000001) nilai_d = 25*0.75;
        else nilai_d = 0;

        temp = nilai_d.toString().indexOf('.');
        if (temp>0) nilai_d = nilai_d.toFixed(2);

        nilai_a = parseFloat(nilai_b) + parseFloat(nilai_d);

        temp = nilai_a.toString().indexOf('.');

        document.getElementById(a).value=CurrencyFormatted(nilai_a);
        document.getElementById(b).value=CurrencyFormatted(nilai_b);
        document.getElementById(d).value=CurrencyFormatted(nilai_d);
}


function relative(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = (a*c)/b;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=0; //Relative (Zero): jika 0/0 maka hasilnya 0%
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
}

function relativemax(id, idval, maxval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = (a*c)/b;
	if(result>maxval){
		result=maxval;
	}
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=0; //Relative (Zero): jika 0/0 maka hasilnya 0%
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
}

function relativenonzero(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = (a*c)/b;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=100; //Relative Non Zero: jika 0/0 maka hasilnya 100%
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
}

function samadengan(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	//if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	//else var c = 100;
	
	if(a!='')
	result = a/1;
       else if(b!='')
	result = b/1;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	//if (a==''||b=='') document.getElementById(idval).value='';
	//else if (a==0&&b==0) document.getElementById(idval).value=100;
	//else if (b==0) document.getElementById(idval).value=0;
	//else 
	document.getElementById(idval).value=CurrencyFormatted(result);
}

function properli(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = (a*c)/a;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=100;
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
}

function pert(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = ((a-b)*c/b);
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=100;
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
}

function prod_2013(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = a/b;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=100;
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
}

function selisih(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	
	result = a-b;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
	
}
function jumlah(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var sum = 0;
	
	for (var index=0; index<id.length; index++)
	{
		var value = document.getElementById(id[index]).value.replace(/[^0-9.-]/g, '');
		if(value!='')
		{
			sum+=parseFloat(value);
		}
	}	
	
	result = sum;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	document.getElementById(idval).value=CurrencyFormatted(result);
}
function rerata(id, idval)
{	//script by rizalkhan, copyright protected
	var result="------";
	
	var sum = 0;
	var act_num = 0;
	
	for (var index=0; index<id.length; index++)
	{
		var value = document.getElementById(id[index]).value.replace(/[^0-9.-]/g, '');
		if(value!='')
		{
			sum+=parseFloat(value);
			act_num++;
		}
	}
	
	if(act_num>0)
	{
		result = sum/act_num;
		var temp = result.toString().indexOf('.');
		if (temp>0) result = result.toFixed(2);
		document.getElementById(idval).value=CurrencyFormatted(result);
	}
	else document.getElementById(idval).value='';
	
}
function RemoveTrailingZero(amount,idval) //script by aim susuleft protected
{
	var minus = 'mns'+amount;
	minus = minus.split("-");
	var amountonly = amount.replace(/[^0-9.]/g, ''); //stripout thousand sep(s)
	var result = '';
	
	if(amountonly!='0')	{
		result = parseFloat(amountonly); //remove leading & trailing zero(s)
	}
	if(amountonly=='0') result = '0';
	if(minus[0]=='mns'&&minus[1]!=undefined&&result!='0') result = '-'+result;
	
	result=CurrencyFormatted(result); //put thousand sep(s) back on
	
	document.getElementById(idval).value=result;
}
function CurrencyFormatted(amount)
{	//script by rizalkhan, copyright protected
	var minus = 'mns'+amount;
	minus = minus.split("-");
	
	if(amount!=''&&minus[0]=='mns') var temp = minus[1].replace(/[^0-9.]/g, '');		
	else var temp = amount.toString().replace(/[^0-9.]/g, '');
	
	var bilangan=temp.split(".");
	
	var i = parseInt(bilangan[0]);
	amount = bilangan[0];
	
	var result = amount;
	if(i>=1000)
	{
		amount = amount.replace(/[^0-9.]/g, '');
		
		result = "";
		var leng=amount.length-3;
		var a=leng;
		result += amount.substring(leng)
		while (leng > 3)
		{	
			leng-=3
			result = amount.substring(leng,a)+","+result;
			a=leng;
		}
		leng=0;
		result = amount.substring(leng,a)+","+result;
	}
	else var result= amount.replace(/[^0-9.]/g, '');
	if(bilangan[1]!=undefined) result=result+"."+bilangan[1];
	if(minus[0]=='mns'&&minus[1]!=undefined) result = '-'+result;
	
	return result;
}
function percent(amount)
{	//script by rizalkhan, copyright protected
	return CurrencyFormatted(amount);
}
function ave_cc_2012 (name,num)
{
	var prefix = name.substr(0,5);
	var sufix = name.substr(name.length - 2,2);
	var med = '';
	var idval = prefix+med+sufix;
	var id = new Array();
	for(var index=0; index<num; index++)
	{	
		var nmed=index+1;
		if(nmed<10) var med = '0'+nmed.toString();
		else var med = nmed.toString();
		id[index] = prefix+med+sufix;
	}		
	rerata (id,idval);
}
function ave_yor_2012 (name,num)
{
	var prefix = name.substr(0,3);
	var sufix = name.substr(name.length - 2,2);
	var med = '';
	var idval = prefix+med+sufix;
	var id = new Array();
	for(var index=0; index<num; index++)
	{	
		var nmed=index+1;
		if(nmed<10) var med = '0'+nmed.toString();
		else var med = nmed.toString();
		id[index] = prefix+med+sufix;
	}		
	rerata (id,idval);
}
function audit_2012(name)
{
	var prefix = name.substr(0,5);//V15010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V15
	var a = prefix+'0101'+sufix;
	var b = prefix+'0102'+sufix;
	var c = prefix+'01'+sufix;
	var d = prefix+'02'+sufix;
	var n = pre+'01'+sufix;
	var nn = pre +'02'+sufix;
	var s = pre +'03'+sufix;
	var e = prefix+sufix;
	var t = pre+sufix;
	
	jumlah ([a,b],c);
	//relative ([c,d],e);
	relativenonzero ([c,d],e); //edit by aim 20150224: jika tidak ada temuan maka tidak ada tindak lanjut --> so 0/0 harusnya 100% bukan 0%
	rerata ([n,nn,s],t);
	
}

function audit_2013(name) //a,b,c
{
	var prefix = name.substr(0,5);//V15010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V15
	
	var a = pre+sufix;
	var n = pre+'01'+sufix;
	var nn = pre +'02'+sufix;
	var s = pre +'03'+sufix;
	//var ss = pre +'04'+sufix;
	var c = prefix+'01'+sufix;
	var d = prefix+'02'+sufix;
	var e = prefix+sufix;
	
	
	relative ([c,d],e);
	rerata ([n,nn,s],a);
	//rerata ([n,nn,s,ss],a);
}

function audit_2013_4(name) //a,b,c,d
{
	var prefix = name.substr(0,5);//V15010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V15
	
	var a = pre+sufix;
	var n = pre+'01'+sufix;
	var nn = pre +'02'+sufix;
	var s = pre +'03'+sufix;
	var ss = pre +'04'+sufix;
	var c = prefix+'01'+sufix;
	var d = prefix+'02'+sufix;
	var e = prefix+sufix;
	
	
	relative ([c,d],e);
	rerata ([n,nn,s,ss],a);
}

function properli_2014(name)
{	//script by rizalkhan, copyright protected
	var prefix = name.substr(0,5);//V15010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V15
	
	var a = pre+sufix;
	var n = pre+'01'+sufix;
	var nn = pre +'02'+sufix;
	var c = prefix+'01'+sufix;
	var d = prefix+'02'+sufix;
	var e = prefix+sufix;
	
	
	relative ([c,d],e);
	rerata ([n,nn],a);
}

function csr_2013(name)
{
	/*V2201T1
	V220101T1
	V220102T1
	V2202T1
	V220201T1
	V220202T1
	v22020201T1
	v22020202T1
	v22020203T1
	v22020204T1 */
	
	// var prefix = name.substr(0,5);//V2201
	// var sufix = name.substr(name.length - 2,2);//T1
	// var pre = name.substr(0,3);//V22
	// var s = name.substr()
	
	// var a = pre+sufix;
	// var n = pre+'01'+sufix;
	// var nn = pre +'02'+sufix;
	// var j = prefix +'02'+sufix;
	// var c = prefix+'0201'+sufix;
	// var d = prefix+'0202'+sufix;
	// var f = prefix+'0203'+sufix;
	// var g = prefix+'0204'+sufix;
	// var r1 ="V220101"+sufix;
	// var r2 ="V220102"+sufix;
	// var r3 ="V220201"+sufix;
	// var r4 ="V220202"+sufix;
	// var e = "V2201"+sufix;
	// var rr ="V2202"+sufix;
	
	// jumlah([c,d,f,g],j);
	// relative ([r1,r2],e);
	// relative ([r3,r4],rr);
	// rerata ([n,nn],a);
	var prefix = name.substr(0,3);//V09
	var sufix = name.substr(name.length - 2,2);//T1
	var temp = '';
		
	var csr = prefix+sufix;
	var penyaluran = prefix+'01'+sufix;
	var disalurkan = prefix+'0101'+sufix;
	var tersedia = prefix+'0102'+sufix;
	var pengembalian = prefix+'02'+sufix;
	var rata = prefix+'0201'+sufix;
	var total = prefix+'0202'+sufix;
	var lancar = prefix+'020201'+sufix;
	var klancar = prefix+'020202'+sufix;
	var diragukan = prefix+'020203'+sufix;
	var macet = prefix+'020204'+sufix;
	jumlah([lancar,klancar,diragukan,macet],total);
	relative([rata,total],pengembalian);
	relative([disalurkan,tersedia],penyaluran);
	rerata([pengembalian, penyaluran],csr);
}

function tar_lap_2013(name)
{
	var prefix = name.substr(0,5);//V21010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V21
	
	var a = pre+'03'+sufix;
	var b = pre+'0101'+sufix;
	var c = pre+'0102'+sufix;
	var d = pre+'0103'+sufix;
	var e = pre+'0104'+sufix;
	var bb = pre+'0201'+sufix;
	var cc = pre+'0202'+sufix;
	var dd = pre+'0203'+sufix;
	var ee = pre+'0204'+sufix;
	var f = pre+'01'+sufix;
	var g = pre+'02'+sufix;
	var n = 'T2103'+sufix;
	var id= pre+sufix;
	jumlah([b,c,d,e],f);
	jumlah([bb,cc,dd,ee],g);
	jumlah ([b,c,d,e,bb,cc,dd,ee],a);
	//relative([a,a],id);
	relative([a,a],id);

}

function tar_lap_2014(name)
{
	var prefix = name.substr(0,5);//V21010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V21
	
	var a = pre+'04'+sufix;
	var b = pre+'0101'+sufix;
	var c = pre+'0102'+sufix;
	var d = pre+'0103'+sufix;
	var e = pre+'0104'+sufix;
	var bb = pre+'0201'+sufix;
	var cc = pre+'0202'+sufix;
	var dd = pre+'0203'+sufix;
	var ee = pre+'0204'+sufix;
	var p = pre+'0301'+sufix;
	var q = pre+'0302'+sufix;
	var r = pre+'0303'+sufix;
	var s = pre+'0304'+sufix;
	var f = pre+'01'+sufix;
	var g = pre+'02'+sufix;
	var h = pre+'03'+sufix;
	var id= pre+sufix;
	var tmp = a.replace('R','V');
	jumlah([b,c,d,e],f);
	jumlah([p,q,r,s],h);
	jumlah([bb,cc,dd,ee],g);
	jumlah ([f,g,h],a);
	//relative([a,a],id);
	if(a.charAt(0)=='V'){
		relativemax([a,a],id,100);
	}else{
		relativemax([a,tmp],id,100);
	}
}

function capex_2013(name)
{
	var prefix = name.substr(0,5);//V15010101T1
	var sufix = name.substr(name.length - 2,2);
	var pre = name.substr(0,3);//V15
	
	var a = pre+sufix;
	var n = pre+'01'+sufix;
	var nn = pre +'02'+sufix;
	var c = prefix+'01'+sufix;
	var d = prefix+'02'+sufix;
	var e = prefix+sufix;
	
	
	relative ([c,d],e);
	rerata ([n,nn],a);
}

function tar_lap_2012 (name)
{
	//v180101t1
	var prefix = name.substr(0,3);//v18
	var sufix = name.substr(name.length - 2,2);//t1
	var id = new Array();
	id[0] = prefix+'0101'+sufix;id[5] = prefix+'0202'+sufix;
	id[1] = prefix+'0102'+sufix;id[6] = prefix+'0203'+sufix;
	id[2] = prefix+'0103'+sufix;id[7] = prefix+'0204'+sufix;
	id[3] = prefix+'0104'+sufix;idval = prefix+'03'+sufix;
	id[4] = prefix+'0201'+sufix;
	
	jumlah(id,idval);
	document.getElementById(prefix+sufix).value='100';
}
function real_lap_2012 (name)
{
	var prefix = name.substr(0,3);//v18
	var sufix = name.substr(name.length - 2,2);//t1
	var id = new Array();
	id[0] = prefix+'0101'+sufix;id[5] = prefix+'0202'+sufix;
	id[1] = prefix+'0102'+sufix;id[6] = prefix+'0203'+sufix;
	id[2] = prefix+'0103'+sufix;id[7] = prefix+'0204'+sufix;
	id[3] = prefix+'0104'+sufix;idval = prefix+'03'+sufix;
	id[4] = prefix+'0201'+sufix;
	
	jumlah(id,idval);
	
	var prefix_tar = 'T'+name.substr(1,2);//v18
	relative ([idval,prefix_tar+'03'+sufix],prefix+sufix);
}
function slg_2012(name)
{	//v190101t1
	var prefix = name.substr(0,5);//v1901
	var sufix = name.substr(name.length - 2,2);
	var id = new Array();
	id[0] = prefix+'0101'+sufix;id[5] = prefix+'0201'+sufix;
	id[1] = prefix+'0102'+sufix;id[6] = prefix+'0202'+sufix;
	id[2] = prefix+'0103'+sufix;id[7] = prefix+'0203'+sufix;
	id[3] = prefix+'0104'+sufix;id[8] = prefix+'0204'+sufix;
	id[4] = prefix+'0105'+sufix;id[9] = prefix+'0205'+sufix;
	idval = prefix+sufix;	
	rerata(id,idval);
}
function perhitungan_piutang(name)
{	//script by rizalkhan, copyright protected
	var prefix = name.substr(0,3);
	var sufix = name.substr(name.length - 2,2);
		
	var saldo_awal_usaha = prefix+'010101'+sufix;//03010101
	var penyisihan_piut = prefix+'010102'+sufix;//03010102
	var saldo_awal_piut = prefix+'0101'+sufix;//030101
	var saldo_akhir_usaha = prefix+'010201'+sufix;//03010201
	var penyisihan_akhir = prefix+'010202'+sufix;//03010202
	var saldo_akhir_piut = prefix+'0102'+sufix;//030102
	var rerata_piut = prefix+'01'+sufix;//0301
	var realisasi_pend = prefix+'0201'+sufix;//030201
	var reduksi_pend = prefix+'0202'+sufix;//030202
	var pend_usaha = prefix+'02'+sufix;//0302
	var jumlah_hari = prefix+'03'+sufix;//0303
	var nilai = prefix+''+sufix;//03
		
	//menghitung saldo awal
	selisih([saldo_awal_usaha,penyisihan_piut],saldo_awal_piut);
	//menghitung saldo akhir
	selisih([saldo_akhir_usaha,penyisihan_akhir],saldo_akhir_piut);
	//rata-rata piutang
	rerata([saldo_awal_piut,saldo_akhir_piut],rerata_piut);
	//pendapatan usaha bersih
	selisih([realisasi_pend,reduksi_pend],pend_usaha);
	//nilai akhir
	relative([rerata_piut,pend_usaha,jumlah_hari],nilai);
}
function lingkungan_tar(name)
{
	var prefix = name.substr(0,3);
	var sufix = name.substr(name.length - 2,2);
	var id = new Array();
	
	id[1]=prefix+'01'+'T4';
	id[0]=prefix+'02'+'T2';
	var idval =prefix+'T2';
		
	relative (id,idval);
	
	id[1]=prefix+'01'+'T4';
	id[0]=prefix+'02'+'T4';
	var idval =prefix+'T4';
	
	var result="------";

	var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
	var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
	if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
	else var c = 100;
	
	result = ((a*c)/b)/2;
	var temp = result.toString().indexOf('.');
	if (temp>0) result = result.toFixed(2);
	
	if (a==''||b=='') document.getElementById(idval).value='';
	else if (a==0&&b==0) document.getElementById(idval).value=100;
	else if (b==0) document.getElementById(idval).value=0;
	else document.getElementById(idval).value=CurrencyFormatted(result);
	
}
function lingkungan_rea(name)
{
	var prefix = name.substr(0,3);
	var sufix = name.substr(name.length - 2,2);
	var id = new Array();
	
	if(sufix=='T2')
	{
		id[1]=prefix+'01'+'T4';
		id[0]=prefix+'02'+'T2';
		var idval =prefix+'T2';
			
		relative (id,idval);
	}
	else if (sufix=='T4')
	{
		id[1]=prefix+'01'+'T4';
		id[0]=prefix+'02'+'T4';
		var idval =prefix+'T4';
		
		var result="------";

		var a = document.getElementById(id[0]).value.replace(/[^0-9.-]/g, '');
		var b = document.getElementById(id[1]).value.replace(/[^0-9.-]/g, '');
		if (id[2]!=undefined) var c =  document.getElementById(id[2]).value.replace(/[^0-9.-]/g, '');
		else var c = 100;
		
		result = ((a*c)/b)/2;
		var temp = result.toString().indexOf('.');
		if (temp>0) result = result.toFixed(2);
		
		if (a==''||b=='') document.getElementById(idval).value='';
		else if (a==0&&b==0) document.getElementById(idval).value=100;
		else if (b==0) document.getElementById(idval).value=0;
		else document.getElementById(idval).value=CurrencyFormatted(result);
	}	
}