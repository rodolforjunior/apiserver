<?php

class ErrorHandler //Classe que irá realizar o controle de erros no código.
{
    public static function handleException(Throwable $exception): void //O argumento do tipo throwable vai representar a exception disparada (caso haja erro na request.)
    {
        http_response_code(500); //Indicador de erro
        echo json_encode([
            "code" => $exception->getCode(), //Código do erro
            "message" => $exception->getMessage(), //Mensagem do erro
            "file" => $exception->getFile(), //Arquivo do erro
            "line" => $exception->getLine() //Linha do erro
        ]);
        //Objeto contendo informações do tipo de erro, encoded para o formato JSON ao invés de HTML (padrão)
    }
}
