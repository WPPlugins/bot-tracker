<?php
//----------------------------------------------------------------------
//  CrawlTrack 2.3.0
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.fr
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: searchenginesposition.php
//----------------------------------------------------------------------

//nusoap
/*

NuSOAP - Web Services Toolkit for PHP

Copyright (c) 2002 NuSphere Corporation

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

If you have any questions or comments, please email:

Dietrich Ayala
dietrich@ganx4.com
http://dietrich.ganx4.com/nusoap

NuSphere Corporation
http://www.nusphere.com

*/

class nusoap_base {

	var $title = 'NuSOAP';
	var $version = '0.6.3';
	var $error_str = false;
    var $debug_str = '';
	// toggles automatic encoding of special characters
	var $charencoding = true;

    /**
	*  set schema version
	*
	* @var      XMLSchemaVersion
	* @access   public
	*/
	var $XMLSchemaVersion = 'http://www.w3.org/2001/XMLSchema';
	
    /**
	*  set default encoding
	*
	* @var      soap_defencoding
	* @access   public
	*/
	//var $soap_defencoding = 'UTF-8';
    var $soap_defencoding = 'ISO-8859-1';

	/**
	*  load namespace uris into an array of uri => prefix
	*
	* @var      namespaces
	* @access   public
	*/
	var $namespaces = array(
		'SOAP-ENV' => 'http://schemas.xmlsoap.org/soap/envelope/',
		'xsd' => 'http://www.w3.org/2001/XMLSchema',
		'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
		'SOAP-ENC' => 'http://schemas.xmlsoap.org/soap/encoding/',
		'si' => 'http://soapinterop.org/xsd');
	/**
	* load types into typemap array
	* is this legacy yet?
	* no, this is used by the xmlschema class to verify type => namespace mappings.
	* @var      typemap
	* @access   public
	*/
	var $typemap = array(
	'http://www.w3.org/2001/XMLSchema' => array(
		'string'=>'string','boolean'=>'boolean','float'=>'double','double'=>'double','decimal'=>'double',
		'duration'=>'','dateTime'=>'string','time'=>'string','date'=>'string','gYearMonth'=>'',
		'gYear'=>'','gMonthDay'=>'','gDay'=>'','gMonth'=>'','hexBinary'=>'string','base64Binary'=>'string',
		// derived datatypes
		'normalizedString'=>'string','token'=>'string','language'=>'','NMTOKEN'=>'','NMTOKENS'=>'','Name'=>'','NCName'=>'','ID'=>'',
		'IDREF'=>'','IDREFS'=>'','ENTITY'=>'','ENTITIES'=>'','integer'=>'integer','nonPositiveInteger'=>'integer',
		'negativeInteger'=>'integer','long'=>'integer','int'=>'integer','short'=>'integer','byte'=>'integer','nonNegativeInteger'=>'integer',
		'unsignedLong'=>'','unsignedInt'=>'','unsignedShort'=>'','unsignedByte'=>'','positiveInteger'=>''),
	'http://www.w3.org/1999/XMLSchema' => array(
		'i4'=>'','int'=>'integer','boolean'=>'boolean','string'=>'string','double'=>'double',
		'float'=>'double','dateTime'=>'string',
		'timeInstant'=>'string','base64Binary'=>'string','base64'=>'string','ur-type'=>'array'),
	'http://soapinterop.org/xsd' => array('SOAPStruct'=>'struct'),
	'http://schemas.xmlsoap.org/soap/encoding/' => array('base64'=>'string','array'=>'array','Array'=>'array'),
    'http://xml.apache.org/xml-soap' => array('Map')
	);

	/**
	*  entities to convert
	*
	* @var      xmlEntities
	* @access   public
	*/
	var $xmlEntities = array('quot' => '"','amp' => '&',
		'lt' => '<','gt' => '>','apos' => "'");

	/**
	* adds debug data to the class level debug string
	*
	* @param    string $string debug data
	* @access   private
	*/
	function debug($string){
		$this->debug_str .= get_class($this).": $string\n";
	}

	/**
	* returns error string if present
	*
	* @return   boolean $string error string
	* @access   public
	*/
	function getError(){
		if($this->error_str != ''){
			return $this->error_str;
		}
		return false;
	}

	/**
	* sets error string
	*
	* @return   boolean $string error string
	* @access   private
	*/
	function setError($str){
		$this->error_str = $str;
	}

	/**
	* serializes PHP values in accordance w/ section 5
	* @return	string
    * @access	public
	*/
	function serialize_val($val,$name=false,$type=false,$name_ns=false,$type_ns=false,$attributes=false){
    	if(is_object($val) && get_class($val) == 'soapval'){
        	return $val->serialize();
        }
		$this->debug( "in serialize_val: $val, $name, $type, $name_ns, $type_ns");
		// if no name, use item
		$name = (!$name|| is_numeric($name)) ? 'soapVal' : $name;
		// if name has ns, add ns prefix to name
		$xmlns = '';
        if($name_ns){
			$prefix = 'nu'.rand(1000,9999);
			$name = $prefix.':'.$name;
			$xmlns .= " xmlns:$prefix=\"$name_ns\"";
		}
		// if type is prefixed, create type prefix
		if($type_ns != '' && $type_ns == $this->namespaces['xsd']){
			// need to fix this. shouldn't default to xsd if no ns specified
		    // w/o checking against typemap
			$type_prefix = 'xsd';
		} elseif($type_ns){
			$type_prefix = 'ns'.rand(1000,9999);
			$xmlns .= " xmlns:$type_prefix=\"$type_ns\"";
		}
		// serialize attributes if present
		if($attributes){
			foreach($attributes as $k => $v){
				$atts .= " $k=\"$v\"";
			}
		}
        // serialize if an xsd built-in primitive type
        if($type != '' && isset($this->typemap[$this->XMLSchemaVersion][$type])){
        	return "<$name$xmlns xsi:type=\"xsd:$type\">$val</$name>";
        }
		// detect type and serialize
		$xml = '';
		$atts = '';
		switch(true) {
			case ($type == '' && is_null($val)):
				$xml .= "<$name$xmlns xsi:type=\"xsd:nil\"/>";
				break;
			case (is_bool($val) || $type == 'boolean'):
				if(!$val){
			    	$val = 0;
				}
				$xml .= "<$name$xmlns xsi:type=\"xsd:boolean\"$atts>$val</$name>";
				break;
			case (is_int($val) || is_long($val) || $type == 'int'):
				$xml .= "<$name$xmlns xsi:type=\"xsd:int\"$atts>$val</$name>";
				break;
			case (is_float($val)|| is_double($val) || $type == 'float'):
				$xml .= "<$name$xmlns xsi:type=\"xsd:float\"$atts>$val</$name>";
				break;
			case (is_string($val) || $type == 'string'):
				if($this->charencoding){
			    	$val = htmlspecialchars($val, ENT_QUOTES);
			    }
				$xml .= "<$name$xmlns xsi:type=\"xsd:string\"$atts>$val</$name>";
				break;
			case is_object($val):
				break;
			break;
			case (is_array($val) || $type):
				// detect if struct or array
                $keyList = array_keys($val);
				$valueType = 'arraySimple';
				foreach($keyList as $keyListValue){
					if(!is_int($keyListValue)){
						$valueType = 'arrayStruct';
					}
				}
                if($valueType=='arraySimple' || ereg('^ArrayOf',$type)){
					foreach($val as $v){
                    	if(is_object($v) && get_class($v) == 'soapval'){
                        	$tt = $v->type;
                        } else {
							$tt = gettype($v);
                        }
						$array_types[$tt] = 1;
						$xml .= $this->serialize_val($v,'item');
						$i = 0;
						if(is_array($v) && is_numeric(key($v))){
							$i += sizeof($v);
						} else {
							$i += 1;
						}
					}
					if(count($array_types) > 1){
						$array_typename = 'xsd:ur-type';
					} elseif(isset($this->typemap[$this->XMLSchemaVersion][$tt])) {
						$array_typename = 'xsd:'.$tt;
					} elseif($tt == 'array' || $tt == 'Array'){
						$array_typename = 'SOAP-ENC:Array';
					} else {
						$array_typename = $tt;
					}
					if(isset($array_types['array'])){
						$array_type = $i.",".$i;
					} else {
						$array_type = $i;
					}
					$xml = "<$name xsi:type=\"SOAP-ENC:Array\" SOAP-ENC:arrayType=\"".$array_typename."[$array_type]\"$atts>".$xml."</$name>";
				} else {
					// got a struct
					if(isset($type) && isset($type_prefix)){
						$type_str = " xsi:type=\"$type_prefix:$type\"";
					} else {
						$type_str = '';
					}
					$xml .= "<$name$xmlns$type_str$atts>";
					foreach($val as $k => $v){
						$xml .= $this->serialize_val($v,$k);
					}
					$xml .= "</$name>";
				}
				break;
			default:
				$xml .= 'not detected, got '.gettype($val).' for '.$val;
				break;
		}
		return $xml;
	}

