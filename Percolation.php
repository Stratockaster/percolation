<?php

class Percolation
{
    private $n;

    private $virtualTopSiteIndex;
    private $virtualBottomSiteIndex;

    private $openedSites;

    private $unionFind;

    public function __construct(int $n)
    {
        if ($n < 0) {
            throw new InvalidArgumentException('n must be greater than 0.');
        }

        $this->n = $n;
        $this->virtualTopSiteIndex = 0;
        $this->virtualBottomSiteIndex = $n * $n + 1;
        $this->unionFind = new QuickFindUf($n * $n + 2);
        $this->openedSites = array_fill(0, $n * $n + 2, false);
        $this->openedSites[$this->virtualTopSiteIndex] = true;
        $this->openedSites[$this->virtualBottomSiteIndex] = false;
    }

    public function getIndexFromRowAndCol(int $row, int $col)
    {
        return ($row - 1) * $this->n + $col;
    }

    public function open(int $row, int $col): void
    {
        $siteToOpenIndex = $this->getIndexFromRowAndCol($row, $col);
        $this->openedSites[$siteToOpenIndex] = true;

        if ($row === 1) {
            $this->unionFind->quickUnion($siteToOpenIndex, $this->virtualTopSiteIndex);
        }
        if ($row === $this->n) {
            $this->unionFind->quickUnion($siteToOpenIndex, $this->virtualBottomSiteIndex);
        }

        $this->tryUnion($row, $col, $row - 1, $col);
        $this->tryUnion($row, $col, $row + 1, $col);
        $this->tryUnion($row, $col, $row, $col - 1);
        $this->tryUnion($row, $col, $row, $col + 1);
    }

    private function tryUnion(int $rowA, int $colA, int $rowB, int $colB): void
    {
        if ($rowB > 0 && $rowB <= $this->n && $colB > 0 && $colB <= $this->n && $this->isOpen($rowB, $colB)) {
            $this->unionFind->quickUnion($this->getIndexFromRowAndCol($rowA, $colA), $this->getIndexFromRowAndCol($rowB, $colB));
        }
    }

    public function isOpen(int $row, int $col): bool
    {
        return $this->openedSites[$this->getIndexFromRowAndCol($row, $col)];
    }

    private function isFull(int $row, int $col): bool
    {
        return $this->unionFind->connected($this->virtualTopSiteIndex, $this->getIndexFromRowAndCol($row, $col));
    }

    public function numberOfOpenSites(): int
    {
        $count = 0;
        foreach ($this->openedSites as $openedSite) {
            if ($openedSite === true) {
                $count++;
            }
        }

        return $count;
    }

    public function percolates(): bool
    {
        return $this->unionFind->connected($this->virtualTopSiteIndex, $this->virtualBottomSiteIndex);
    }
}
