<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class NotaDinasTujuan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function NotaDinasTujuan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("nota_tujuan_id", $this->getNextId("NOTA_TUJUAN_ID","NOTA_DINAS_TUJUAN")); 
				
		$str = "
		INSERT INTO NOTA_DINAS_TUJUAN (
			   NOTA_TUJUAN_ID, NOTA_DINAS_ID, NO_URUT, 
			   SATUAN_KERJA_ID, TERBACA) 
            VALUES ('".$this->getField("nota_tujuan_id")."',
					'".$this->getField("nota_dinas_id")."',
                    '".$this->getField("no_urut")."',
                    '".$this->getField("satuan_kerja_id")."',0
				)";
				
		$this->query = $str;
		//$this->reqNotaDinasId = $this->getField("nota_dinas_id");
		//echo $str;
		return $this->execQuery($str);
    }	
	
	function delete_buat_update()
	{		
		$str1 = "
		 		DELETE FROM NOTA_DINAS
                WHERE 
                  NOTA_TUJUAN_ID = '".$this->getField("nota_tujuan_id")."' ";
				  // AND satuan_kerja_id_tujuan = '".$this->getField("satuan_kerja_id_tujuan")."'
		$this->query = $str1;
		//echo $str1;
        return $this->execQuery($str1);
    }
	
	function delete_all()
	{		
		$str1 = "
		 		DELETE FROM NOTA_DINAS_TUJUAN
                WHERE 
                  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."' ";
				  // AND satuan_kerja_id_tujuan = '".$this->getField("satuan_kerja_id_tujuan")."'
		$this->query = $str1;
		//echo $str1;
        return $this->execQuery($str1);
    }
	
	function delete()
	{	
		$str0 = "
		 		DELETE FROM nota_dinas_attachment
                WHERE 
                  nota_dinas_id = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str0;
        $this->execQuery($str0);
			
		$str = "
		 		DELETE FROM nota_dinas_disposisi
                WHERE 
                  nota_dinas_id = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str;
        $this->execQuery($str);
		
		$str1 = "
		 		DELETE FROM nota_dinas
                WHERE 
                  nota_dinas_id = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function update_baca_dyna($stat='')
	{
		$str = "UPDATE ".$this->getField("TABLE")." SET
				  TERBACA = '1'
				WHERE ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				".$stat; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="")
	{
		$str = "
				SELECT 
				NOTA_TUJUAN_ID, NOTA_DINAS_ID, NO_URUT, 
				   A.SATUAN_KERJA_ID, TERBACA,
				   (SELECT X.NAMA FROM SATUAN_KERJA X WHERE A.SATUAN_KERJA_ID=X.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
				FROM NOTA_DINAS_TUJUAN A
				WHERE 1 = 1
				";
			  
		$str .= $statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$order;
		//echo $str;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsEntri($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT 
					 COUNT(*) ROWCOUNT
				FROM NOTA_DINAS A 
					LEFT JOIN KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
				WHERE 1 = 1	AND SATUAN_KERJA_ID_ASAL = ".$id."		
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= " ".$statement;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsSimple($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT MAX(NO_AGENDA) AS ROWCOUNT FROM NOTA_DINAS WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(NOMOR) AS ROWCOUNT FROM NOTA_DINAS WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
		
  }
?>