    /**
    * serialize message
    *
    * @param string body
    * @param string headers
    * @param array namespaces
    * @return string message
    * @access public
    */
    function serializeEnvelope($body,$headers=false,$namespaces=array()){
	// serialize namespaces
    $ns_string = '';
	foreach(array_merge($this->namespaces,$namespaces) as $k => $v){
		$ns_string .= "  xmlns:$k=\"$v\"";
	}
	// serialize headers
	if($headers){
		$headers = "<SOAP-ENV:Header>".$headers."</SOAP-ENV:Header>";
	}
	// serialize envelope
	return
	'<?xml version="1.0" encoding="'.$this->soap_defencoding .'"?'.">".
	'<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"'.$ns_string.">".
	$headers.
	"<SOAP-ENV:Body>".
		$body.
	"</SOAP-ENV:Body>".
	"</SOAP-ENV:Envelope>";
    }

    function formatDump($str){
		$str = htmlspecialchars($str);
		return nl2br($str);
    }

    /**
    * returns the local part of a prefixed string
    * returns the original string, if not prefixed
    *
    * @param string
    * @return string
    * @access public
    */
	function getLocalPart($str){
		if($sstr = strrchr($str,':')){
			// get unqualified name
			return substr( $sstr, 1 );
		} else {
			return $str;
		}
	}

	/**
    * returns the prefix part of a prefixed string
    * returns false, if not prefixed
    *
    * @param string
    * @return mixed
    * @access public
    */
	function getPrefix($str){
		if($pos = strrpos($str,':')){
			// get prefix
			return substr($str,0,$pos);
		}
		return false;
	}

    function varDump($data) {
		ob_start();
		var_dump($data);
		$ret_val = ob_get_contents();
		ob_end_clean();
		return $ret_val;
	}
}

















/**
* transport class for sending/receiving data via HTTP and HTTPS
* NOTE: PHP must be compiled with the CURL extension for HTTPS support
*
* @author   Dietrich Ayala <dietrich@ganx4.com>
* @version  v 0.6.3
* @access public
*/
class soap_transport_http extends nusoap_base {

	var $username = '';
	var $password = '';
	var $url = '';
    var $proxyhost = '';
    var $proxyport = '';
	var $scheme = '';
	var $protocol_version = '1.0';
	var $encoding = '';
	var $outgoing_payload = '';
	var $incoming_payload = '';
	
	/**
	* constructor
	*/
	function soap_transport_http($url){
		$this->url = $url;
		$u = parse_url($url);
		foreach($u as $k => $v){
			$this->debug("$k = $v");
			$this->$k = $v;
		}
		if(isset($u['query']) && $u['query'] != ''){
            $this->path .= '?' . $u['query'];
		}
		if(!isset($u['port']) && $u['scheme'] == 'http'){
			$this->port = 80;
		}
	}

	/**
	* if authenticating, set user credentials here
	*
	* @param    string $user
	* @param    string $pass
	* @access   public
	*/
	function setCredentials($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}

	/**
	* set the soapaction value
	*
	* @param    string $soapaction
	* @access   public
	*/
	function setSOAPAction($soapaction) {
		$this->soapaction = $soapaction;
	}

	/**
	* set proxy info here
	*
	* @param    string $proxyhost
	* @param    string $proxyport
	* @access   public
	*/
	function setProxy($proxyhost, $proxyport) {
		$this->proxyhost = $proxyhost;
		$this->proxyport = $proxyport;
	}

	/**
	* send the SOAP message via HTTP
	*
	* @param    string $data message data
	* @param    integer $timeout set timeout in seconds
	* @return	string data
	* @access   public
	*/
	function send($data, $timeout=0) {
	
		$this->debug('entered send() with data of length: '.strlen($data));
		// proxy
		if($this->proxyhost != '' && $this->proxyport != ''){
			$host = $this->proxyhost;
			$port = $this->proxyport;
			$this->debug("using http proxy: $host, $port");
		} else {
			$host = $this->host;
			$port = $this->port;
		}
		// ssl
		if($this->scheme == 'https'){
			$host = 'ssl://'.$host;
			$port = 443;
		}
		
		$this->debug("connection params: $host, $port");
		// timeout
		if($timeout > 0){
			$fp = fsockopen($host, $port, $this->errno, $this->error_str, $timeout);
		} else {
			$fp = fsockopen($host, $port, $this->errno, $this->error_str);
		}
		
		// test pointer
		if(!$fp) {
			$this->debug('Couldn\'t open socket connection to server '.$this->url.', Error: '.$this->error_str);
			$this->setError('Couldn\'t open socket connection to server: '.$this->url.', Error: '.$this->error_str);
			return false;
		}
		$this->debug('socket connected');
		// http auth
		$credentials = '';
		if($this->username != '') {
			$this->debug('setting http auth credentials');
			$credentials = 'Authorization: Basic '.base64_encode("$this->username:$this->password").'\r\n';
		}
		// swap url for path if going through a proxy
		if($this->proxyhost != '' && $this->proxyport != ''){
			$this->outgoing_payload = "POST $this->url ".strtoupper($this->scheme)."/$this->protocol_version\r\n";
		} else {
			$this->outgoing_payload = "POST $this->path ".strtoupper($this->scheme)."/$this->protocol_version\r\n";
		}
		// set encoding headers
		if($this->encoding != '' && function_exists('gzdeflate')){
			$encoding_headers = "Accept-Encoding: $this->encoding\r\n".
			"Connection: close\r\n";
			set_magic_quotes_runtime(0);
		} else {
			$encoding_headers = '';
		}
		// make payload
		$this->outgoing_payload .=
			"User-Agent: $this->title/$this->version\r\n".
			//"User-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)\r\n".
			"Host: ".$this->host."\r\n".
			$credentials.
			"Content-Type: text/xml\r\nContent-Length: ".strlen($data)."\r\n".
			$encoding_headers.
			"SOAPAction: \"$this->soapaction\""."\r\n\r\n".
			$data;
		
		// send payload
		if(!fputs($fp, $this->outgoing_payload, strlen($this->outgoing_payload))) {
			$this->setError('couldn\'t write message data to socket');
			$this->debug('Write error');
		}
		$this->debug('wrote data to socket');
		
		// get response
	    $this->incoming_payload = '';
		//$strlen = 0;
		while( $data = fread($fp, 32768) ){
			$this->incoming_payload .= $data;
			//$strlen += strlen($data);
	    }
		$this->debug('received '.strlen($this->incoming_payload).' bytes of data from server');
		
		// close filepointer
		fclose($fp);
		$this->debug('closed socket');
		
		// connection was closed unexpectedly
		if($this->incoming_payload == ''){
			$this->setError('no response from server');
			return false;
		}
		
		$this->debug('received incoming payload: '.strlen($this->incoming_payload));
		$data = $this->incoming_payload."\r\n\r\n\r\n\r\n";
		
		// remove 100 header
		if(ereg('^HTTP/1.1 100',$data)){
			if($pos = strpos($data,"\r\n\r\n") ){
				$data = ltrim(substr($data,$pos));
			} elseif($pos = strpos($data,"\n\n") ){
				$data = ltrim(substr($data,$pos));
			}
		}//
		
		// separate content from HTTP headers
		if( $pos = strpos($data,"\r\n\r\n") ){
			$lb = "\r\n";
		} elseif( $pos = strpos($data,"\n\n") ){
			$lb = "\n";
		} else {
			$this->setError('no proper separation of headers and document');
			return false;
		}
		$header_data = trim(substr($data,0,$pos));
		$header_array = explode($lb,$header_data);
		$data = ltrim(substr($data,$pos));
		$this->debug('found proper separation of headers and document');
		$this->debug('cleaned data, stringlen: '.strlen($data));
		// clean headers
		foreach($header_array as $header_line){
			$arr = explode(':',$header_line);
			if(count($arr) >= 2){
				$headers[trim($arr[0])] = trim($arr[1]);
			}
		}
		//print "headers: <pre>$header_data</pre><br>";
		//print "data: <pre>$data</pre><br>";
		
		// decode transfer-encoding
		if(isset($headers['Transfer-Encoding']) && $headers['Transfer-Encoding'] == 'chunked'){
			//$timer->setMarker('starting to decode chunked content');
			if(!$data = $this->decodeChunked($data)){
				$this->setError('Decoding of chunked data failed');
				return false;
			}
			//$timer->setMarker('finished decoding of chunked content');
			//print "<pre>\nde-chunked:\n---------------\n$data\n\n---------------\n</pre>";
		}
		
		// decode content-encoding
		if(isset($headers['Content-Encoding']) && $headers['Content-Encoding'] != ''){
			if($headers['Content-Encoding'] == 'deflate' || $headers['Content-Encoding'] == 'gzip'){
    			// if decoding works, use it. else assume data wasn't gzencoded
    			if(function_exists('gzinflate')){
					//$timer->setMarker('starting decoding of gzip/deflated content');
					if($headers['Content-Encoding'] == 'deflate' && $degzdata = @gzinflate($data)){
    					$data = $degzdata;
					} elseif($headers['Content-Encoding'] == 'gzip' && $degzdata = gzinflate(substr($data, 10))){
						$data = $degzdata;
					} else {
						$this->setError('Errors occurred when trying to decode the data');
					}
					//$timer->setMarker('finished decoding of gzip/deflated content');
					//print "<xmp>\nde-inflated:\n---------------\n$data\n-------------\n</xmp>";
    			} else {
					$this->setError('The server sent deflated data. Your php install must have the Zlib extension compiled in to support this.');
				}
			}
		}
		
		if(strlen($data) == 0){
			$this->debug('no data after headers!');
			$this->setError('no data present after HTTP headers');
			return false;
		}
		$this->debug('end of send()');
		return $data;
	}


