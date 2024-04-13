<?php

namespace RefactoringGuru\Estrategia\Conceitual;

/**
 * O Contexto define a interface de interesse para os clientes.
 */
class Contexto
{
    /**
     * @var Estrategia O Contexto mantém uma referência a um dos objetos de Estratégia.
     * O Contexto não conhece a classe concreta de uma estratégia. Deve funcionar com
     * todas as estratégias via interface de Estratégia.
     */
    private $estrategia;

    /**
     * Normalmente, o Contexto aceita uma estratégia através do construtor, mas também
     * fornece um método setter para alterá-la em tempo de execução.
     */
    public function __construct(Estrategia $estrategia)
    {
        $this->estrategia = $estrategia;
    }

    /**
     * Normalmente, o Contexto permite substituir um objeto de Estratégia em tempo de execução.
     */
    public function setEstrategia(Estrategia $estrategia)
    {
        $this->estrategia = $estrategia;
    }

    /**
     * O Contexto delega parte do trabalho para o objeto de Estratégia em vez de
     * implementar várias versões do algoritmo por conta própria.
     */
    public function fazerAlgumaLogicaDeNegocio(): void
    {
        // ...

        echo "Contexto: Ordenando dados usando a estratégia (não tem certeza de como ela vai fazer)\n";
        $resultado = $this->estrategia->executarAlgoritmo(["a", "b", "c", "d", "e"]);
        echo implode(",", $resultado) . "\n";

        // ...
    }
}

/**
 * A interface de Estratégia declara operações comuns a todas as versões
 * suportadas de algum algoritmo.
 *
 * O Contexto usa esta interface para chamar o algoritmo definido pelas Estratégias Concretas.
 */

interface Estrategia
{
    public function executarAlgoritmo(array $dados): array;
}

/**
 * As Estratégias Concretas implementam o algoritmo enquanto seguem a interface base de Estratégia.
 * A interface as torna intercambiáveis no Contexto.
 */

class EstrategiaConcretaA implements Estrategia
{
    public function executarAlgoritmo(array $dados): array
    {
        sort($dados);

        return $dados;
    }
}

class EstrategiaConcretaB implements Estrategia
{
    public function executarAlgoritmo(array $dados): array
    {
        rsort($dados);

        return $dados;
    }
}

/**
 * O código do cliente escolhe uma estratégia concreta e a passa para o contexto. O
 * cliente deve estar ciente das diferenças entre as estratégias para fazer a escolha certa.
 */

$contexto = new Contexto(new EstrategiaConcretaA());
echo "Cliente: Estratégia definida para ordenação normal.\n";
$contexto->fazerAlgumaLogicaDeNegocio();

echo "\n";

echo "Cliente: Estratégia definida para ordenação reversa.\n";
$contexto->setEstrategia(new EstrategiaConcretaB());
$contexto->fazerAlgumaLogicaDeNegocio();
