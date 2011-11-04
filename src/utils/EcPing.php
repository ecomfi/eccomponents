<?php

/*

	Example:

	$ping = new EcPing(Ping::OS_UNIX, 'ecomwebi.fi', 4);
 
	//echo command & raw result
	$ping->setOutputStream(fopen("php://output", 'r+'));
 
	//result = array('sent' => ... 'received' => .. etc.
	$result = $ping->execute();
	

*/

class EcPing
{
	protected $os;

	protected $outputStream;

	protected $commandPattern;

	protected $resultPattern;

	protected $statsPattern;

	protected $count = 2;

	protected $host;

	const OS_WINDOWS = 0;

	const OS_UNIX = 1;

	public function __construct($os=self::OS_UNIX, $host=null, $count=2)
	{
		switch ($os) {
			case self::OS_WINDOWS:
				/*
				 * Ping statistics for 192.168.1.253:
				 *     Packets: Sent = 17, Received = 14, Lost = 3 (17% loss),
				 * Approximate round trip times in milli-seconds:
				 *     Minimum = 0ms, Maximum = 0ms, Average = 0ms
				 */
				$this->commandPattern = 'ping -n [COUNT] [HOST]';
				$this->resultPattern = '/Sent = (?<sent>\d+), Received = (?<received>\d+), Lost = (?<lost>\d+) \((?<lostp>\d+)\% loss/';
				$this->statsPattern = '/Minimum = (?<min>\d+)ms, Maximum = (?<max>\d+)ms, Average = (?<avg>\d+)ms/';
				break;
			case self::OS_UNIX:
				/*
				 * 1 packets transmitted, 1 received, 0% packet loss, time 0ms
				 * rtt min/avg/max/mdev = 0.039/0.039/0.039/0.000 ms
				 */
				$this->commandPattern = 'ping -c [COUNT] [HOST]';
				$this->resultPattern = '/(?<sent>\d+) packets transmitted, (?<received>\d+) received, (?<lostp>\d+)\% packet loss/';
				$this->statsPattern = '/rtt min\/avg\/max\/mdev = (?<min>\d+\.\d+)\/(?<avg>\d+\.\d+)\/(?<max>\d+\.\d+)/';
				break;
			default:
				throw new Exception('Unsupported OS.');
				break;
		}

		$this->os = $os;
		$this->host = $host;
		$this->count = $count;
	}

	public function setOutputStream($handle)
	{
		$this->outputStream = $handle;
	}

	public function setCommandPattern($pattern)
	{
		$this->commandPattern = $pattern;
	}

	public function setResultPattern($pattern)
	{
		$this->resultPattern = $pattern;
	}

	public function setStatsPattern($pattern)
	{
		$this->statsPattern = $pattern;
	}

	public function setCount($count)
	{
		$this->count = $count;
	}

	public function setHost($host)
	{
		$this->host = $host;
	}

	public function execute($host=null)
	{
		if ($host !== null) {
			$this->host = $host;
		}

		if (!$this->host) {
			throw new Exception('Host not set.');
		}

		//build command & execute
		$output = array();
		$returnCode = null;
		$command = str_replace(array('[COUNT]', '[HOST]'), array($this->count, $this->host), $this->commandPattern);
		if ($this->outputStream) {
			fputs($this->outputStream, "$command\n");
		}

		exec($command, $output, $returnCode);

		if ($this->outputStream) {
			fputs($this->outputStream, implode("\n", $output));
			fputs($this->outputStream, "\n");
		}

		//parse result
		$result = array('returnCode'=>$returnCode);
		foreach($output as $line) {
			if (preg_match($this->resultPattern, $line, $matches)) {
				foreach(array('sent', 'received', 'lost', 'lostp') as $key)
					$result[$key] = isset($matches[$key]) ? $matches[$key] : null;
			}
			elseif (preg_match($this->statsPattern, $line, $matches)) {
				foreach(array('min', 'max', 'avg') as $key)
					$result[$key] = isset($matches[$key]) ? $matches[$key] : null;
			}
		}

		return $result;
	}

}


?>