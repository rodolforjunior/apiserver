<?php
declare (strict_types=1);
//Strict type "tipa" a linguagem e faz com que as variáveis
//tenham valores específicos, por exemplo: int $x. Esse int precisa
//obrigatoriamente de um valor tipo num e assim sucessivamente.
//Isso se aplica somente ao arquivo atual, no caso index.php.
//*********************************************************** */
spl_autoload_register(function($class){
//Ao invés de declarar manualmente as classes usando o require, 
//Com o autoload register o PHP irá buscar e inicializar as classes especificadas nesse escopo.
//*********************************************************** */
require __DIR__ . "/src/$class.php";
//No corpo da função, é realizado o REQUIRE da função através da constante DIR para referenciar o diretório da classe a ser utilizada.
});
//*********************************************************** */
set_exception_handler("ErrorHandler::handleException");
//Passe-se uma string que vai identificar o método handleException adicionada.
//*********************************************************** */
header("Content-Type: application/json; charset=UTF-8"); 
//Especificando o formato no header
//*********************************************************** */
$parts = explode("/", $_SERVER['REQUEST_URI']);
//A variável parts vai receber um array* convertido pela função explode.
//A função "explode" uma string em um array de dados
if($parts[1] != "products") { //Se a primeira parte da string não for igual a $var
    //Retornará o erro de resposta 404.
    http_response_code(404);
    exit;
}
//*********************************************************** */
$id = $parts[2] ?? null;

$database = new Database("localhost", "product_db", "root", ""); //Criando objeto da classe, passando os parâmetros definidos na classe Database.

$gateway = new ProductGateway($database);

// $database->getConnection(); //Chamando o método no objeto.

$controller = new ProductController($gateway); //Criando novo objeto da classe ProductController

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
?>  
