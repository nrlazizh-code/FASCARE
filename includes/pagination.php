<?php
function renderPagination($totalData, $limit, $currentPage, $baseUrlParams = '') {
    $totalPages = ceil($totalData / $limit);
    if ($totalPages <= 1) return;

    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $currentPage) ? 'active' : '';
        $separator = empty($baseUrlParams) ? '?' : "?{$baseUrlParams}&";
        echo "<a href='{$separator}page={$i}' class='page-link {$active}'>{$i}</a>";
    }
    echo '</div>';
}