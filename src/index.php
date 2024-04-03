<?php

/**
 * PHP possui algumas interfaces integradas relacionadas ao padrão Observer.
 *
 * Veja como é a interface Subject:
 *
 * @link http://php.net/manual/en/class.splsubject.php
 *
 *     interface SplSubject
 *     { 
 *         // Anexa um observador ao subject.
 *         public function attach(SplObserver $observer);
 *
 *         // Desanexa um observador do subject.
 *         public function detach(SplObserver $observer);
 *
 *         // Notifica todos os observadores sobre um evento.
 *         public function notify();
 *     }
 *
 * Também há uma interface integrada para Observers:
 *
 * @link http://php.net/manual/en/class.splobserver.php
 *
 *     interface SplObserver
 *     {
 *         public function update(SplSubject $subject);
 *     }
 */

/**
 * O Subject possui um estado importante e notifica observadores quando o estado muda.
 */
class Subject implements \SplSubject
{
    /**
     * @var int Para simplificar, o estado do Subject, essencial para todos os assinantes, é armazenado nesta variável.
     */
    public $state;

    /**
     * @var \SplObjectStorage
     */
    private $observers;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * Os métodos de gerenciamento de inscrição.
     */
    public function attach(\SplObserver $observer): void
    {
        echo "Subject: Observador conectado.\n";
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
        echo "Subject: Observador desconectado.\n";
    }

    /**
     * Dispara uma atualização em cada assinante.
     */
    public function notify(): void
    {
        echo "Subject: Notificando observadores...\n";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Normalmente, a lógica de inscrição é apenas uma fração do que um Subject realmente pode fazer. Os Subjects geralmente contêm alguma lógica de negócios importante, que aciona um método de notificação sempre que algo importante está prestes a acontecer (ou depois disso).
     */
    public function someBusinessLogic(): void
    {
        echo "\nSubject: Estou fazendo algo importante.\n";
        $this->state = rand(0, 10);

        echo "Subject: Meu estado acabou de mudar para: {$this->state}\n";
        $this->notify();
    }
}

/**
 * Observadores Concretos reagem às atualizações emitidas pelo Subject a que foram anexados.
 */
class ConcreteObserverA implements \SplObserver
{
    public function update(\SplSubject $subject): void
    {
        if ($subject->state < 3) {
            echo "ConcreteObserverA: Reagiu ao evento.\n";
        }
    }
}

class ConcreteObserverB implements \SplObserver
{
    public function update(\SplSubject $subject): void
    {
        if ($subject->state == 0 || $subject->state >= 2) {
            echo "ConcreteObserverB: Reagiu ao evento.\n";
        }
    }
}

/**
 * Cliente.
 */

$subject = new Subject();

$o1 = new ConcreteObserverA();
$subject->attach($o1);

$o2 = new ConcreteObserverB();
$subject->attach($o2);

$subject->someBusinessLogic();
$subject->someBusinessLogic();

$subject->detach($o2);

$subject->someBusinessLogic();
