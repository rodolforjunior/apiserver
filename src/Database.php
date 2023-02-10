<?php 
class Database {
    public function __construct(private string $host, //Criando obj da classe usando o método __construct
                                private string $name,
                                private string $user,
                                private string $password)
    {}
    public function getConnection(): PDO 
    {
         $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
        //A $var dsn (DataSourceName) vai receber a informação requirida para conectar ao db, no caso os parâmetros setados no construct. Serão atribuídos os valores nesse escopo.
         return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false 
            //Argumentos PDO para definir a NÃO conversão para string de atributos tipo int/bool do banco
         ]); //Cria-se e retorna um objeto PDO, retornando com os valores obtidos no constructor.
        
    }
}
