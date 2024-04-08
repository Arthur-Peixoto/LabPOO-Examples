<?php

namespace RefactoringGuru\Memento\Conceptual;

/**
 * O Originador mantém um estado importante que pode mudar ao longo do tempo.
 * Também define um método para salvar o estado dentro de um memento e outro
 * método para restaurar o estado dele.
 */
class Originador
{
    /**
     * @var string Para simplificar, o estado do originador é armazenado dentro de uma única variável.
     */
    private $estado;

    public function __construct(string $estado)
    {
        $this->estado = $estado;
        echo "Originador: Meu estado inicial é: {$this->estado}\n";
    }

    /**
     * A lógica de negócios do Originador pode afetar seu estado interno.
     * Portanto, o cliente deve fazer backup do estado antes de lançar métodos
     * da lógica de negócios por meio do método save().
     */
    public function fazerAlgo(): void
    {
        echo "Originador: Estou fazendo algo importante.\n";
        $this->estado = $this->gerarStringAleatoria(30);
        echo "Originador: e meu estado mudou para: {$this->estado}\n";
    }

    private function gerarStringAleatoria(int $comprimento = 10): string
    {
        return substr(
            str_shuffle(
                str_repeat(
                    $x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    ceil($comprimento / strlen($x))
                )
            ),
            1,
            $comprimento,
        );
    }

    /**
     * Salva o estado atual dentro de um memento.
     */
    public function salvar(): Memento
    {
        return new MementoConcreto($this->estado);
    }

    /**
     * Restaura o estado do Originador a partir de um objeto memento.
     */
    public function restaurar(Memento $memento): void
    {
        $this->estado = $memento->getEstado();
        echo "Originador: Meu estado mudou para: {$this->estado}\n";
    }
}

/**
 * A interface Memento fornece uma maneira de recuperar os metadados do memento,
 * como data de criação ou nome. No entanto, não expõe o estado do Originador.
 */
interface Memento
{
    public function getNome(): string;

    public function getData(): string;
}

/**
 * O Memento Concreto contém a infraestrutura para armazenar o estado do Originador.
 */
class MementoConcreto implements Memento
{
    private $estado;

    private $data;

    public function __construct(string $estado)
    {
        $this->estado = $estado;
        $this->data = date('Y-m-d H:i:s');
    }

    /**
     * O Originador usa este método ao restaurar seu estado.
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * O restante dos métodos é usado pelo Zelador para exibir metadados.
     */
    public function getNome(): string
    {
        return $this->data . " / (" . substr($this->estado, 0, 30) . ")";
    }

    public function getData(): string
    {
        return $this->data;
    }
}

/**
 * O Zelador não depende da classe MementoConcreto. Portanto, não tem acesso
 * ao estado do originador, armazenado dentro do memento. Ele funciona com
 * todos os mementos por meio da interface Memento base.
 */
class Zelador
{
    /**
     * @var Memento[]
     */
    private $mementos = [];

    /**
     * @var Originador
     */
    private $originador;

    public function __construct(Originador $originador)
    {
        $this->originador = $originador;
    }

    public function fazerBackup(): void
    {
        echo "\nZelador: Salvando estado do Originador...\n";
        $this->mementos[] = $this->originador->salvar();
    }

    public function desfazer(): void
    {
        if (!count($this->mementos)) {
            return;
        }
        $memento = array_pop($this->mementos);

        echo "Zelador: Restaurando estado para: " . $memento->getNome() . "\n";
        try {
            $this->originador->restaurar($memento);
        } catch (\Exception $e) {
            $this->desfazer();
        }
    }

    public function mostrarHistorico(): void
    {
        echo "Zelador: Aqui está a lista de mementos:\n";
        foreach ($this->mementos as $memento) {
            echo $memento->getNome() . "\n";
        }
    }
}

/**
 * Código do Cliente.
 */
$originador = new Originador("Vasco da gama");
$zelador = new Zelador($originador);

$zelador->fazerBackup();
$originador->fazerAlgo();

$zelador->fazerBackup();
$originador->fazerAlgo();

$zelador->fazerBackup();
$originador->fazerAlgo();

echo "\n";
$zelador->mostrarHistorico();

echo "\nCliente: Agora, vamos desfazer!\n\n";
$zelador->desfazer();

echo "\nCliente: Mais uma vez!\n\n";
$zelador->desfazer();
