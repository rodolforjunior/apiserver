<?php
//Esse método utiliza o padrão table gateway, da forma que um objeto age como um
//gateway para uma tabela do banco.
class ProductGateway {
    private PDO $conn; 
    public function __construct(Database $database) //Passa-se um objeto da classe Database por meio do constructor
    {
        $this->conn = $database->getConnection(); //Chamamos o método connection no objeto Database
    }

    public function getAll(): array //Método para listar os produtos da tabela com o select
    {
        $sql = "SELECT * FROM product"; 

        $stmt = $this->conn->query($sql);//Chamando o método query no SQL -> passando para a var statement

        $data = [];
        //O retorno será um array de linhas, então a variável data vai ser o array para armazenar os dados da table

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //O método Fetch do PDO retorna uma linha na colsuta de dados. O parâmetro FETCH ASSOC retorna
            //O resultado como um associative array
            $row["is_available"] = (bool) $row["is_available"];
            //Selecionando a coluna is_available aplicando um cast no tipo de variável para
            //que seja exibido os valores TRUE or FALSE no JSON, ao invés de 1 ou 0.
            $data[] = $row;
        }
        return $data;
    }
    
    public function create(array $data): string { //Método para inserção na tabela a partir de uma request do tipo POST
        $sql = "INSERT INTO product (name, size, is_available)
        VALUES (:name, :size, :is_available)";
        
        $stmt = $this->conn->prepare($sql);
        //Chamando o método prepare na propriedade connection, passando o SQL 

        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR); //Atribuindo os valores aos parâmetros a serem inseridos. A propriedades PARAM_STR/INT etc reprentam o tipo de dado de cada campo
        $stmt->bindValue(":size", $data["size"], PDO::PARAM_INT);
        $stmt->bindValue(":is_available", (bool) $data["is_available"] ?? false, PDO::PARAM_BOOL);
        //No caso da variável booleana, é realizado um cast e atribuido o default como false 
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    public function get(string $id): array | false{ //Função buscar dado por ID
        $sql = "SELECT * FROM product WHERE id = :id"; //Pegando o ID com o placeholder :id
        $stmt = $this->conn->prepare($sql); //Usando o prepare passando o sql
        $stmt->bindValue(":id", $id, PDO::PARAM_INT); //Bindando o valor como INT
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC); //Executando e fetching como um array
        return $data; //Retorna o dado

        if ($data !== false) {
            $data["is_available"] = (bool)["is_available"];
        }
    } 

    public function update(array $current, array $new): int {
        $sql = "UPDATE product 
        SET name = :name, size = :size, is_available = :is_available
         WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
        $stmt->bindValue(":size", $new["size"] ?? $current["name"], PDO::PARAM_INT);
        $stmt->bindValue(":is_available", $new["is_available"] ?? $current["is_available"], PDO::PARAM_BOOL);
        //Binda os campos com os novos dados ou mantem os "current" se não estiver atualizados.
   
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int {
        $sql = "DELETE FROM product WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }
    
}
