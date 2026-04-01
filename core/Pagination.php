<?php
declare(strict_types=1);
namespace Core;

class Pagination
{
    public int $total;
    public int $currentPage;
    public int $perPage;
    public int $totalPages;
    public int $limit;
    public int $offset;

    public function __construct(int $total, int $currentPage = 1, int $perPage = ITEMS_PER_PAGE)
    {
        $this->total      = $total;
        $this->perPage    = $perPage;
        $this->totalPages = max(1, (int) ceil($total / $perPage));
        $this->currentPage= max(1, min($currentPage, $this->totalPages));
        $this->limit      = $perPage;
        $this->offset     = ($this->currentPage - 1) * $perPage;
    }

    public function hasPrev(): bool { return $this->currentPage > 1; }
    public function hasNext(): bool { return $this->currentPage < $this->totalPages; }

    public function render(string $baseUrl, array $extraParams = []): string
    {
        if ($this->totalPages <= 1) return '';
        $params = $extraParams;
        $html   = '<nav class="pagination"><ul>';
        if ($this->hasPrev()) {
            $params['page'] = $this->currentPage - 1;
            $html .= '<li><a href="' . $baseUrl . '?' . http_build_query($params) . '">&laquo;</a></li>';
        }
        foreach ($this->getRange() as $page) {
            if ($page === '...') {
                $html .= '<li class="ellipsis"><span>…</span></li>';
            } else {
                $params['page'] = $page;
                $active = $page === $this->currentPage ? ' class="active"' : '';
                $html .= "<li{$active}><a href=\"{$baseUrl}?" . http_build_query($params) . "\">{$page}</a></li>";
            }
        }
        if ($this->hasNext()) {
            $params['page'] = $this->currentPage + 1;
            $html .= '<li><a href="' . $baseUrl . '?' . http_build_query($params) . '">&raquo;</a></li>';
        }
        $html .= '</ul></nav>';
        return $html;
    }

    private function getRange(): array
    {
        if ($this->totalPages <= 7) return range(1, $this->totalPages);
        $pages = [1];
        if ($this->currentPage > 3) $pages[] = '...';
        foreach (range(max(2, $this->currentPage - 1), min($this->totalPages - 1, $this->currentPage + 1)) as $p) {
            $pages[] = $p;
        }
        if ($this->currentPage < $this->totalPages - 2) $pages[] = '...';
        $pages[] = $this->totalPages;
        return $pages;
    }
}
