<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class NotaDinas extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function NotaDinas()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("nota_dinas_id", $this->getNextId("NOTA_DINAS_ID","NOTA_DINAS")); 
		
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		
		$str = "
		INSERT INTO NOTA_DINAS(NOTA_DINAS_ID, NO_AGENDA, TAHUN, NOMOR, TANGGAL,TANGGAL_BATAS,JENIS,TIPE,
								KEPADA,PERIHAL,KLASIFIKASI_ID,SATUAN_KERJA_ID_ASAL,SATUAN_KERJA_ID_TUJUAN,ISI,CATATAN,TANGGAL_ENTRI,USER_ID,NAMA_USER, KLASIFIKASI_JENIS, SURAT_ASAL
            )
            VALUES ('".$this->getField("nota_dinas_id")."',
					'".$this->getField("no_agenda")."',
					".$this->NowYear.",
                    '".$this->getField("nomor")."',
                    ".$this->getField("tanggal").",
                    ".$this->getField("tanggal_batas").",
                    '".$this->getField("jenis")."',
					'".$this->getField("tipe")."',
                    '".$this->getField("kepada")."',
                    '".$this->getField("perihal")."',
                    '".$this->getField("klasifikasi_id")."',
                    '".$this->getField("satuan_kerja_id_asal")."',					
                    '".$this->getField("satuan_kerja_id_tujuan")."',
                    '".$this->getField("isi")."',
                    '".$this->getField("catatan")."',
                    '".$this->tanggal."',
                    '".$this->getField("user_id")."',
                    '".$this->getField("nama_user")."',
					'".$this->getField("klasifikasi_jenis")."',
					'".$this->getField("surat_asal")."'
				)";
				
		$this->query = $str;
		$this->reqNotaDinasId = $this->getField("nota_dinas_id");
		$this->reqNotaDinasNomor = $this->getField("nomor");
		$this->id = $this->getField("klasifikasi_id");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function insert_tujuan()
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
	
    function update()
	{
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		//Auto-generate primary key(s) by next max value (integer) 		
		$str = "
		UPDATE NOTA_DINAS SET  
			   TAHUN = ".$this->NowYear.",
			   NOMOR = '".$this->getField("nomor")."',
			   NO_AGENDA = '".$this->getField("no_agenda")."',
			   TANGGAL = ".$this->getField("tanggal").",
			   TANGGAL_BATAS = ".$this->getField("tanggal_batas").",
			   JENIS = '".$this->getField("jenis")."',
			   TIPE = '".$this->getField("tipe")."',
			   KEPADA = '".$this->getField("kepada")."',
			   PERIHAL = '".$this->getField("perihal")."',
			   KLASIFIKASI_ID = ".$this->getField("klasifikasi_id").",
			   SURAT_ASAL= ".$this->getField("surat_asal").",
			   SATUAN_KERJA_ID_TUJUAN = '".$this->getField("satuan_kerja_id_tujuan")."',
			   ISI = '".$this->getField("isi")."',
			   CATATAN = '".$this->getField("catatan")."',
			   TANGGAL_ENTRI = '".$this->tanggal."',
			   USER_ID = '".$this->getField("user_id")."',
			   NAMA_USER = '".$this->getField("nama_user")."',
			   KLASIFIKASI_JENIS= '".$this->getField("klasifikasi_jenis")."'
		   WHERE NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."'
				"; 
				$this->query = $str;
				$this->id = $this->getField("klasifikasi_id");
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
		 		DELETE FROM NOTA_DINAS_ATTACHMENT
                WHERE 
                  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str0;
        $this->execQuery($str0);
		
		$str4 = "
		 		DELETE FROM FEEDBACK_NOTA_DINAS
                WHERE 
                  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str4;
        $this->execQuery($str4);
		
		/*$str = "
		 		DELETE FROM nota_dinas_disposisi
                WHERE 
                  nota_dinas_id = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str;
        $this->execQuery($str);
		*/
		
		$str1 = "
		 		DELETE FROM NOTA_DINAS
                WHERE 
                  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function update_dyna()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE NOTA_DINAS A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE NOTA_DINAS_ID = ".$this->getField("nota_dinas_id")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $id="")
	{
		$str = "
                SELECT    
					A.NOTA_DINAS_ID , A.NO_AGENDA , A.TAHUN , A.NOMOR , 
					A.TANGGAL , A.TANGGAL_BATAS , A.JENIS , A.KEPADA , KLASIFIKASI_JENIS,
					A.TIPE , A.SATUAN_KERJA_ID_ASAL , A.PERIHAL , A.KLASIFIKASI_ID ,  
					CASE WHEN A.KLASIFIKASI_ID IS NULL
					THEN A.SURAT_ASAL
					ELSE (SELECT X.NAMA FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID)
					END DATA_SURAT_ASAL,
					(SELECT X.KODE_SO FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID) KLASIFIKASI_KODE,
					(SELECT X.NAMA FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID) KLASIFIKASI_NAMA,
					(SELECT X.NOTA_TUJUAN_ID FROM NOTA_DINAS_TUJUAN X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = '".$id."') NOTA_DINAS_TUJUAN_ID,
					A.SATUAN_KERJA_ID_TUJUAN , A.ISI , A.CATATAN , 
					A.TANGGAL_ENTRI , A.USER_ID , A.NAMA_USER 
        		FROM NOTA_DINAS A
				WHERE 1=1
			   "; //(SELECT NAMA FROM SATUAN_KERJA X WHERE X.KODE_SO = A.KLASIFIKASI_ID)
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsNotaDinasTujuan($paramsArray=array(),$limit=-1,$from=-1, $id="")
	{
		$str = "
				SELECT 
				A.SATUAN_KERJA_ID_ASAL,
				CASE WHEN (SELECT X.NOTA_TUJUAN_ID FROM NOTA_DINAS_TUJUAN X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = '".$id."') IS NOT NULL
				THEN (SELECT X.NOTA_TUJUAN_ID FROM NOTA_DINAS_TUJUAN X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = '".$id."')
				WHEN (SELECT X.NOTA_TUJUAN_ID FROM NOTA_DINAS_DISPOSISI X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID_TUJUAN = '".$id."') IS NOT NULL
				THEN (SELECT X.NOTA_TUJUAN_ID FROM NOTA_DINAS_DISPOSISI X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID_TUJUAN = '".$id."')
				ELSE (SELECT X.NOTA_TUJUAN_ID FROM NOTA_DINAS_TUJUAN X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND ROWNUM = 1)
				END NOTA_DINAS_TUJUAN_ID,
				CASE WHEN (SELECT X.TERDISPOSISI FROM NOTA_DINAS_TUJUAN X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = '".$id."') IS NOT NULL
				THEN (SELECT X.TERDISPOSISI FROM NOTA_DINAS_TUJUAN X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = '".$id."')
				ELSE
					(SELECT X.TERDISPOSISI FROM NOTA_DINAS_DISPOSISI X WHERE A.NOTA_DINAS_ID = X.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID_TUJUAN = '".$id."')
				END 
				TERDISPOSISI
				FROM NOTA_DINAS A
				WHERE 1=1
			   "; //(SELECT NAMA FROM SATUAN_KERJA X WHERE X.KODE_SO = A.KLASIFIKASI_ID)
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRiwayat($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
                SELECT
					(SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL,
                	(SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID) TUJUAN,
					B.TERBACA, B.SATUAN_KERJA_ID
        		FROM NOTA_DINAS A
				LEFT JOIN NOTA_DINAS_TUJUAN B ON A.NOTA_DINAS_ID = B.NOTA_DINAS_ID
				LEFT JOIN SATUAN_KERJA C ON  B.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID 
				WHERE 1=1
			   "; //(SELECT NAMA FROM SATUAN_KERJA X WHERE X.KODE_SO = A.KLASIFIKASI_ID)
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsUser($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		//(SELECT X.KODE_SO FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID) || ' - ' || (SELECT X.NAMA FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID) KLASIFIKASI, 
		
		/*CASE
                   WHEN C.SATUAN_KERJA_ID = ".$id." THEN COALESCE (A.TERBACA, 0)
                   ELSE COALESCE (C.TERBACA, 0)
                END STATUS_BACA,*/
		$str = "
		SELECT DISTINCT B.SATUAN_KERJA_ID, A.NOTA_DINAS_ID, A.TAHUN, A.NO_AGENDA,
                A.NOMOR, A.KLASIFIKASI_JENIS KLASIFIKASI,
				CASE WHEN A.KLASIFIKASI_ID IS NULL THEN A.SURAT_ASAL ELSE B.NAMA END DATA_SURAT_ASAL,
				(SELECT X.NOTA_DINAS_DISPOSISI_ID FROM NOTA_DINAS_DISPOSISI X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID_TUJUAN = ".$id."
				AND X.NOTA_TUJUAN_ID = D.NOTA_TUJUAN_ID
				) NOTA_DINAS_DISPOSISI_ID,
				CASE 
					WHEN (SELECT COALESCE(TERBACA, 0) FROM NOTA_DINAS_DISPOSISI X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID_TUJUAN = ".$id."
					AND X.NOTA_TUJUAN_ID = D.NOTA_TUJUAN_ID
					) = 0 THEN 1
					WHEN (SELECT DISTINCT COALESCE(TERBACA, 0) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = ".$id.") = 0 THEN 1
					ELSE 2 
				END  TERBACA,
				A.TIPE, B.NAMA SATUAN_KERJA_ASAL,
                CASE
                   WHEN D.SATUAN_KERJA_ID = ".$id." THEN 'NOTA DINAS'
                   ELSE 'DISPOSISI'
                END JENIS_SURAT,
                TO_CHAR (A.TANGGAL, 'DD/MM/YY') TANGGAL_BB, A.TANGGAL,
                C.TANGGAL_DISPOSISI,
                CASE
                   WHEN D.SATUAN_KERJA_ID = ".$id." THEN 1
                   ELSE 0
                END STATUS_DISPOSISI,
                CASE
                   WHEN D.SATUAN_KERJA_ID = ".$id." THEN (SELECT COALESCE (X.TERDISPOSISI, 0) FROM NOTA_DINAS X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID)
                   ELSE (SELECT COALESCE (X.TERDISPOSISI, 0) FROM NOTA_DINAS_DISPOSISI X WHERE X.SATUAN_KERJA_ID_TUJUAN = ".$id." AND X.NOTA_DINAS_ID = A.NOTA_DINAS_ID
				   AND X.NOTA_TUJUAN_ID = D.NOTA_TUJUAN_ID
				   )
                END N_DISPOSISI,
                CASE
                   WHEN D.SATUAN_KERJA_ID = ".$id." THEN 0
                   ELSE 1
                END BACA_DISPOSISI,
				CASE 
					WHEN D.SATUAN_KERJA_ID = ".$id." THEN COALESCE (D.TERBACA, 0)
					ELSE COALESCE (C.TERBACA, 0)
				END N_BACA,
				CASE 
					WHEN D.SATUAN_KERJA_ID = ".$id." THEN COALESCE (D.TERBACA, 0)
					ELSE COALESCE (C.TERBACA, 0)
				END STATUS_BACA,
				A.PERIHAL,
                B.NAMA SATUAN_KERJA_TUJUAN,
                (SELECT COUNT (FEEDBACK_ID) FROM FEEDBACK_NOTA_DINAS X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID) FEEDBACK
           FROM NOTA_DINAS A 
		   		LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_ASAL = B.SATUAN_KERJA_ID
                LEFT JOIN NOTA_DINAS_DISPOSISI C ON A.NOTA_DINAS_ID = C.NOTA_DINAS_ID AND (C.SATUAN_KERJA_ID_ASAL = ".$id." OR C.SATUAN_KERJA_ID_TUJUAN = ".$id." )
                LEFT JOIN NOTA_DINAS_TUJUAN D ON A.NOTA_DINAS_ID = D.NOTA_DINAS_ID AND D.SATUAN_KERJA_ID = ".$id."
          WHERE 1 = 1
		  AND (D.SATUAN_KERJA_ID = ".$id." OR C.SATUAN_KERJA_ID_TUJUAN = ".$id.")
		".$statement;
		
		/*(SELECT COALESCE(TERBACA, 0) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = ".$id.") N_BACA,
		(SELECT COALESCE(TERBACA, 0) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = ".$id.") STATUS_BACA,*/
				
		/*AND (   UPPER (A.PERIHAL) LIKE '%%'
                 OR UPPER (A.NO_AGENDA) LIKE '%%'
                 OR UPPER (A.NOMOR) LIKE '%%'
                 OR UPPER (B.NAMA) LIKE '%%'
                )*/
				
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
					GROUP BY 
					A.NOTA_DINAS_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.KLASIFIKASI_JENIS, A.TIPE, B.NAMA, A.TANGGAL, B.NAMA, A.PERIHAL, C.TANGGAL_DISPOSISI, B.SATUAN_KERJA_ID, A.SATUAN_KERJA_ID_TUJUAN, C.SATUAN_KERJA_ID_ASAL,
                	A.TERBACA, A.TANGGAL_BATAS, C.TANGGAL_BATAS, C.TERBACA, C.SATUAN_KERJA_ID_TUJUAN, D.SATUAN_KERJA_ID, C.TERBACA, D.TERBACA, A.KLASIFIKASI_ID, A.SURAT_ASAL, D.NOTA_TUJUAN_ID
		".$order;
		
		$this->query = $str;
		
	
		
		/*$str = "
				SELECT 
				NOTA_DINAS_ID, NO_AGENDA, NOMOR, TANGGAL, JENIS, KEPADA, 
				   PERIHAL, TIPE,
				   C.NAMA SATUAN_KERJA_ASAL, 
				   CASE 
						WHEN (SELECT COALESCE(TERBACA, 0) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = ".$id.") = 0 THEN 1
						ELSE 2 
				   END  TERBACA,
				   (SELECT COALESCE(TERBACA, 0) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID AND X.SATUAN_KERJA_ID = ".$id.") N_BACA,
				   A.KLASIFIKASI_JENIS KLASIFIKASI,
				   CASE WHEN A.SATUAN_KERJA_ID_TUJUAN = ".$id." THEN 0 ELSE 1 END BACA_DISPOSISI
				FROM NOTA_DINAS A 
					LEFT JOIN SATUAN_KERJA C ON  A.SATUAN_KERJA_ID_ASAL = C.SATUAN_KERJA_ID   
					INNER JOIN NOTA_DINAS_TUJUAN D ON A.NOTA_DINAS_ID = D.NOTA_DINAS_ID AND D.SATUAN_KERJA_ID = ".$id."
				WHERE 1 = 1		
				";*/
			  /*CASE WHEN TO_DATE(A.TANGGAL_BATAS,'YYYY-MM-DD HH24:MI:SS') < TO_DATE(NOW(),'YYYY-MM-DD HH24:MI:SS') AND COALESCE(D.TERBACA, 0) = 0 THEN 0 
				WHEN TO_DATE(A.TANGGAL_BATAS,'YYYY-MM-DD HH24:MI:SS') >= TO_DATE(NOW(),'YYYY-MM-DD HH24:MI:SS') AND COALESCE(D.TERBACA, 0) = 0 THEN 1 
			  ELSE 2 END  TERBACA*/
			  
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function selectByParamsTujuan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
				SELECT 
				NOTA_TUJUAN_ID, NOTA_DINAS_ID, NO_URUT, 
				   SATUAN_KERJA_ID, TERBACA
				FROM NOTA_DINAS_TUJUAN
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
	
	function selectByParamsPenelusuran($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
			   SELECT 
			   D.NOTA_TUJUAN_ID,
			   NO_AGENDA || ' - ' || NOMOR NOTA_DINAS_MERGE, TIPE,
			   (SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID=D.SATUAN_KERJA_ID) SATUAN_KERJA_TUJUAN,
			   NOTA_DINAS_ID, NO_AGENDA, NOMOR, TANGGAL, JENIS, KEPADA, PERIHAL,
			   (SELECT X.KODE_SO FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID) || ' - ' || (SELECT X.NAMA FROM SATUAN_KERJA X WHERE A.KLASIFIKASI_ID=X.SATUAN_KERJA_ID) KLASIFIKASI,
			   CASE WHEN A.KLASIFIKASI_ID IS NULL THEN A.SURAT_ASAL ELSE C.NAMA END DATA_SURAT_ASAL,
			   C.NAMA SATUAN_KERJA_ASAL,
			   CASE
				  WHEN (SELECT COALESCE (D.TERBACA, 0)
						  FROM NOTA_DINAS_TUJUAN X
						 WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID
						   AND X.SATUAN_KERJA_ID = D.SATUAN_KERJA_ID GROUP BY X.SATUAN_KERJA_ID) = 0
					 THEN 1
				  ELSE 2
			   END TERBACA,
			   (SELECT COALESCE (D.TERBACA, 0)
				  FROM NOTA_DINAS_TUJUAN X
				 WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID
				   AND X.SATUAN_KERJA_ID = D.SATUAN_KERJA_ID GROUP BY X.SATUAN_KERJA_ID) N_BACA
		  FROM NOTA_DINAS A 
			   LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_ID_ASAL = C.SATUAN_KERJA_ID
			   INNER JOIN NOTA_DINAS_TUJUAN D ON A.NOTA_DINAS_ID = D.NOTA_DINAS_ID
		 WHERE 1 = 1 AND (UPPER (A.PERIHAL) LIKE '%%') AND SATUAN_KERJA_ID_ASAL = ".$id."	
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
	
	function selectByParamsEntri($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		//B.KODE_SO || ' - ' || B.NAMA KLASIFIKASI
		$str = "
				SELECT 
				NOTA_DINAS_ID, NO_AGENDA, NOMOR, TANGGAL, JENIS, KEPADA, TIPE,
				CASE WHEN A.KLASIFIKASI_ID IS NULL THEN A.SURAT_ASAL ELSE B.NAMA END DATA_SURAT_ASAL,
				   PERIHAL, B.NAMA KLASIFIKASI, KLASIFIKASI_ID, (SELECT COUNT(NOTA_TUJUAN_ID) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID) JUMLAH_TUJUAN,
       				(SELECT SUM(TERBACA) FROM NOTA_DINAS_TUJUAN X WHERE X.NOTA_DINAS_ID = A.NOTA_DINAS_ID) BACA_ALL
				FROM NOTA_DINAS A 
					LEFT JOIN SATUAN_KERJA B ON A.KLASIFIKASI_ID = B.SATUAN_KERJA_ID
				WHERE 1 = 1	AND SATUAN_KERJA_ID_ASAL = ".$id."	
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
	
	function getCountByParamsPenelusuran($paramsArray=array(), $statement='', $id='')
	{
		$str = "
		SELECT 
			   COUNT(*) ROWCOUNT
		  FROM NOTA_DINAS A 
			   LEFT JOIN KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
			   LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_ID_ASAL = C.SATUAN_KERJA_ID
			   INNER JOIN NOTA_DINAS_TUJUAN D ON A.NOTA_DINAS_ID = D.NOTA_DINAS_ID
		 WHERE 1 = 1 AND SATUAN_KERJA_ID_ASAL = ".$id."	
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
		
    function getCountByParamsUser($paramsArray=array(), $statement='', $id='')
	{
		$str = " 
		SELECT COUNT(*) ROWCOUNT
		FROM(
		SELECT DISTINCT A.NOTA_DINAS_ID
           FROM NOTA_DINAS A 
		   		LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_ASAL = B.SATUAN_KERJA_ID
                LEFT JOIN NOTA_DINAS_DISPOSISI C ON A.NOTA_DINAS_ID = C.NOTA_DINAS_ID AND (C.SATUAN_KERJA_ID_ASAL = ".$id." OR C.SATUAN_KERJA_ID_TUJUAN = ".$id." )
                LEFT JOIN NOTA_DINAS_TUJUAN D ON A.NOTA_DINAS_ID = D.NOTA_DINAS_ID AND D.SATUAN_KERJA_ID = ".$id."
          WHERE 1 = 1
		  AND (D.SATUAN_KERJA_ID = ".$id." OR C.SATUAN_KERJA_ID_TUJUAN = ".$id.")
		".$statement;
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."
					GROUP BY 
					A.NOTA_DINAS_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.KLASIFIKASI_JENIS, A.TIPE, B.NAMA, A.TANGGAL, B.NAMA, A.PERIHAL, C.TANGGAL_DISPOSISI, B.SATUAN_KERJA_ID, A.SATUAN_KERJA_ID_TUJUAN, C.SATUAN_KERJA_ID_ASAL,
                	A.TERBACA, A.TANGGAL_BATAS, C.TANGGAL_BATAS, C.TERBACA, C.SATUAN_KERJA_ID_TUJUAN, D.SATUAN_KERJA_ID, C.TERBACA, D.TERBACA
		)";
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
		
  }
?>