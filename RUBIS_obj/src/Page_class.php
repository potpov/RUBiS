<?php
namespace Rubis;

/* YOU MUST INCLUDE DBCONNECT CLASS AND CHECKER BEFORE TO USE THIS SCRIPT */
class Page {
	private $current;
	private $start_from;
	private $vpp;
	private $tot;

	public function __construct($pag, $vpp) {
		(!is_numeric($pag) or empty($pag)) ? $this->current=0 : $this->current = $pag;
		(!is_numeric($vpp) or empty($vpp)) ? $this->vpp=25 : $this->vpp=$vpp;

		$this->start_from = ($this->current-1) * $this->vpp;
		$db = new DB_connect();
		$this->tot = $db->totItem();
	}

    /**
     * @return int|string
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param int|string $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * @return int|string
     */
    public function getStartFrom()
    {
        return $this->start_from;
    }

    /**
     * @param int|string $start_from
     */
    public function setStartFrom($start_from)
    {
        $this->start_from = $start_from;
    }

    /**
     * @return int|string
     */
    public function getVpp()
    {
        return $this->vpp;
    }

    /**
     * @param int|string $vpp
     */
    public function setVpp($vpp)
    {
        $this->vpp = $vpp;
    }

    /**
     * @return int
     */
    public function getTot()
    {
        return $this->tot;
    }

    /**
     * @param int $tot
     */
    public function setTot($tot)
    {
        $this->tot = $tot;
    }


}

?>
