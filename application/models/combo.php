<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once("Entity.php");

  class Combo extends Entity{ 

  	var $query;
	    /**
	    * Class constructor.
	    **/

	    function Combo()
	    {
	    	$this->Entity(); 
	    }

	    function selectByParamsBank($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from bank WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	    function selectByParamsPendidikan($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from PENDIDIKAN WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	    function selectByParamsAgama($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from AGAMA WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }


	    function selectByParamsJenisPerusahaan($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from JENIS_PERUSAHAAN WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }


	    function selectByParamsCustomer($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from CUSTOMER WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }


	    function selectByParamsLantaiLoo($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from LANTAI_LOO WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }


	    function selectByParamsLokasiLoo($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from LOKASI_LOO WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	    function selectByParamsLokasiLooDetil($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from LOKASI_LOO_DETIL WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	    function selectByParamsProduk($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from PRODUK WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	    function comboUtilityCharge($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT * from utility_charge WHERE 1 = 1
	    	"; 

	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}

	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	    function looutilityharge($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	    { 
	    	$str = "
	    	SELECT
	    		A1.NAMA, A1.KETERANGAN, A.*
	    	FROM loo_utility_charge A
	    	INNER JOIN utility_charge A1 ON A.UTILITY_CHARGE_ID = A1.UTILITY_CHARGE_ID
	    	WHERE 1 = 1
	    	"; 
	    	
	    	while(list($key,$val) = each($paramsArray))
	    	{
	    		$str .= " AND $key = '$val' ";
	    	}
	    	
	    	$str .= $statement." ".$order;
	    	$this->query = $str;
	    	return $this->selectLimit($str,$limit,$from); 
	    }

	} 
	?>