	/**
	* send the SOAP message via HTTPS 1.0 using CURL
	*
	* @param    string $msg message data
	* @param    integer $timeout set timeout in seconds
	* @return	string data
	* @access   public
	*/
	function sendHTTPS($data, $timeout=0) {
	   	global $t;
		$t->setMarker('inside sendHTTPS()');
		$this->debug('entered sendHTTPS() with data of length: '.strlen($data));
		// init CURL
		$ch = curl_init();
		$t->setMarker('got curl handle');
		// set proxy
		if($this->proxyhost && $this->proxyport){
			$host = $this->proxyhost;
			$port = $this->proxyport;
		} else {
			$host = $this->host;
			$port = $this->port;
		}
		// set url
		$hostURL = ($port != '') ? "https://$host:$port" : "https://$host";
		// add path
		$hostURL .= $this->path;
		curl_setopt($ch, CURLOPT_URL, $hostURL);
		// set other options
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// encode
		if(function_exists('gzinflate')){
			curl_setopt($ch, CURLOPT_ENCODING, 'deflate');
		}
		// set timeout
		if($timeout != 0){
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}
		
		$credentials = '';
		if($this->username != '') {
			$credentials = 'Authorization: Basic '.base64_encode("$this->username:$this->password").'\r\n';
		}
		
		if($this->encoding != ''){
			if(function_exists('gzdeflate')){
				$encoding_headers = "Accept-Encoding: $this->encoding\r\n".
				"Connection: close\r\n";
				set_magic_quotes_runtime(0);
			}
		}
		
		if($this->proxyhost && $this->proxyport){
			$this->outgoing_payload = "POST $this->url HTTP/$this->protocol_version\r\n";
		} else {
			$this->outgoing_payload = "POST $this->path HTTP/$this->protocol_version\r\n";
		}
		
		$this->outgoing_payload .=
			"User-Agent: $this->title v$this->version\r\n".
			"Host: ".$this->host."\r\n".
			$encoding_headers.
			$credentials.
			"Content-Type: text/xml\r\nContent-Length: ".strlen($data)."\r\n".
			"SOAPAction: \"$this->soapaction\""."\r\n\r\n".
			$data;

		// set payload
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->outgoing_payload);
		$t->setMarker('set curl options, executing...');
		// send and receive
		$this->incoming_payload = curl_exec($ch);
		$t->setMarker('executed transfer');
		$data = $this->incoming_payload;

        $cErr = curl_error($ch);

		if($cErr != ''){
        	$err = 'cURL ERROR: '.curl_errno($ch).': '.$cErr.'<br>';
			foreach(curl_getinfo($ch) as $k => $v){
				$err .= "$k: $v<br>";
			}
			$this->setError($err);
			curl_close($ch);
	    	return false;
		} else {
			echo '<pre>';
			var_dump(curl_getinfo($ch));
			echo '</pre>';
		}
		// close curl
		curl_close($ch);
		$t->setMarker('closed curl');
		
		// remove 100 header
		if(ereg('^HTTP/1.1 100',$data)){
			if($pos = strpos($data,"\r\n\r\n") ){
				$data = ltrim(substr($data,$pos));
			} elseif($pos = strpos($data,"\n\n") ){
				$data = ltrim(substr($data,$pos));
			}
		}//
		
		// separate content from HTTP headers
		if( $pos = strpos($data,"\r\n\r\n") ){
			$lb = "\r\n";
		} elseif( $pos = strpos($data,"\n\n") ){
			$lb = "\n";
		} else {
			$this->setError('no proper separation of headers and document');
			return false;
		}
		$header_data = trim(substr($data,0,$pos));
		$header_array = explode($lb,$header_data);
		$data = ltrim(substr($data,$pos));
		$this->debug('found proper separation of headers and document');
		$this->debug('cleaned data, stringlen: '.strlen($data));
		// clean headers
		foreach($header_array as $header_line){
			$arr = explode(':',$header_line);
			$headers[trim($arr[0])] = trim($arr[1]);
		}
		if(strlen($data) == 0){
			$this->debug('no data after headers!');
			$this->setError('no data present after HTTP headers.');
			return false;
		}
		
		// decode transfer-encoding
		if($headers['Transfer-Encoding'] == 'chunked'){
			//$timer->setMarker('starting to decode chunked content');
			if(!$data = $this->decodeChunked($data)){
				$this->setError('Decoding of chunked data failed');
				return false;
			}
			//$timer->setMarker('finished decoding of chunked content');
			//print "<pre>\nde-chunked:\n---------------\n$data\n\n---------------\n</pre>";
		}
		// decode content-encoding
		if($headers['Content-Encoding'] != ''){
			if($headers['Content-Encoding'] == 'deflate' || $headers['Content-Encoding'] == 'gzip'){
    			// if decoding works, use it. else assume data wasn't gzencoded
    			if(function_exists('gzinflate')){
					//$timer->setMarker('starting decoding of gzip/deflated content');
					if($headers['Content-Encoding'] == 'deflate' && $degzdata = @gzinflate($data)){
    					$data = $degzdata;
					} elseif($headers['Content-Encoding'] == 'gzip' && $degzdata = gzinflate(substr($data, 10))){
						$data = $degzdata;
					} else {
						$this->setError('Errors occurred when trying to decode the data');
					}
					//$timer->setMarker('finished decoding of gzip/deflated content');
					//print "<xmp>\nde-inflated:\n---------------\n$data\n-------------\n</xmp>";
    			} else {
					$this->setError('The server sent deflated data. Your php install must have the Zlib extension compiled in to support this.');
				}
			}
		}
		// set decoded payload
		$this->incoming_payload = $header_data."\r\n\r\n".$data;
		return $data;
	}
	
	function setEncoding($enc='gzip, deflate'){
		$this->encoding = $enc;
		$this->protocol_version = '1.1';
	}
	
	// This function will decode "chunked' transfer encoding
 	// as defined in RFC2068 19.4.6
	function decodeChunked($buffer){
		// length := 0
		$length = 0;
		$new = '';
		
		// read chunk-size, chunk-extension (if any) and CRLF
		// get the position of the linebreak
		$chunkend = strpos($buffer,"\r\n") + 2;
		$temp = substr($buffer,0,$chunkend);
		$chunk_size = hexdec( trim($temp) );
		$chunkstart = $chunkend;
		// while (chunk-size > 0) {
		while ($chunk_size > 0) {
			
			$chunkend = strpos( $buffer, "\r\n", $chunkstart + $chunk_size);
		  	
			// Just in case we got a broken connection
		  	if ($chunkend == FALSE) {
		  	    $chunk = substr($buffer,$chunkstart);
				// append chunk-data to entity-body
		    	$new .= $chunk;
		  	    $length += strlen($chunk);
		  	    break;
			}
			
		  	// read chunk-data and CRLF
		  	$chunk = substr($buffer,$chunkstart,$chunkend-$chunkstart);
		  	// append chunk-data to entity-body
		  	$new .= $chunk;
		  	// length := length + chunk-size
		  	$length += strlen($chunk);
		  	// read chunk-size and CRLF
		  	$chunkstart = $chunkend + 2;
			
		  	$chunkend = strpos($buffer,"\r\n",$chunkstart)+2;
			if ($chunkend == FALSE) {
				break; //Just in case we got a broken connection
			}
			$temp = substr($buffer,$chunkstart,$chunkend-$chunkstart);
			$chunk_size = hexdec( trim($temp) );
			$chunkstart = $chunkend;
		}
        // Update headers
        //$this->Header['content-length'] = $length;
        //unset($this->Header['transfer-encoding']);
		return $new;
	}
	
}



