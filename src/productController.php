<?php
class ProductController
{
    public function __construct(private ProductGateway $gateway){

    }
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) { //Se a request conter um ID, então é uma request baseada em somente um recurso 
            $this->processResourceRequest($method, $id);
        } else { //Se não contiver, então é uma request sobre uma coleção de recursos.
            $this->processCollectionRequest($method);
        }
    }
 
    private function processResourceRequest(string $method, ?string $id): void //No método processRequest, passa-se o método da request e o ID (Podendo ser nulo, indicado com o ?) com retorno do tipo VOID.
    {
        $product = $this->gateway->get($id); //Chamando o método get na propriedade gateway passando o ID da table
        echo json_encode($product); //Encodando o dado como JSON para a request.

        if (! $product) {
            http_response_code(404);
            echo json_encode(["message" => "Dado não encontrado."]);
                return;
        }

        // switch ($method) { //Exibe o dado se existir.
        //     case "GET":
        //         echo json_encode($product);
        //         break;
        // }
        switch ($method){
        case "PATCH": 
            $data = (array) json_decode(file_get_contents("php://input"), true);
            $rows =  $this->gateway->update($product, $data); //Chmanado a função gateway, passando o array de dados
            //O retorno será um ID da criação, então se atribuí a variável $id, a resposta será o JSON com o ID.
            
            echo json_encode([
                "message" => "Usuário $id atualizado.",
                "rows" => $rows //Retornando número de linhas que foram afetadas.
            ]);
            
            break;
            
            case "DELETE":
                $rows = $this->gateway->delete($id); //Chmanado a função gateway, passando o array de dados
                echo json_encode([
                    "message" => "Usuário $id DELETADO.",
                    "row" => $rows
                ]);
                break;
        }
    }

    
    private function processCollectionRequest(string $method): void { //No método de processamento da coleção, passa-se somente o método de req
        switch($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $id =  $this->gateway->create($data); //Chmanado a função gateway, passando o array de dados
                //O retorno será um ID da criação, então se atribuí a variável $id, a resposta será o JSON com o ID.
                
                echo json_encode([
                    "message" => "Usuário adicionado.",
                    "id" => $id
                ]);
                
                break;
            }

            
    }   
}

?>