<?php

namespace App\Traits;

use \Illuminate\Http\JsonResponse;

trait ApiResponse
{
    private static $STATUS = [
        'Success' => 200,
        'Created' => 201,
        'NoContent' => 204,
        'Unauthorized' => 401,
        'Forbidden' => 403,
        'NotAcceptable' => 406,
        'ServerError' => 500,
    ];

    private function response($data, string $message,  string $title, int $status): JsonResponse
    {
        return response()->json([
            'title' => $title,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function success($data, string $message = '',  string $title = "Sucesso!"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message,
            self::$STATUS['Success']
        );
    }

    public function created($data, string $message = '',  string $title = "Criado com sucesso!"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message,
            self::$STATUS['Created']
        );
    }

    public function forbidden($data, string $message = '', string $title = "Impossível processar"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message,
            self::$STATUS['Forbidden']
        );
    }

    public function notAcceptable($data, string $message = '', string $title = "Envio incorreto"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message, 
            self::$STATUS['NotAcceptable']
        );
    }

    public function deleted($data, string $message = '', string $title = "Removido com sucesso"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message,
            self::$STATUS['NoContent']
        );
    }

    public function updated($data, string $message = '', string $title = "Editado com sucesso"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message,
            self::$STATUS['NoContent']
        );
    }

    public function unauthorized($data = [], string $message = 'Ação não permitida', string $title = "Não autorizado"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message,
            self::$STATUS['Unauthorized']
        );
    }

    public function serverError($data, string $message = '', $title = "Erro no Servidor"): JsonResponse
    {
        return $this->response(
            $data, 
            $title,
            $message, 
            self::$STATUS['ServerError']
        );
    }
}