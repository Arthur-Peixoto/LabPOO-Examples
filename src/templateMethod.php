<?php

namespace RefactoringGuru\TemplateMethod\Conceptual;

/**
 * A classe Abstract define um método modelo que contém um esqueleto de algum
 * algoritmo, composto de chamadas para (normalmente) operações primitivas abstratas.
 *
 * Subclasses concretas devem implementar essas operações, mas deixar o método template
 * em si intacto.
 */
abstract class AbstractClass
{
    /**
     * O método template define o esqueleto de um algoritmo.
     */
    public final function templateMethod(): void
    {
        $this->baseOperation1();
        $this->requiredOperations1();
        $this->baseOperation2();
        $this->hook1();
        $this->requiredOperation2();
        $this->baseOperation3();
        $this->hook2();
    }

    /**
     * Estas operações já possuem implementações.
     */
    protected function baseOperation1(): void
    {
        echo "AbstractClass diz: Estou fazendo a maior parte do trabalho\n";
    }

    protected function baseOperation2(): void
    {
        echo "AbstractClass diz: Mas deixei as subclasses substituírem algumas operações\n";
    }

    protected function baseOperation3(): void
    {
        echo "AbstractClass diz: Mas estou fazendo a maior parte do trabalho de qualquer maneira\n";
    }

    /**
     * Estas operações devem ser implementadas em subclasses.
     */
    protected abstract function requiredOperations1(): void;

    protected abstract function requiredOperation2(): void;

    /**
     * Estes são "ganchos". As subclasses podem substituí-los, mas não é obrigatório
     * pois os ganchos já possuem implementação padrão (mas vazia). Os ganchos
     * fornecem pontos de extensão adicionais em alguns locais cruciais do
     * algoritmo.
     */
    protected function hook1(): void { }

    protected function hook2(): void { }
}

/**
 * Classes concretas precisam implementar todas as operações abstratas da classe base.
 * Eles também podem substituir algumas operações por uma implementação padrão.
 */
class ConcreteClass1 extends AbstractClass
{
    protected function requiredOperations1(): void
    {
        echo "ConcretoClass1 diz: Operação implementada1\n";
    }

    protected function requiredOperation2(): void
    {
        echo "ConcretoClass1 diz: Operação2 implementada\n";
    }
}

/**
 * Normalmente, as classes concretas substituem apenas uma fração das operações da classe base.
 */
class ConcreteClass2 extends AbstractClass
{
    protected function requiredOperations1(): void
    {
        echo "ConcreteClass2 diz: Operação Implementada1\n";
    }

    protected function requiredOperation2(): void
    {
        echo "ConcretoClass2 diz: Operação2 implementada\n";
    }

    protected function hook1(): void
    {
        echo "ConcretoClass2 diz: Hook1 substituído\n";
    }
}

/**
 * O código do cliente chama o método de modelo para executar o algoritmo. O código cliente
 * não precisa conhecer a classe concreta de um objeto com o qual trabalha, desde que
 * trabalhe com objetos por meio da interface de sua classe base.
 */
function clientCode(AbstractClass $class)
{
    // ...
    $class->templateMethod();
    // ...
}

echo "O mesmo código cliente pode funcionar com subclasses diferentes:\n";
clientCode(new ConcreteClass1());
echo "\n";

echo "O mesmo código cliente pode funcionar com subclasses diferentes:\n";
clientCode(new ConcreteClass2());
