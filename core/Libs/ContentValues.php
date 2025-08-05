<?php

namespace App\Libs;

class ContentValues
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Limpa todos os valores armazenados.
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * Adiciona um valor associado a uma chave.
     *
     * @param string $key
     * @param mixed $value
     */
    public function put(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Retorna o valor associado à chave, ou null se não existir.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Retorna todos os valores armazenados (opcional).
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }
}
