<?php class db_mysql{
	var $version = '';
	var $querynum = 0;
	var $link;
	var $link2;
	var $tbl;
	var $charset;
	var $dbname;
	var $dbname2;
	var $info;
	var $args;
	
  function db_mysql($info){
  	parse_str($info['query'],$args);
  	$this->info=$info;
  	$this->args=$args;
    $port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
  	$host=$info['host'];
  	$user=$info['user'];
  	$pass=$info['pass'];
  	$host2=$args['host2'];!$host2 && $host2=$info['host'];
  	$user2=$args['user2'];!$user2 && $user2=$info['user'];
  	$pass2=$args['pass2'];!$pass2 && $pass2=$info['pass'];
  	$dbname2=$args['dbname2'];
  	$dbName=substr($info['path'], 1);
  	!$dbname2 && $dbname2=$dbName;
  	$pconnect=$args['pconnect']=='true';
  	$tablepre=$args['tablepre'];
  	$charset=$args['charset']?$args['charset']:'utf8';
		$func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect';
		if(!$this->link = $func($host.':'.$port, $user, $pass)) {
			return false;
		} else {
			mysql_select_db($dbName, $this->link);
			$this->charset=$charset;
			@$this->query('set names '.$charset);
		}
		$this->tbl=$tablepre;
		$this->dbname=$dbName;
    register_shutdown_function(array(&$this, 'close'));
    #从数据库
    $this->link2 = $func($host2.':'.$port, $user2, $pass2);
		$this->dbname2=$dbname2;
		mysql_select_db($dbname2, $this->link2);
  }
  
  function close(){
    mysql_close($this->link);
  }
  
	function version() {
		if(empty($this->version)) {
			$this->version = mysql_get_server_info($this->link);
		}
		return $this->version;
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return (($this->link) ? mysql_errno($this->link) : mysql_errno());
	}
  
  function query($sql,$type='') {
    $sql=str_replace(' #_',' '.$this->tbl,$sql);
    $sql=str_replace(' `#_',' `'.$this->tbl,$sql);
    if(strtolower(substr($sql,0,6))=='select'){
    	$query=@mysql_query($sql, $this->link2);
    }else{
    	$query=@mysql_query($sql, $this->link);
    }
		
		if(!$query)exit($this->errno().'['.$this->error().']');
		$this->querynum++;
		return $query;
  }
	function free_result($query) {
		return @mysql_free_result($query);
	}
  
  function getFields($table) {
    $tbl=$this->tbl;
    $table = $tbl.$table;
    $fields=array();
	$result=$this->query("SHOW FIELDS FROM $table");
	while ($key = D()->fetch_array($result)) {
		$fields[]=$key['Field'];
	}
	return $fields;
  }
  function fetch_array($sql) {
    return mysql_fetch_array($sql,MYSQL_ASSOC);
  }
	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}
  
  function rows($table,$vars="",$field='*') {
    $tbl=$this->tbl;
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    $result=$this->query("select count($field) as id from $table $vars");
    if(!$result)return 0;
    $rs=$this->fetch_array($result);
    return $rs['id'];
  }
  
  function checkKey($key){
    if(in_array($key,array('force','field','type','name'))){
      $key='`'.$key.'`';
    }
    return $key;
  }
  function insert($table,$arr) {
    $tbl=$this->tbl;
    $table = $tbl.$table;
    $A=array();
    foreach($arr as $k=>$v){
      $k=$this->checkKey($k);
      if(!is_numeric($v)){
        $A[$k]=var_export($v,true);
			}else{
        $A[$k]=$v;
			}
    }
    $this->query("insert into $table (".implode(',',array_keys($A)).")values(".implode(',',array_values($A)).")");
    return $this->insert_id();
  }
  function replace($table,$arr) {
    $tbl=$this->tbl;
    if(!$arr)return;
    $table = $tbl.$table;
    $A=array();
    foreach($arr as $k=>$v){
      $k=$this->checkKey($k);
      if(!is_numeric($v)){
        $v=var_export($v,true);
        if(substr($v,-2,2)=='\\\'')$v=substr($v,0,-2).'\'';
			}
			$A[]="`$k`=$v";
    }
    $query=$this->query("replace into $table set ".implode(',',$A));
    return $query;
  }
  function update($table,$arr,$vars) {
    $tbl=$this->tbl;
    if(!$arr)return;
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    $A=array();
    foreach($arr as $k=>$v){
      $k=$this->checkKey($k);
      if(!is_numeric($v)){
        $v=var_export($v,true);
        if(substr($v,-2,2)=='\\\'')$v=substr($v,0,-2).'\'';
			}
			$A[]=$k.'='.$v;
    }
    $query=$this->query("update $table set ".implode(',',$A)." $vars");
    return $query;
  }
  function delete($table,$vars) {
    $tbl=$this->tbl;
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    $query=$this->query("delete from $table $vars");
  }
  function select($table,$key,$vars=""){
    $tbl=$this->tbl;
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    $result=$this->query("select $key from $table $vars");
    if(!$result){
      return false;
    }else{
      $rs=mysql_fetch_array($result);
			$this->free_result($result);
      return $rs[$key];
    }
  }
  function one($table,$key,$vars=""){
    $tbl=$this->tbl;
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    $result=$this->query("select $key from $table $vars limit 1");
    if(!$result){
      return array();
    }else{
      $rs=$this->fetch_array($result);
			$this->free_result($result);
      return $rs;
    }
  }
  function autoindex($table){
    $tbl=$this->tbl;
    $table = $tbl.$table;
    $result=mysql_query("SHOW TABLE STATUS LIKE '$table'");
    if(!$result){
      return false;
    }else{
      $rs=mysql_fetch_array($result);
			$this->free_result($result);
      return $rs['Auto_increment'];
    }
  }
  function record($table,$key,$vars="",$limit=""){
    $tbl=$this->tbl;
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    if($limit){
      $limit = "limit $limit";
    }
    $k=explode(",",$key);
    $record = Array();
    $result=$this->query("select $key from $table $vars $limit");
    $j=0;
    if(!$result){
      return $record;
    }
    while($onez=$this->fetch_array($result)){
      $record[$j]=$onez;
      $j++;
    }
		$this->free_result($result);
    return $record;
  }
	function sql($table) {
    $tbl=$this->tbl;
    $table = $tbl.$table;
	  $res = mysql_query( "SHOW CREATE TABLE `$table`" ); // user是要获取的表名
		$row = mysql_fetch_row( $res ); 
	  return $row[1];
	}
	function createtable($sql) {
		$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
		$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
		return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
			(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT charset=".$this->charset : " TYPE=$type");
	}
	function runquery($sql) {
		$A = $ret = array();
		$num = 0;
		foreach(explode(";\n", trim($sql)) as $query) {
			$queries = explode("\n", trim($query));
			foreach($queries as $query) {
				$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
			}
			$num++;
		}
		unset($sql);
		foreach($ret as $query) {
			$query = trim($query);
			if($query) {
				if(substr($query, 0, 12) == 'CREATE TABLE') {
					$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
					$A[]=createtable($query);
				} else {
					$A[]=$query;
				}
			}
		}
		return $A;
	}
  function page($table,$key,$vars="",$maxperpage=20,$strs){
 		if($strs=='onez.admin' || $strs==''){
 			$strs=html()->assign('url').'?f='.html()->assign('f').'&page=*';
 		}else{
    	strstr($strs,'page=')==false && $strs.="&page=*";
 		}
    $tbl=$this->tbl;
    $table_=$table;
    $vars_=$vars;
    $pagename = $_SERVER['PHP_SELF'];
    $table = $tbl.$table;
    if($vars){
      $vars = "where $vars";
    }
    $thispage=isset($_GET['page']) ? intval($_GET['page']) : 1;
    if ($thispage=="" || !is_numeric($thispage)){
      $thispage=1;
    }
    $thispage=intval($thispage);
    if($thispage<1)$thispage=1;
    $totalput=$this->rows($table_,$vars_);
    if (($totalput %$maxperpage)==0){
      $PageCount=intval($totalput /$maxperpage);
    }else{
      $PageCount=intval($totalput /$maxperpage+1);
    } 
    if($thispage<1)$thispage=1;
    if($PageCount<1)$PageCount=1;
    if($thispage>$PageCount)$thispage=$PageCount;
    $sql="select $key from $table $vars limit ".(($thispage-1)*$maxperpage).",$maxperpage";
    
    $result=$this->query($sql);
    $record = Array();
    while($onez=$this->fetch_array($result)){
      $record[]=$onez;
    }
    $ms="";unset($A,$B);
    if($strs && $PageCount>0){
      $buffer = null;
      $index = '首页';
      $pre = '上一页';
      $next = '下一页';
      $last = '末页';
  
      if ($PageCount<=7) { 
        $range = range(1,$PageCount);
      } else {
        $min = $thispage - 3;
        $max = $thispage + 3;
        if ($min < 1) {
          $max += (3-$min);
          $min = 1;
        }
        if ( $max > $PageCount ) {
          $min -= ( $max - $PageCount );
          $max = $PageCount;
        }
        $min = ($min>1) ? $min : 1;
        $range = range($min, $max);
      }
      
      if ($thispage > 1) {
        $buffer .= "<a href='".str_replace('*',1,$strs)."'>{$index}</a> <a href='".str_replace('*',$thispage-1,$strs)."' class='prev'>{$pre}</a>";
      }
      foreach($range AS $one) {
        if ( $one == $thispage ) {
          $buffer .= "<a class='current'>{$one}</a>";
        } else {
          $buffer .= "<a href='".str_replace('*',$one,$strs)."'>{$one}</a>";
        }
      }
      if ($thispage < $PageCount) {
        $buffer .= "<a href='".str_replace('*',$thispage+1,$strs)."' class='nxt'>{$next}</a> <a href='".str_replace('*',$PageCount,$strs)."'>{$last}</a>";
      }
      $page='<div class="page-list">'.$buffer . '</div><div class="clear"></div>';
		}
    return array($record,$page);
  }
}
?>