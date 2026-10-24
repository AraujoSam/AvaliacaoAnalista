/*
 Você deve implementar nessa aplicação console uma lógica que satisfaça os seguintes requisitos:
    1 - A aplicação deve solicitar ao usuário que ele digite uma string alfanumérica.
    2 - A aplicação deve remover todos os valores duplicados próximos, de forma que não hajam letras/números iguais juntos.
    Exemplo: AAABCCDDD -> Retorno : ABCD
 */

using System.Text;

namespace Teste1
{
    internal class Program
    {
        static void Main(string[] args)
        {
            string entrada = LerEntradaAlfanumerica();
            string resultado = RemoverDuplicadosConsecutivos(entrada);

            Console.WriteLine($"Retorno: {resultado}");
        }

        // Pede a string até receber um valor não vazio composto apenas por letras (A-Z, a-z) e números (0-9).
        static string LerEntradaAlfanumerica()
        {
            while (true)
            {
                Console.Write("Digite uma string alfanumérica: ");
                string? entrada = Console.ReadLine()?.Trim();

                if (!string.IsNullOrEmpty(entrada) && entrada.All(char.IsAsciiLetterOrDigit))
                {
                    return entrada;
                }

                Console.WriteLine("Entrada inválida. Use apenas letras e números, sem espaços ou símbolos.");
            }
        }

        // Mantém um caractere apenas quando ele é diferente do último adicionado.
        // A comparação diferencia maiúsculas de minúsculas: "Aa" permanece "Aa".
        static string RemoverDuplicadosConsecutivos(string texto)
        {
            var resultado = new StringBuilder(texto.Length);

            foreach (char caractere in texto)
            {
                if (resultado.Length == 0 || resultado[^1] != caractere)
                {
                    resultado.Append(caractere);
                }
            }

            return resultado.ToString();
        }
    }
}
