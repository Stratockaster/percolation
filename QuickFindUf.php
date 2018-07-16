<?php

class QuickFindUf
{
    private $id;

    public function __construct(int $n)
    {
        $this->id = range(0, $n);
    }

    public function connected(int $p, int $q): bool
    {
        return $this->root($p) === $this->root($q);
    }

    public function quickUnion(int $p, int $q) :void
    {
        $pRoot = $this->root($p);
        $qRoot = $this->root($q);

        $this->id[$pRoot] = $qRoot;
    }

    private function root(int $i): int
    {
        while ($i !== $this->id[$i]) {
            $this->id[$i] = $this->id[$this->id[$i]]; //path compression

            $i = $this->id[$i];
        }

        return $i;
    }

    public function getId(): array
    {
        return $this->id;
    }
}