/**
*
* soap_parser class parses SOAP XML messages into native PHP values
*
* @author   Dietrich Ayala <dietrich@ganx4.com>
* @version  v 0.6.3
* @access   public
*/
class soap_parser extends nusoap_base {

	var $xml = '';
	var $xml_encoding = '';
	var $method = '';
	var $root_struct = '';
	var $root_struct_name = '';
	var $root_header = '';
    var $document = '';
	// determines where in the message we are (envelope,header,body,method)
	var $status = '';
	var $position = 0;
	var $depth = 0;
	var $default_namespace = '';
	var $namespaces = array();
	var $message = array();
    var $parent = '';
	var $fault = false;
	var $fault_code = '';
	var $fault_str = '';
	var $fault_detail = '';
	var $depth_array = array();
	var $debug_flag = true;
	var $soapresponse = NULL;
	var $responseHeaders = '';
	// for multiref parsing:
	// array of id => pos
	var $ids = array();
	// array of id => hrefs => pos
	var $multirefs = array();

	/**
	* constructor
	*
	* @param    string $xml SOAP message
	* @param    string $encoding character encoding scheme of message
	* @access   public
	*/
	function soap_parser($xml,$encoding='UTF-8',$method=''){
		$this->xml = $xml;
		$this->xml_encoding = $encoding;
		$this->method = $method;

		// Check whether content has been read.
	if(!empty($xml)){
			$this->debug('Entering soap_parser()');
			// Create an XML parser.
			$this->parser = xml_parser_create($this->xml_encoding);
			// Set the options for parsing the XML data.
			//xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
			// Set the object for the parser.
			xml_set_object($this->parser, $this);
			// Set the element handlers for the parser.
			xml_set_element_handler($this->parser, 'start_element','end_element');
			xml_set_character_data_handler($this->parser,'character_data');

			// Parse the XML file.
			if(!xml_parse($this->parser,$xml,true)){
			    // Display an error message.
			    $err = sprintf('XML error on line %d: %s',
			    xml_get_current_line_number($this->parser),
			    xml_error_string(xml_get_error_code($this->parser)));
				$this->debug('parse error: '.$err);
				$this->errstr = $err;
		} else {
				$this->debug('parsed successfully, found root struct: '.$this->root_struct.' of name '.$this->root_struct_name);
				// get final value
				$this->soapresponse = $this->message[$this->root_struct]['result'];
				// get header value
				if($this->root_header != ""){
					$this->responseHeaders = $this->message[$this->root_header]['result'];
				}
			}
			xml_parser_free($this->parser);
	} else {
			$this->debug('xml was empty, didn\'t parse!');
			$this->errstr = 'xml was empty, didn\'t parse!';
		}
	}

	/**
	* start-element handler
	*
	* @param    string $parser XML parser object
	* @param    string $name element name
	* @param    string $attrs associative array of attributes
	* @access   private
	*/
	function start_element($parser, $name, $attrs) {
		// position in a total number of elements, starting from 0
		// update class level pos
		$pos = $this->position++;
		// and set mine
		$this->message[$pos] = array('pos' => $pos,'children'=>'','cdata'=>'');
		// depth = how many levels removed from root?
		// set mine as current global depth and increment global depth value
		$this->message[$pos]['depth'] = $this->depth++;

		// else add self as child to whoever the current parent is
		if($pos != 0){
			$this->message[$this->parent]['children'] .= '|'.$pos;
		}
		// set my parent
		$this->message[$pos]['parent'] = $this->parent;
		// set self as current parent
		$this->parent = $pos;
		// set self as current value for this depth
		$this->depth_array[$this->depth] = $pos;
		// get element prefix
		if(strpos($name,':')){
			// get ns prefix
			$prefix = substr($name,0,strpos($name,':'));
			// get unqualified name
			$name = substr(strstr($name,':'),1);
		}
		// set status
		if($name == 'Envelope'){
			$this->status = 'envelope';
		} elseif($name == 'Header'){
			$this->root_header = $pos;
			$this->status = 'header';
		} elseif($name == 'Body'){
			$this->status = 'body';
			$this->body_position = $pos;
		// set method
		} elseif($this->status == 'body' && $pos == ($this->body_position+1)){
			$this->status = 'method';
			$this->root_struct_name = $name;
			$this->root_struct = $pos;
			$this->message[$pos]['type'] = 'struct';
			$this->debug("found root struct $this->root_struct_name, pos $this->root_struct");
		}
		// set my status
		$this->message[$pos]['status'] = $this->status;
		// set name
		$this->message[$pos]['name'] = htmlspecialchars($name);
		// set attrs
		$this->message[$pos]['attrs'] = $attrs;

		// loop through atts, logging ns and type declarations
        $attstr = '';
		foreach($attrs as $key => $value){
        	$key_prefix = $this->getPrefix($key);
			$key_localpart = $this->getLocalPart($key);
			// if ns declarations, add to class level array of valid namespaces
            if($key_prefix == 'xmlns'){
				if(ereg('^http://www.w3.org/[0-9]{4}/XMLSchema$',$value)){
					$this->XMLSchemaVersion = $value;
					$this->namespaces['xsd'] = $this->XMLSchemaVersion;
					$this->namespaces['xsi'] = $this->XMLSchemaVersion.'-instance';
				}
                $this->namespaces[$key_localpart] = $value;
				// set method namespace
				if($name == $this->root_struct_name){
					$this->methodNamespace = $value;
				}
			// if it's a type declaration, set type
            } elseif($key_localpart == 'type'){
            	$value_prefix = $this->getPrefix($value);
                $value_localpart = $this->getLocalPart($value);
				$this->message[$pos]['type'] = $value_localpart;
				$this->message[$pos]['typePrefix'] = $value_prefix;
                if(isset($this->namespaces[$value_prefix])){
                	$this->message[$pos]['type_namespace'] = $this->namespaces[$value_prefix];
                }
				// should do something here with the namespace of specified type?
			} elseif($key_localpart == 'arrayType'){
				$this->message[$pos]['type'] = 'array';
				/* do arrayType ereg here
				[1]    arrayTypeValue    ::=    atype asize
				[2]    atype    ::=    QName rank*
				[3]    rank    ::=    '[' (',')* ']'
				[4]    asize    ::=    '[' length~ ']'
				[5]    length    ::=    nextDimension* Digit+
				[6]    nextDimension    ::=    Digit+ ','
				*/
				$expr = '([A-Za-z0-9_]+):([A-Za-z]+[A-Za-z0-9_]+)\[([0-9]+),?([0-9]*)\]';
				if(ereg($expr,$value,$regs)){
					$this->message[$pos]['typePrefix'] = $regs[1];
					$this->message[$pos]['arraySize'] = $regs[3];
					$this->message[$pos]['arrayCols'] = $regs[4];
				}
			}
			// log id
			if($key == 'id'){
				$this->ids[$value] = $pos;
			}
			// root
			if($key_localpart == 'root' && $value == 1){
				$this->status = 'method';
				$this->root_struct_name = $name;
				$this->root_struct = $pos;
				$this->debug("found root struct $this->root_struct_name, pos $pos");
			}
            // for doclit
            $attstr .= " $key=\"$value\"";
		}
        // get namespace - must be done after namespace atts are processed
		if(isset($prefix)){
			$this->message[$pos]['namespace'] = $this->namespaces[$prefix];
			$this->default_namespace = $this->namespaces[$prefix];
		} else {
			$this->message[$pos]['namespace'] = $this->default_namespace;
		}
        if($this->status == 'header'){
        	$this->responseHeaders .= "<$name$attstr>";
        } elseif($this->root_struct_name != ''){
        	$this->document .= "<$name$attstr>";
        }
	}

