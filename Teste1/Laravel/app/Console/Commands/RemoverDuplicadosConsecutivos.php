<?php

/*
 * Não tenho experiência prévia com .NET, minha stack principal é PHP/Laravel e desenvolvimento
 * web fullstack. Resolvi o desafio em C# assistido por IA e incluí esta versão
 * equivalente em Laravel para mostrar como eu resolveria o mesmo problema na tecnologia em que atuo
 * no dia a dia. Caso o meu perfil faça sentido para a empresa, tenho interesse em ser considerado
 * também para oportunidades dentro dessa stack.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoverDuplicadosConsecutivos extends Command
{
    protected $signature = 'teste1:remover-duplicados {texto? : String alfanumérica a ser processada}';

    protected $description = 'Remove caracteres iguais consecutivos de uma string alfanumérica (ex: AAABCCDDD -> ABCD)';

    public function handle(): int
    {
        $entrada = $this->argument('texto');

        // Pede a string até receber um valor não vazio composto apenas por letras (A-Z, a-z) e números (0-9).
        while (! $this->ehAlfanumerica($entrada)) {
            if ($entrada !== null) {
                $this->error('Entrada inválida. Use apenas letras e números, sem espaços ou símbolos.');
            }

            $entrada = trim((string) $this->ask('Digite uma string alfanumérica'));
        }

        $this->info('Retorno: ' . $this->removerDuplicadosConsecutivos($entrada));

        return self::SUCCESS;
    }

    private function ehAlfanumerica(?string $texto): bool
    {
        return $texto !== null && preg_match('/^[A-Za-z0-9]+$/', $texto) === 1;
    }

    // Mantém um caractere apenas quando ele é diferente do último adicionado.
    // A comparação diferencia maiúsculas de minúsculas: "Aa" permanece "Aa".
    private function removerDuplicadosConsecutivos(string $texto): string
    {
        $resultado = '';

        foreach (str_split($texto) as $caractere) {
            if ($resultado === '' || $resultado[-1] !== $caractere) {
                $resultado .= $caractere;
            }
        }

        return $resultado;
    }
}
