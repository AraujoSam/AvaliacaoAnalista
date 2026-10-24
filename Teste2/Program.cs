/*
 Você deve implementar nessa aplicação console uma lógica que satisfaça os seguintes requisitos:
    1 - A aplicação deve solicitar ao usuário que ele digite um número.
    2 - A aplicação deve ler essa entrada e garantir que foi digitado um número inteiro, positivo, maior que 0 e menor que 20.
    3 - A aplicação deve gerar uma sequência de Fibonacci com a quantidade de repetições informada pelo usuário.
    Obs: A seguência de Fibonacci segue o padrão 1,1,2,3,5,8 e assim por diante, sendo o próximo número a soma do último número com o seu anterior.
*/

namespace Teste2
{
    internal class Program
    {
        const int Minimo = 1;
        const int Maximo = 19;

        static void Main(string[] args)
        {
            int quantidade = LerQuantidade();
            List<int> sequencia = GerarFibonacci(quantidade);

            Console.WriteLine($"Sequência de Fibonacci com {quantidade} termo(s): {string.Join(", ", sequencia)}");
        }

        // Pede o número até receber um inteiro maior que 0 e menor que 20.
        static int LerQuantidade()
        {
            while (true)
            {
                Console.Write($"Digite um número inteiro entre {Minimo} e {Maximo}: ");
                string? entrada = Console.ReadLine();

                if (int.TryParse(entrada, out int numero) && numero >= Minimo && numero <= Maximo)
                {
                    return numero;
                }

                Console.WriteLine("Entrada inválida. Informe um número inteiro maior que 0 e menor que 20.");
            }
        }

        // Os dois primeiros termos são 1; cada termo seguinte é a soma dos dois anteriores.
        static List<int> GerarFibonacci(int quantidade)
        {
            var sequencia = new List<int>(quantidade);
            int anterior = 0;
            int atual = 1;

            for (int i = 0; i < quantidade; i++)
            {
                sequencia.Add(atual);
                (anterior, atual) = (atual, anterior + atual);
            }

            return sequencia;
        }
    }
}