	/**
	* end-element handler
	*
	* @param    string $parser XML parser object
	* @param    string $name element name
	* @access   private
	*/
	function end_element($parser, $name) {
		// position of current element is equal to the last value left in depth_array for my depth
		$pos = $this->depth_array[$this->depth--];

        // get element prefix
		if(strpos($name,':')){
			// get ns prefix
			$prefix = substr($name,0,strpos($name,':'));
			// get unqualified name
			$name = substr(strstr($name,':'),1);
		}

		// build to native type
		if(isset($this->body_position) && $pos > $this->body_position){
			// deal w/ multirefs
			if(isset($this->message[$pos]['attrs']['href'])){
				// get id
				$id = substr($this->message[$pos]['attrs']['href'],1);
				// add placeholder to href array
				$this->multirefs[$id][$pos] = "placeholder";
				// add set a reference to it as the result value
				$this->message[$pos]['result'] =& $this->multirefs[$id][$pos];
            // build complex values
			} elseif($this->message[$pos]['children'] != ""){
				$this->message[$pos]['result'] = $this->buildVal($pos);
			} else {
            	$this->debug('adding data for scalar value '.$this->message[$pos]['name'].' of value '.$this->message[$pos]['cdata']);
				if(is_numeric($this->message[$pos]['cdata']) ){
                	if( strpos($this->message[$pos]['cdata'],'.') ){
                		$this->message[$pos]['result'] = doubleval($this->message[$pos]['cdata']);
                    } else {
                    	$this->message[$pos]['result'] = intval($this->message[$pos]['cdata']);
                    }
                } else {
                	$this->message[$pos]['result'] = $this->message[$pos]['cdata'];
                }
			}
		}

		// switch status
		if($pos == $this->root_struct){
			$this->status = 'body';
		} elseif($name == 'Body'){
			$this->status = 'header';
		 } elseif($name == 'Header'){
			$this->status = 'envelope';
		} elseif($name == 'Envelope'){
			// resolve hrefs/ids
			if(sizeof($this->multirefs) > 0){
				foreach($this->multirefs as $id => $hrefs){
					$this->debug('resolving multirefs for id: '.$id);
					foreach($hrefs as $refPos => $ref){
						$this->debug('resolving href at pos '.$refPos);
						$this->multirefs[$id][$refPos] = $this->buildval($this->ids[$id]);
					}
				}
			}
		}
		// set parent back to my parent
		$this->parent = $this->message[$pos]['parent'];
        // for doclit
        if($this->status == 'header'){
        	$this->responseHeaders .= "</$name>";
        } elseif($pos >= $this->root_struct){
        	$this->document .= "</$name>";
        }
	}

	/**
	* element content handler
	*
	* @param    string $parser XML parser object
	* @param    string $data element content
	* @access   private
	*/
	function character_data($parser, $data){
		$pos = $this->depth_array[$this->depth];
        $this->message[$pos]['cdata'] .= $data;
        // for doclit
        if($this->status == 'header'){
        	$this->responseHeaders .= $data;
        } else {
        	$this->document .= $data;
        }
	}

	/**
	* get the parsed message
	*
	* @return	mixed
	* @access   public
	*/
	function get_response(){
		return $this->soapresponse;
	}

	/**
	* get the parsed headers
	*
	* @return	string XML or empty if no headers
	* @access   public
	*/
	function getHeaders(){
	    return $this->responseHeaders;
	}

	/**
	* decodes entities
	*
	* @param    string $text string to translate
	* @access   private
	*/
	function decode_entities($text){
		foreach($this->entities as $entity => $encoded){
			$text = str_replace($encoded,$entity,$text);
		}
		return $text;
	}

	/**
	* builds response structures for compound values (arrays/structs)
	*
	* @param    string $pos position in node tree
	* @access   private
	*/
	function buildVal($pos){
		if(!isset($this->message[$pos]['type'])){
			$this->message[$pos]['type'] = '';
		}
		$this->debug('inside buildVal() for '.$this->message[$pos]['name']."(pos $pos) of type ".$this->message[$pos]['type']);
		// if there are children...
		if($this->message[$pos]['children'] != ''){
			$children = explode('|',$this->message[$pos]['children']);
			array_shift($children); // knock off empty
			// md array
			if(isset($this->message[$pos]['arrayCols']) && $this->message[$pos]['arrayCols'] != ''){
            	$r=0; // rowcount
            	$c=0; // colcount
            	foreach($children as $child_pos){
					$this->debug("got an MD array element: $r, $c");
					$params[$r][] = $this->message[$child_pos]['result'];
				    $c++;
				    if($c == $this->message[$pos]['arrayCols']){
				    	$c = 0;
						$r++;
				    }
                }
            // array
			} elseif($this->message[$pos]['type'] == 'array' || $this->message[$pos]['type'] == 'Array'){
                $this->debug('adding array '.$this->message[$pos]['name']);
                foreach($children as $child_pos){
                	$params[] = $this->message[$child_pos]['result'];
                }
            // apache Map type: java hashtable
            } elseif($this->message[$pos]['type'] == 'Map' && $this->message[$pos]['type_namespace'] == 'http://xml.apache.org/xml-soap'){
                foreach($children as $child_pos){
                	$kv = explode("|",$this->message[$child_pos]['children']);
                   	$params[$this->message[$kv[1]]['result']] = $this->message[$kv[2]]['result'];
                }
            // generic compound type
            //} elseif($this->message[$pos]['type'] == 'SOAPStruct' || $this->message[$pos]['type'] == 'struct') {
            } else {
            	foreach($children as $child_pos){
				    $params[$this->message[$child_pos]['name']] =& $this->message[$child_pos]['result'];
                }
			}
			return is_array($params) ? $params : array();
		} else {
        	$this->debug('no children');
            if(strpos($this->message[$pos]['cdata'],'&')){
		    	return  strtr($this->message[$pos]['cdata'],array_flip($this->entities));
            } else {
            	return $this->message[$pos]['cdata'];
            }
		}
	}
}



/**
*
* soapclientct higher level class for easy usage.
*
* usage:
*
* // instantiate client with server info
* $soapclientct = new soapclientct( string path [ ,boolean wsdl] );
*
* // call method, get results
* echo $soapclientct->call( string methodname [ ,array parameters] );
*
* // bye bye client
* unset($soapclientct);
*
* @author   Dietrich Ayala <dietrich@ganx4.com>
* @version  v 0.6.3
* @access   public
*/
class soapclientct extends nusoap_base  {

	var $username = '';
	var $password = '';
	var $requestHeaders = false;
	var $responseHeaders;
	var $endpoint;
	var $error_str = false;
    var $proxyhost = '';
    var $proxyport = '';
    var $xml_encoding = '';
	var $http_encoding = false;
	var $timeout = 0;
	var $endpointType = '';
	/**
	* fault related variables
	*
	* @var      fault
	* @var      faultcode
	* @var      faultstring
	* @var      faultdetail
	* @access   public
	*/
	var $fault, $faultcode, $faultstring, $faultdetail;

	/**
	* constructor
	*
	* @param    string $endpoint SOAP server or WSDL URL
	* @param    bool $wsdl optional, set to true if using WSDL
	* @param	int $portName optional portName in WSDL document
	* @access   public
	*/
	function soapclientct($endpoint,$wsdl = false){
		$this->endpoint = $endpoint;

		// make values
		if($wsdl){
			$this->endpointType = 'wsdl';
			$this->wsdlFile = $this->endpoint;
			
			// instantiate wsdl object and parse wsdl file
			$this->debug('instantiating wsdl class with doc: '.$endpoint);
			$this->wsdl =& new wsdl($this->wsdlFile);
			$this->debug("wsdl debug: \n".$this->wsdl->debug_str);
			// catch errors
			if($errstr = $this->wsdl->getError()){
				$this->debug('got wsdl error: '.$errstr);
				$this->setError('wsdl error: '.$errstr);
			} elseif($this->operations = $this->wsdl->getOperations()){
				$this->debug( 'got '.count($this->operations).' operations from wsdl '.$this->wsdlFile);
			} else {
				$this->debug( 'getOperations returned false');
				$this->setError('no operations defined in the WSDL document!');
			}
		}
	}

