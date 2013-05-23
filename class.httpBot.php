<?php

class HTTPBot
{
  var $curl;		# cURL instance
	var $post = array();	# POST variables
	var $get = array();	# GET variables
	var $url;		# next URL to visit
	
	
	var $user_agent	= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
	
	/**
	* Constructor, sets appropiate options
	* void HTTPBot ([string $cookiefile])
	*/
	function HTTPBot ($cookiefile = 'cookiejar.txt')
	{
			$this->curl = curl_init();
			$options = array(
								CURLOPT_RETURNTRANSFER => true,     // return web page
								CURLOPT_HEADER         => false,    // don't return headers
								CURLOPT_FOLLOWLOCATION => true,     // follow redirects
								CURLOPT_ENCODING       => '',       // handle all encodings
								CURLOPT_USERAGENT      => $this->user_agent, // who am i
								CURLOPT_AUTOREFERER    => true,     // set referer on redirect
								CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
								CURLOPT_TIMEOUT        => 120,      // timeout on response
								CURLOPT_COOKIEJAR      => $cookiefile,
								CURLOPT_COOKIEFILE     => $cookiefile
							);
			$this->setOption($options);
	}
	
	/**
	* Manually set a specific option
	* void setOption (string $name [, bool $val])
	*/
	function setOption($name, $val = true)
	{
			if (is_array($name))
					curl_setopt_array($this->curl, $name);
			else
					curl_setopt($this->curl, constant('CURLOPT_' . $name), $val);
	}
	
	/**
	* Set the url for the next visit
	* void setURL (string $url)
	*/
	function setURL($url)
	{
			$this->url = $url;
			$this->setOption('URL', $url);
	}
	
	/**
	* Make a request and return the output
	* string go (void)
	*/
	function go ()
	{
			// add POST fields
			$ct = count($this->post);
			if ($ct > 0)
			{
					$this->setOption('POST',1);
					$tmpStr = '&';
					foreach ($this->post as $key => $val)
							$tmpStr .= "&$key=$val";
					$this->setOption('POSTFIELDS',str_replace('&&','',$tmpStr));
			}
			
			// add GET fields
			$ct = count($this->get);
			if ($ct > 0)
			{
					$tmpStr = '&';
					foreach ($post as $key => $val)
							$tmpStr .= "&$key=$val";
					$this->setUrl($this->url . str_replace('&&','?',$tmpStr));
			}
			
			$output = curl_exec($this->curl);
			
			// clear post & get
			$this->post = $this->get = array();
			$this->setOption('POSTFIELDS','');
			
			return $output;
	}
}




?>
