<?php

/**
 * A interface Componente declara um método `aceitar` que deve receber a interface
 * de visitante base como argumento.
 */
interface Componente
{
    public function aceitar(Visitante $visitante): void;
}

/**
 * Cada Componente Concreto deve implementar o método `aceitar` de forma que
 * ele chame o método do visitante correspondente à classe do componente.
 */
class ComponenteConcretoA implements Componente
{
    /**
     * Note que estamos chamando `visitarComponenteConcretoA`, que corresponde ao
     * nome da classe atual. Dessa forma, informamos ao visitante a classe do
     * componente com o qual ele está trabalhando.
     */
    public function aceitar(Visitante $visitante): void
    {
        $visitante->visitarComponenteConcreto($this);
    }

    /**
     * Componentes Concretos podem ter métodos especiais que não existem em sua
     * classe base ou interface. O Visitante ainda pode usar esses métodos, pois
     * está ciente da classe concreta do componente.
     */
    public function metodoExclusivoDoComponenteConcretoA(): string
    {
        return "A";
    }
}

class ComponenteConcretoB implements Componente
{
    /**
     * Mesmo aqui: visitarComponenteConcretoB => ComponenteConcretoB
     */
    public function aceitar(Visitante $visitante): void
    {
        $visitante->visitarComponenteConcretoB($this);
    }

    public function metodoEspecialDoComponenteConcretoB(): string
    {
        return "B";
    }
}

/**
 * A Interface Visitante declara um conjunto de métodos de visita que correspondem
 * às classes de componentes. A assinatura de um método de visita permite ao
 * visitante identificar a classe exata do componente com o qual está lidando.
 */
interface Visitante
{
    public function visitarComponenteConcretoA(ComponenteConcretoA $elemento): void;

    public function visitarComponenteConcretoB(ComponenteConcretoB $elemento): void;
}

/**
 * Visitantes Concretos implementam várias versões do mesmo algoritmo, que
 * podem trabalhar com todas as classes concretas de componentes.
 *
 * Você pode experimentar o maior benefício do padrão Visitante ao usá-lo com
 * uma estrutura de objeto complexa, como uma árvore Composta. Nesse caso, pode
 * ser útil armazenar algum estado intermediário do algoritmo enquanto executa
 * os métodos do visitante sobre vários objetos da estrutura.
 */
class VisitanteConcreto1 implements Visitante
{
    public function visitarComponenteConcretoA(ComponenteConcretoA $elemento): void
    {
        echo $elemento->metodoExclusivoDoComponenteConcretoA() . " + VisitanteConcreto1\n";
    }

    public function visitarComponenteConcretoB(ComponenteConcretoB $elemento): void
    {
        echo $elemento->metodoEspecialDoComponenteConcretoB() . " + VisitanteConcreto1\n";
    }
}

class VisitanteConcreto2 implements Visitante
{
    public function visitarComponenteConcretoA(ComponenteConcretoA $elemento): void
    {
        echo $elemento->metodoExclusivoDoComponenteConcretoA() . " + VisitanteConcreto2\n";
    }

    public function visitarComponenteConcretoB(ComponenteConcretoB $elemento): void
    {
        echo $elemento->metodoEspecialDoComponenteConcretoB() . " + VisitanteConcreto2\n";
    }
}

/**
 * O código do cliente pode executar operações de visitante sobre qualquer
 * conjunto de elementos sem precisar saber suas classes concretas. A operação
 * aceitar direciona uma chamada para a operação apropriada no objeto visitante.
 */
function codigoDoCliente(array $componentes, Visitante $visitante)
{
    // ...
    foreach ($componentes as $componente) {
        $componente->aceitar($visitante);
    }
    // ...
}

$componentes = [
    new ComponenteConcretoA(),
    new ComponenteConcretoB(),
];

echo "O código do cliente trabalha com todos os visitantes via interface Visitante base:\n";
$visitante1 = new VisitanteConcreto1();
codigoDoCliente($componentes, $visitante1);
echo "\n";

echo "Isso permite que o mesmo código do cliente trabalhe com diferentes tipos de visitantes:\n";
$visitante2 = new VisitanteConcreto2();
codigoDoCliente($componentes, $visitante2);