	/**
	* calls method, returns PHP native type
	*
	* @param    string $method SOAP server URL or path
	* @param    array $params array of parameters, can be associative or not
	* @param	string $namespace optional method namespace
	* @param	string $soapAction optional SOAPAction value
	* @param	boolean $headers optional array of soapval objects for headers
	* @return	mixed
	* @access   public
	*/
	function call($operation,$params=array(),$namespace='',$soapAction='',$headers=false){
		$this->operation = $operation;
		$this->fault = false;
		$this->error_str = '';
		$this->request = '';
		$this->response = '';
		$this->faultstring = '';
		$this->faultcode = '';
		$this->opData = array();
		// if wsdl, get operation data and process parameters
		if($this->endpointType == 'wsdl' && $opData = $this->getOperationData($operation)){

			$this->opData = $opData;
			foreach($opData as $key => $value){
				$this->debug("$key -> $value");
			}
			$soapAction = $opData['soapAction'];
			$this->endpoint = $opData['endpoint'];
			$namespace = isset($opData['input']['namespace']) ? $opData['input']['namespace'] : 'http://testuri.org';
			$style = $opData['style'];
			// add ns to ns array
			if($namespace != '' && !isset($this->wsdl->namespaces[$namespace])){
				$this->wsdl->namespaces['nu'] = $namespace;
			} else {
            	$namespace = 'http://testuri.org';
                $this->wsdl->namespaces['nu'] = $namespace;
            }
			// serialize payload
			
			if($opData['input']['use'] == 'literal') {
				$payload = is_array($params) ? array_shift($params) : $params;
			} else {
				$this->debug("serializing RPC params for operation $operation");
				$payload = "<".$this->wsdl->getPrefixFromNamespace($namespace).":$operation>".
				$this->wsdl->serializeRPCParameters($operation,'input',$params).
				'</'.$this->wsdl->getPrefixFromNamespace($namespace).":$operation>";
			}
			$this->debug('payload size: '.strlen($payload));
			// serialize envelope
			$soapmsg = $this->serializeEnvelope($payload,$this->requestHeaders,$this->wsdl->usedNamespaces);
			$this->debug("wsdl debug: \n".$this->wsdl->debug_str);
		} elseif($this->endpointType == 'wsdl') {
			$this->setError( 'operation '.$operation.' not present.');
			$this->debug("operation '$operation' not present.");
			$this->debug("wsdl debug: \n".$this->wsdl->debug_str);
			return false;
		// no wsdl
		} else {
			// make message
			if(!isset($style)){
				$style = 'rpc';
			}
            if($namespace == ''){
            	$namespace = 'http://testuri.org';
                $this->wsdl->namespaces['ns1'] = $namespace;
            }
			// serialize envelope
			$payload = '';
			foreach($params as $k => $v){
				$payload .= $this->serialize_val($v,$k);
			}
			$payload = "<ns1:$operation xmlns:ns1=\"$namespace\">\n".$payload."</ns1:$operation>\n";
			$soapmsg = $this->serializeEnvelope($payload,$this->requestHeaders);
		}
		$this->debug("endpoint: $this->endpoint, soapAction: $soapAction, namespace: $namespace");
		// send
		$this->debug('sending msg (len: '.strlen($soapmsg).") w/ soapaction '$soapAction'...");
		$return = $this->send($soapmsg,$soapAction,$this->timeout);
		if($errstr = $this->getError()){
			$this->debug('Error: '.$errstr);
			return false;
		} else {
			$this->return = $return;
			$this->debug('sent message successfully and got a(n) '.gettype($return).' back');
			
			// fault?
			if(is_array($return) && isset($return['faultcode'])){
				$this->debug('got fault');
				$this->setError($return['faultcode'].': '.$return['faultstring']);
				$this->fault = true;
				foreach($return as $k => $v){
					$this->$k = $v;
					$this->debug("$k = $v<br>");
				}
				return $return;
			} else {
				// array of return values
				if(is_array($return)){
					// multiple 'out' parameters
					if(sizeof($return) > 1){
						return $return;
					}
					// single 'out' parameter
					return array_shift($return);
				// nothing returned (ie, echoVoid)
				} else {
					return "";
				}
			}
		}
	}

	/**
	* get available data pertaining to an operation
	*
	* @param    string $operation operation name
	* @return	array array of data pertaining to the operation
	* @access   public
	*/
	function getOperationData($operation){
		if(isset($this->operations[$operation])){
			return $this->operations[$operation];
		}
	}

    /**
    * send the SOAP message
    *
    * Note: if the operation has multiple return values
    * the return value of this method will be an array
    * of those values.
    *
	* @param    string $msg a SOAPx4 soapmsg object
	* @param    string $soapaction SOAPAction value
	* @param    integer $timeout set timeout in seconds
	* @return	mixed native PHP types.
	* @access   private
	*/
	function send($msg, $soapaction = '', $timeout=0) {
		// detect transport
		switch(true){
			// http(s)
			case ereg('^http',$this->endpoint):
				$this->debug('transporting via HTTP');
				$http = new soap_transport_http($this->endpoint);
				$http->setSOAPAction($soapaction);
				if($this->proxyhost && $this->proxyport){
					$http->setProxy($this->proxyhost,$this->proxyport);
				}
                if($this->username != '' && $this->password != '') {
					$http->setCredentials($this->username,$this->password);
				}
				if($this->http_encoding != ''){
					$http->setEncoding($this->http_encoding);
				}
				$this->debug('sending message, length: '.strlen($msg));
				if(ereg('^http:',$this->endpoint)){
				//if(strpos($this->endpoint,'http:')){
					$response = $http->send($msg,$timeout);
				} elseif(ereg('^https',$this->endpoint)){
				//} elseif(strpos($this->endpoint,'https:')){
					//if(phpversion() == '4.3.0-dev'){
						//$response = $http->send($msg,$timeout);
                   		//$this->request = $http->outgoing_payload;
						//$this->response = $http->incoming_payload;
					//} else
					if (extension_loaded('curl')) {
						$response = $http->sendHTTPS($msg,$timeout);
					} else {
						$this->setError('CURL Extension, or OpenSSL extension w/ PHP version >= 4.3 is required for HTTPS');
					}								
				} else {
					$this->setError('no http/s in endpoint url');
				}
				$this->request = $http->outgoing_payload;
				$this->response = $http->incoming_payload;
				$this->debug("transport debug data...\n".$http->debug_str);
				if($err = $http->getError()){
					$this->setError('HTTP Error: '.$err);
					return false;
				} elseif($this->getError()){
					return false;
				} else {
					$this->debug('got response, length: '.strlen($response));
					return $this->parseResponse($response);
				}
			break;
			default:
				$this->setError('no transport found, or selected transport is not yet supported!');
			return false;
			break;
		}
	}

	/**
	* processes SOAP message returned from server
	*
	* @param	string unprocessed response data from server
	* @return	mixed value of the message, decoded into a PHP type
	* @access   private
	*/
    function parseResponse($data) {
		$this->debug('Entering parseResponse(), about to create soap_parser instance');
		$parser = new soap_parser($data,$this->xml_encoding,$this->operation);
		// if parse errors
		if($errstr = $parser->getError()){
			$this->setError( $errstr);
			// destroy the parser object
			unset($parser);
			return false;
		} else {
			// get SOAP headers
			$this->responseHeaders = $parser->getHeaders();
			// get decoded message
			$return = $parser->get_response();
			// add parser debug data to our debug
			$this->debug($parser->debug_str);
            // add document for doclit support
            $this->document = $parser->document;
			// destroy the parser object
			unset($parser);
			// return decode message
			return $return;
		}
	 }

	/**
	* set the SOAP headers
	*
	* @param	$headers string XML
	* @access   public
	*/
	function setHeaders($headers){
		$this->requestHeaders = $headers;
	}

	/**
	* get the response headers
	*
	* @return	mixed object SOAPx4 soapval object or empty if no headers
	* @access   public
	*/
	function getHeaders(){
	    if($this->responseHeaders != '') {
			return $this->responseHeaders;
	    }
	}

	/**
	* set proxy info here
	*
	* @param    string $proxyhost
	* @param    string $proxyport
	* @access   public
	*/
	function setHTTPProxy($proxyhost, $proxyport) {
		$this->proxyhost = $proxyhost;
		$this->proxyport = $proxyport;
	}

