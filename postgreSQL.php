<?php
class Conexao {
	private $SERVIDOR;
	private $PORTA;
	private $USUARIO;
	private $SENHA;
	private $BANCO;
	private $CONEXAO;

	function Conexao(string $servidor, string $porta, string $usuario, string $senha, string $banco) {
		$this->SERVIDOR = $servidor;
		$this->PORTA = $porta;
		$this->USUARIO = $usuario;
		$this->SENHA = $senha;
		$this->BANCO = $banco;
		$this->conectar();
	}

	private function conectar() {
		$this->CONEXAO = pg_connect("host=".$this->SERVIDOR
			                       ." port=".$this->PORTA
			                       ." dbname=".$this->BANCO
			                       ." user=".$this->USUARIO
			                       ." password=".$this->SENHA);
	}

	public function desconectar() {
		pg_close($this->CONEXAO);
	}

	public function buscar($tabela, $condicao) {
		return pg_fetch_all(pg_query($this->CONEXAO, "SELECT * FROM $tabela WHERE $condicao"));
	}

	public function atualizar($tabela, $campo, $valor, $condicao) {
		pg_query($this->CONEXAO, "UPDATE $tabela SET $campo = ".$this->aspas($valor)." WHERE $condicao");
	}

	public function inserir($tabela, ...$valores) {
		$sql = "INSERT INTO $tabela VALUES (";

		foreach ($valores as $valor) {
			$sql .= $this->aspas($valor).",";
    	}
    	$sql[-1] = ")";

    	pg_query($this->CONEXAO, $sql);
	}

	public function apagar($tabela, $condicao) {
		pg_query($this->CONEXAO, "DELETE FROM $tabela WHERE $condicao");
	}

	private function aspas($valor) {
		if ($valor === NULL) {
			return "default";
		}
		else if (gettype($valor) === "string")
    		return "'$valor'";
    	else
    		return "$valor";
	}
}

main();
?>