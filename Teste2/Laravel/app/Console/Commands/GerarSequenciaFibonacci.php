<?php

/*
 * Versão equivalente em Laravel do Teste2, por favor, consulte a 'nota do candidato' em
 * Teste1/Laravel/app/Console/Commands/RemoverDuplicadosConsecutivos.php para contexto.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GerarSequenciaFibonacci extends Command
{
    private const MINIMO = 1;

    private const MAXIMO = 19;

    protected $signature = 'teste2:fibonacci {quantidade? : Quantidade de termos (inteiro de 1 a 19)}';

    protected $description = 'Gera a sequência de Fibonacci (1, 1, 2, 3, 5...) com a quantidade de termos informada';

    public function handle(): int
    {
        $entrada = $this->argument('quantidade');
        $quantidade = $this->validarQuantidade($entrada);

        // Pede o número até receber um inteiro maior que 0 e menor que 20.
        while ($quantidade === null) {
            if ($entrada !== null) {
                $this->error('Entrada inválida. Informe um número inteiro maior que 0 e menor que 20.');
            }

            $entrada = trim((string) $this->ask('Digite um número inteiro entre ' . self::MINIMO . ' e ' . self::MAXIMO));
            $quantidade = $this->validarQuantidade($entrada);
        }

        $sequencia = $this->gerarFibonacci($quantidade);

        $this->info("Sequência de Fibonacci com {$quantidade} termo(s): " . implode(', ', $sequencia));

        return self::SUCCESS;
    }

    private function validarQuantidade(?string $entrada): ?int
    {
        if ($entrada === null) {
            return null;
        }

        $numero = filter_var(trim($entrada), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => self::MINIMO, 'max_range' => self::MAXIMO],
        ]);

        return $numero === false ? null : $numero;
    }

    // Os dois primeiros termos são 1; cada termo seguinte é a soma dos dois anteriores.
    private function gerarFibonacci(int $quantidade): array
    {
        $sequencia = [];
        $anterior = 0;
        $atual = 1;

        for ($i = 0; $i < $quantidade; $i++) {
            $sequencia[] = $atual;
            [$anterior, $atual] = [$atual, $anterior + $atual];
        }

        return $sequencia;
    }
}