	/**
	* if authenticating, set user credentials here
	*
	* @param    string $username
	* @param    string $password
	* @access   public
	*/
	function setCredentials($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
	* use HTTP encoding
	*
	* @param    string $enc
	* @access   public
	*/
	function setHTTPEncoding($enc='gzip, deflate'){
		$this->http_encoding = $enc;
	}
	
	/**
	* dynamically creates proxy class, allowing user to directly call methods from wsdl
	*
	* @return   object soap_proxy object
	* @access   public
	*/
	function getProxy(){
		foreach($this->operations as $operation => $opData){
			if($operation != ''){
				// create param string
				if(sizeof($opData['input']['parts']) > 0){
					foreach($opData['input']['parts'] as $name => $type){
						$paramStr .= "\$$name,";
					}
					$paramStr = substr($paramStr,0,strlen($paramStr)-1);
				}
				$evalStr .= "function $operation ($paramStr){
					// load params into array
					\$params = array($paramStr);
					return \$this->call('$operation',\$params,'".$opData['namespace']."','".$opData['soapAction']."');
				}";
				unset($paramStr);
			}
		}
		$r = rand();
		$evalStr = 'class soap_proxy_'.$r.' extends soapclientct {
				'.$evalStr.'
			}';
		//print "proxy class:<pre>$evalStr</pre>";
		// eval the class
		eval($evalStr);
		// instantiate proxy object
		eval("\$proxy = new soap_proxy_$r('');");
		// transfer current wsdl data to the proxy thereby avoiding parsing the wsdl twice
		$proxy->endpointType = 'wsdl';
		$proxy->wsdlFile = $this->wsdlFile;
		$proxy->wsdl = $this->wsdl;
		$proxy->operations = $this->operations;
		return $proxy;
	}
}


    
    //sortyahooXMLTree
    
    function sortyahooXMLTree($data)
    {
       // create parser
       $parser = xml_parser_create();
       xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
       xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
       xml_parse_into_struct($parser,$data,$values,$tags);
       xml_parser_free($parser);       
       
       $result = $values[0]['attributes']['totalResultsAvailable']; 
       
       return $result;
       
    }    
    
    
    
    
    
    function &composeArray($array, $elements, $value=array())
    {    
     // get current element
     $element = array_shift($elements);
    
     // does the current element refer to a list
     if ($element=="Result")
     {
         // more elements?
         if(sizeof($elements) > 0)
         {
           @$array[$element][sizeof($array[$element])-1] = &composeArray(@$array[$element][sizeof(@$array[$element])-1], $elements, $value);
         }
         else // if (is_array($value))
         {
           @$array[$element][sizeof(@$array[$element])] = $value;
         }
     }
     else
     {
         // more elements?
         if(sizeof($elements) > 0)
         {
           @$array[$element] = &composeArray(@$array[$element], $elements, $value);
         }
         else
         {
           @$array[$element] = $value;
         }
     }
    
     return $array;
    }

//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------

//mysql requete for site id and url

$crawltsql = "SELECT id_site, url  FROM crawlt_site";
$crawltrequete = mysql_query($crawltsql, $crawltconnexion) or die("MySQL query error");

$crawltnbrresult=mysql_num_rows($crawltrequete);
$crawltnbrresult2 = ($crawltnbrresult*2);
$crawltnbrresult3 = ($crawltnbrresult*3);
//initialize array
$listsitecrawlt=array();
$crawltsiteurl=array();
  
if($crawltnbrresult >= 1)
        {	
        while($crawltligne = mysql_fetch_row($crawltrequete))                                                                              
            {
            $listsitecrawlt[] = $crawltligne[0];
            $crawltsiteurl[$crawltligne[0]] = $crawltligne[1];
            }        
        }


//looking for position in search engines database using api


//test loop position

