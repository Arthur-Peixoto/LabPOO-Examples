<?php

class Contexto
{
    /**
     * @var State Uma referência para o estado atual do Contexto.
     */
    private $estado;

    public function __construct(State $estado)
    {
        $this->transitarPara($estado);
    }

    /**
     * O Contexto permite mudar o objeto State em tempo de execução.
     */
    public function transitarPara(State $estado): void
    {
        echo "Contexto: Transição para " . get_class($estado) . ".\n";
        $this->estado = $estado;
        $this->estado->setContexto($this);
    }

    /**
     * O Contexto delega parte de seu comportamento para o objeto State atual.
     */

    public function requisicao1(): void
    {
        $this->estado->tratar1();
    }

    public function requisicao2(): void
    {
        $this->estado->tratar2();
    }
}

/**
 * A classe base State declara métodos que todos os State Concretos devem
 * implementar e também fornece uma referência de volta ao objeto Contexto,
 * associado com o State. Essa referência de volta pode ser usada pelos States
 * para transitar o Contexto para outro State.
 */

abstract class State
{
    /**
     * @var Contexto
     */
    protected $contexto;

    public function setContexto(Contexto $contexto)
    {
        $this->contexto = $contexto;
    }

    abstract public function tratar1(): void;

    abstract public function tratar2(): void;
}

/**
 * Os State Concretos implementam vários comportamentos associados a um estado
 * do Contexto.
 */

class EstadoConcretoA extends State
{
    public function tratar1(): void
    {
        echo "Estado Concreto A trata requisição 1.\n";
        echo "Estado Concreto A deseja mudar o estado do contexto.\n";
        $this->contexto->transitarPara(new EstadoConcretoB());
    }

    public function tratar2(): void
    {
        echo "Estado Concreto A trata requisição 2.\n";
    }
}

class EstadoConcretoB extends State
{
    public function tratar1(): void
    {
        echo "Estado Concreto B trata requisição 1.\n";
    }

    public function tratar2(): void
    {
        echo "Estado Concreto B trata requisição 2.\n";
        echo "Estado Concreto B deseja mudar o estado do contexto.\n";
        $this->contexto->transitarPara(new EstadoConcretoA());
    }
}

/**
 * O código do cliente.
 */
$contexto = new Contexto(new EstadoConcretoA());
$contexto->requisicao1();
$contexto->requisicao2();
