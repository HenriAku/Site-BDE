<?php
class Couleur {
    public function __construct(
        public string $code_hexa,
		public int $n_prod,){}

	public function getcode_hexa() : string { return $this->code_hexa; }
	public function getn_prod   () : int    { return $this->n_prod   ; }

	public function setcode_hexa(string $code_hexa): void {$this->code_hexa = $code_hexa;}
	public function setn_prod   (int    $n_prod   ): void {$this->n_prod    = $n_prod   ;}
}
?>