if($crawltloop<$crawltnbrresult)
    {
    //yahoo
           
     $crawltidsite=$listsitecrawlt[$crawltloop];
     $crawlturlsite=$crawltsiteurl[$crawltidsite];
     
     if($crawlturlsite=='')
        {
         //update the crawlt_config table 
         $crawltloopnext = $crawltloop + 1;        
        $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='0', loop1='".crawlt_sql_quote($crawltloopnext)."'";        
        $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");          
        }
     else
        {
        //to avoid problem if the url is enter in the database with http://           
        if(strncmp($crawlturlsite,'http://',7)==0)
          {
          $crawlturlsite=substr($crawlturlsite,7);
          }            
      
         if($crawltloop2==0)
            {      
            $crawltrequete1 = "linkdomain:$crawlturlsite";
            $crawltquery1 = "http://api.search.yahoo.com/WebSearchService/V1/webSearch?query=".rawurlencode($crawltrequete1)."&appid=crawltrack";    
            $crawltxml1 = file_get_contents($crawltquery1);
            $crawltnbryahoo1 = sortyahooXMLTree($crawltxml1);
            if($crawltnbryahoo1=='')
                {
                $crawltnbryahoo1=0;
                }    
        
             //check if the date exit already in the table     
            $crawltsqlcheck = "SELECT date,id_site  FROM crawlt_seo_position
                        WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                        AND id_site='".crawlt_sql_quote($crawltidsite)."'";
        
            $crawltrequetecheck = mysql_query($crawltsqlcheck, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresultcheck=mysql_num_rows($crawltrequetecheck);
            if($crawltnbrresultcheck >=1)
                {      
                $crawltsqlseo ="UPDATE crawlt_seo_position SET linkyahoo='".crawlt_sql_quote($crawltnbryahoo1)."'
                          WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                          AND id_site='".crawlt_sql_quote($crawltidsite)."'";
                } 
            else
                {
                $crawltsqlseo ="INSERT INTO crawlt_seo_position (date,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn,nbrdelicious, tagdelicious) VALUES ( '".crawlt_sql_quote($crawltdatetoday2)."','".crawlt_sql_quote($crawltidsite)."','".crawlt_sql_quote($crawltnbryahoo1)."','0','0','0','0',' ')";        
                } 
            $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error"); 
    
            $crawltloop2next = $crawltloop2 + 1;
            
             //update the crawlt_config table         
            $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='".crawlt_sql_quote($crawltloop2next)."'";        
            $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error"); 
            }
        else
            {
            $crawltrequete2 = "http://$crawlturlsite";    
            $crawltquery2 = "http://api.search.yahoo.com/WebSearchService/V1/webSearch?query=".rawurlencode($crawltrequete2)."&appid=crawltrack";    
            $crawltxml2 = file_get_contents($crawltquery2);
            $crawltnbryahoo2 = sortyahooXMLTree($crawltxml2);
            if($crawltnbryahoo2=='')
                {
                $crawltnbryahoo2=0;
                }  
             //check if the date exit already in the table     
            $crawltsqlcheck = "SELECT date,id_site  FROM crawlt_seo_position
                        WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                        AND id_site='".crawlt_sql_quote($crawltidsite)."'";
        
            $crawltrequetecheck = mysql_query($crawltsqlcheck, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresultcheck=mysql_num_rows($crawltrequetecheck);
            if($crawltnbrresultcheck >=1)
                {      
                $crawltsqlseo ="UPDATE crawlt_seo_position SET pageyahoo='".crawlt_sql_quote($crawltnbryahoo2)."'
                          WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                          AND id_site='".crawlt_sql_quote($crawltidsite)."'";
                } 
            else
                {
                $crawltsqlseo ="INSERT INTO crawlt_seo_position (date,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn,nbrdelicious, tagdelicious) VALUES ( '".crawlt_sql_quote($crawltdatetoday2)."','".crawlt_sql_quote($crawltidsite)."','0','".crawlt_sql_quote($crawltnbryahoo2)."','0','0','0',' ')";        
                }         
            $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error");         
            
                 //update the crawlt_config table 
                 $crawltloopnext = $crawltloop + 1;        
                $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='0', loop1='".crawlt_sql_quote($crawltloopnext)."'";        
                $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");            
        
            }
       
        }
    }    
elseif($crawltloop>=$crawltnbrresult && $crawltloop<$crawltnbrresult2)
    {     
                
    //msn
    $crawltidsite=($crawltloop-$crawltnbrresult);
    $crawltsite=$listsitecrawlt[$crawltidsite];
    $crawlturlsite=$crawltsiteurl[$crawltsite];

     if($crawlturlsite=='')
        {
         //update the crawlt_config table 
         $crawltloopnext = $crawltloop + 1;        
        $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='0', loop1='".crawlt_sql_quote($crawltloopnext)."'";        
        $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");          
        }
     else
        {
        //to avoid problem if the url is enter in the database with http://
        if(strncmp($crawlturlsite,'http://',7)==0)
          {
          $crawlturlsite=substr($crawlturlsite,7);
          } 
            
         if($crawltloop2==0)
            {         
            $crawltrequete1 = "linkdomain:$crawlturlsite";        
            $soapclientct = new soapclientct("http://soap.search.msn.com/webservices.asmx");        
            $crawltparam1 = array(
             'AppID' => '5E4A1FC1F7B268DD7BCE62F39BFF8A0D81CB900B',
             'Query'   => $crawltrequete1,
             'CultureInfo' => 'en-US',
             'SafeSearch' => 'Off',
            'Requests' => array (
            'SourceRequest' => array (
            'Source' => 'Web',
            'Offset' => 0,
            'Count' => 50,
            'ResultFields' => 'All'
            ),
            ), 
            );        
            $crawltsearchresults1 = $soapclientct->call("Search", array("Request"=>$crawltparam1));
            if (!empty($crawltsearchresults1)) 
                {
                $crawltnbrmsn1 = $crawltsearchresults1['Responses']['SourceResponse']['Total'];
                }
            else
                {
                $crawltnbrmsn1=0;
                }             
            if($crawltnbrmsn1=='')
                {
                $crawltnbrmsn1=0;
                } 
                
              //insert values in the crawlt_seo_position table
              
             //check if the date exit already in the table     
            $crawltsqlcheck = "SELECT date,id_site  FROM crawlt_seo_position
                        WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                        AND id_site='".crawlt_sql_quote($crawltsite)."'";
        
            $crawltrequetecheck = mysql_query($crawltsqlcheck, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresultcheck=mysql_num_rows($crawltrequetecheck);
            if($crawltnbrresultcheck >=1)
                {     
                $crawltsqlseo ="UPDATE crawlt_seo_position SET linkmsn='".crawlt_sql_quote($crawltnbrmsn1)."'
                          WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                          AND id_site='".crawlt_sql_quote($crawltsite)."'";
                $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error");    
                }
            else
                {
                $crawltsqlseo ="INSERT INTO crawlt_seo_position (date,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn,nbrdelicious, tagdelicious) VALUES ( '".crawlt_sql_quote($crawltdatetoday2)."','".crawlt_sql_quote($crawltsite)."','0','0','".crawlt_sql_quote($crawltnbrmsn1)."','0','0',' ')";        
                $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error");    
                }            
                    
            $crawltloop2next = $crawltloop2 + 1;
            
             //update the crawlt_config table         
            $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='".crawlt_sql_quote($crawltloop2next)."'";        
            $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");
            }
         else
            {            
            $crawltrequete2 = "site:$crawlturlsite";            
            $soapclientct = new soapclientct("http://soap.search.msn.com/webservices.asmx");            
            $crawltparam2 = array(
             'AppID' => '5E4A1FC1F7B268DD7BCE62F39BFF8A0D81CB900B',
             'Query'   => $crawltrequete2,
             'CultureInfo' => 'en-US',
             'SafeSearch' => 'Off',
            'Requests' => array (
            'SourceRequest' => array (
            'Source' => 'Web',
            'Offset' => 0,
            'Count' => 50,
            'ResultFields' => 'All'
            ),
            ), 
            );            
            $crawltsearchresults2 = $soapclientct->call("Search", array("Request"=>$crawltparam2));
            if (!empty($crawltsearchresults2)) 
                {              
                $crawltnbrmsn2 = $crawltsearchresults2['Responses']['SourceResponse']['Total'];
                }
            else
                {
                $crawltnbrmsn2=0;
                }              
            if($crawltnbrmsn2=='')
                {
                $crawltnbrmsn2=0;
                }              
                
              //insert values in the crawlt_seo_position table
              
             //check if the date exit already in the table     
            $crawltsqlcheck = "SELECT date,id_site  FROM crawlt_seo_position
                        WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                        AND id_site='".crawlt_sql_quote($crawltsite)."'";
        
            $crawltrequetecheck = mysql_query($crawltsqlcheck, $crawltconnexion) or die("MySQL query error");
            $crawltnbrresultcheck=mysql_num_rows($crawltrequetecheck);
            if($crawltnbrresultcheck >=1)
                {     
                $crawltsqlseo ="UPDATE crawlt_seo_position SET pagemsn='".crawlt_sql_quote($crawltnbrmsn2)."'
                          WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                          AND id_site='".crawlt_sql_quote($crawltsite)."'";
                }
            else
                {
                $crawltsqlseo ="INSERT INTO crawlt_seo_position (date,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn,nbrdelicious, tagdelicious) VALUES ( '".crawlt_sql_quote($crawltdatetoday2)."','".crawlt_sql_quote($crawltsite)."','0','0','0','".crawlt_sql_quote($crawltnbrmsn2)."','0',' ')";        
                }                     
            $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error");          
            
                 //update the crawlt_config table 
                 $crawltloopnext = $crawltloop + 1;        
                $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='0', loop1='".crawlt_sql_quote($crawltloopnext)."'";        
                $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");           
            
            }   
        }
    }
elseif($crawltloop >= $crawltnbrresult2 && $crawltloop < $crawltnbrresult3)
    {     
                
    //del.icio.us
    $crawltidsite=($crawltloop-$crawltnbrresult2);
    $crawltsite=$listsitecrawlt[$crawltidsite];
    $crawlturlsite=$crawltsiteurl[$crawltsite];
    
     if($crawlturlsite=='')
        {
         //update the crawlt_config table 
         $crawltloopnext = $crawltloop + 1;        
        $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='0', loop1='".crawlt_sql_quote($crawltloopnext)."'";        
        $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");          
        }
     else
        {    
        //to avoid problem if the url is enter in the database with http://
        if(strncmp($crawlturlsite,'http://',7)==0)
          {
          $crawlturlsite=substr($crawlturlsite,7);
          }    
         
        $crawlturlsite2="http://".$crawlturlsite."/";
        $crawltquery1 = "http://badges.del.icio.us/feeds/json/url/blogbadge?hash=".md5($crawlturlsite2);  
        $crawltresultdelicious = file_get_contents($crawltquery1);
    
        $crawltnbrtag=explode("\"total_posts\":",$crawltresultdelicious);
        if(count($crawltnbrtag)==1)
            {
            $crawltnbrtagdelicious=0;
            $crawlttagtab=" ";
            }
        else
            {    
            $crawltnbrtag2 =explode("}",$crawltnbrtag[1]);
            $crawltnbrtagdelicious=$crawltnbrtag2[0];
            
            $crawlttag=explode("\"top_tags\":{\"",$crawltresultdelicious);
            $crawlttag2 =explode("}",$crawlttag[1]);
            $crawlttag3 =str_replace(":","",$crawlttag2[0]);
            $crawlttag3 =str_replace(",","",$crawlttag3);        
            $crawlttag4 =explode("\"",$crawlttag3);
            
            $n=count($crawlttag4);
            for($i=0;$i<$n;$i++)
                {
                if ($i%2 ==0)
                    {
                    $crawltnbrpertag[$crawlttag4[$i]]=$crawlttag4[$i+1];
                    }
                }
              $crawlttagtab= serialize($crawltnbrpertag);
             }     
           //insert values in the crawlt_seo_position table
          
         //check if the date exit already in the table     
        $crawltsqlcheck = "SELECT date,id_site  FROM crawlt_seo_position
                    WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                    AND id_site='".crawlt_sql_quote($crawltsite)."'";
    
        $crawltrequetecheck = mysql_query($crawltsqlcheck, $crawltconnexion) or die("MySQL query error");
        $nbrresultcheck=mysql_num_rows($crawltrequetecheck);
        if($nbrresultcheck >=1)
            {     
            $crawltsqlseo ="UPDATE crawlt_seo_position SET nbrdelicious='".crawlt_sql_quote($crawltnbrtagdelicious)."',tagdelicious='".crawlt_sql_quote($crawlttagtab)."'
                      WHERE date= '".crawlt_sql_quote($crawltdatetoday2)."'
                      AND id_site='".crawlt_sql_quote($crawltsite)."'";
            }
        else
            {
            $crawltsqlseo ="INSERT INTO crawlt_seo_position (date,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn,nbrdelicious, tagdelicious) VALUES ( '".crawlt_sql_quote($crawltdatetoday2)."','".crawlt_sql_quote($crawltsite)."','0','0','0','0','".crawlt_sql_quote($crawltnbrtagdelicious)."','".crawlt_sql_quote($crawlttagtab)."')";        
            }                     
        $crawltrequeteseo = mysql_query($crawltsqlseo, $crawltconnexion) or die("MySQL query error");          
        
         //update the crawlt_config table 
         $crawltloopnext = $crawltloop + 1;        
        $crawltsqlupdateseo ="UPDATE crawlt_config SET loop2='0', loop1='".crawlt_sql_quote($crawltloopnext)."'";        
        $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");    
        } 
    }   
else
    {
     //update the crawlt_config table         
    $crawltsqlupdateseo ="UPDATE crawlt_config SET loop1='0', datelastseorequest='".crawlt_sql_quote($crawltdatetoday)."'";        
    $crawltrequeteupdateseo = mysql_query($crawltsqlupdateseo, $crawltconnexion) or die("MySQL query error");    
    }    
    
         	    	

?>
