<?php
class DbAdmin
{
	//propriedades (variбveis)
	private $tipo; //ex.: mysql, pgsql, mssql, oracle, etc...
	private $conn; //identificador da conexгo com o SGBD
	
	//mйtodo construtor
	public function DbAdmin($tipo) {
		$this->tipo = $tipo;
	}
	
	//mйtodo que conecta com o banco de dados
	public function connect($host, $user, $pass, $base) {
		switch($this->tipo) {
			case 'mysql':
				$this->conn = mysql_connect($host, $user, $pass);
				mysql_select_db($base);
				break;
			case 'mysqli':
				$this->conn = mysqli_connect($host, $user, $pass, $base);
				if (!$this->conn) {
					printf("Connect failed: %s\n", mysqli_connect_error()); exit;
				} else { 
					$this->conn->set_charset("utf8mb4"); }
				break;
			case 'mssql':
				$this->conn = mssql_connect($host, $user, $pass);
				mssql_select_db($base);
				break;
			case 'pgsql':
				$string = "host=$host port=5432 dbname=$base user=$user password=$pass";
				$this->conn = pg_connect($string);
				break;	
		}//fim do switch
	}
	
	//mйtodo que executa um instruзгo SQL
	public function query($sql) {
		switch($this->tipo) {
			case 'mysql':
				$res = mysql_query($sql, $this->conn) or die(mysql_error());
				break;
			case 'mysqli':
				$res = mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
				break;
			case 'mssql':
				$res = mssql_query($sql, $this->conn) or die(mssql_get_last_message());
				break;
			case 'pgsql':
				$res = pg_query($this->conn, $sql) or die(pg_last_error());
				break;	
		}//fim do switch
		return $res;
	}
	
	//mйtodo que retorna o nъmero de linhas de um "ResultSet"
	public function rows($res) {
		switch($this->tipo) {
			case 'mysql':
				$num = mysql_num_rows($res);
				break;
			case 'mysqli':
				$num = mysqli_num_rows($res);
				break;
			case 'mssql':
				$num = mssql_num_rows($res);
				break;
			case 'pgsql':
				$num = pg_num_rows($res);
				break;	
		}//fim do switchdata row
		return $num;
	}
	
	//mйtodo que retorna um valor especнfico do "ResultSet"
	public function result($res, $lin, $col) {
		switch($this->tipo) {
			case 'mysql':
				$val = mysql_result($res, $lin, $col);
				break;
			case 'mysqli':
				$res->data_seek($lin); 
    			$datarow = $res->fetch_array(); 
    			$val = $datarow[$col];
				break;
			case 'mssql':
				$val = mssql_result($res, $lin, $col);
				break;
			case 'pgsql':
				$val = pg_fetch_result($res, $lin, $col);
				break;	
		}//fim do switch
		return $val;
	}
	
	//mйtodo que faz a manipulaзгo do "ponteiro" do ResultSet
	public function seek($res, $nro) {
		switch($this->tipo) {
			case 'mysql':
				mysql_data_seek($res, $nro);
				break;
			case 'mysqli':
				mysqli_data_seek($res, $nro);
				break;
			case 'mssql':
				mssql_data_seek($res, $nro);
				break;
			case 'pgsql':
				pg_result_seek($res, $nro);
				break;	
		}//fim do switch
	}
	
	//mйtodo que retorna um vetor com os dados da linha do ResultSet
	public function fetch($res) {
		switch($this->tipo) {
			case 'mysql':
				$vet = mysql_fetch_array($res);
				break;
			case 'mysqli':
				$vet = mysqli_fetch_array($res);
				break;
			case 'mssql':
				$vet = mssql_fetch_array($res);
				break;
			case 'pgsql':
				$vet = pg_fetch_array($res);
				break;	
		}//fim do switch
		return $vet;
	}
	
	//mйtodo que retorna o ID do ъltimo registro inserido
	public function lastid() {
		switch($this->tipo) {
			case 'mysql':
				$cod = mysql_insert_id($this->conn);
				break;
			case 'mysqli':
				$cod = mysqli_insert_id($this->conn);
				break;
			case 'mssql':
				$cod = mssql_insert_id($this->conn); //ajustar
				break;
			case 'pgsql':
				$cod = pg_insert_id($this->conn); //ajustar
				break;	
		}//fim do switch
		return $cod;
	}
	
	//mйtodo que fecha a conexгo com o SGBD
	public function close() {
		switch($this->tipo) {
			case 'mysql':
				mysql_close($this->conn);
				break;
			case 'mysqli':
				mysqli_close($this->conn);
				break;
			case 'mssql':
				mssql_close($this->conn);
				break;
			case 'pgsql':
				pg_close($this->conn);
				break;	
		}//fim do switch
	}
}
?>