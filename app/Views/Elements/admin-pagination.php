<nav>
  <ul class="pagination pagination-no-border">
    <li <?=$pagination['display_page_min'] > 0?'class="disabled"':''?>  >
     
      <a href="<?=( $pagination['display_page_min']-$pagination['per_page'] ) > 0?$pagination['pagination_url'].( $pagination['display_page_min']-$pagination['per_page'] ):$pagination['pagination_url']."1"?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
     
    </li>
    <?php for($x = $pagination['display_page_min'];  $x<$pagination['display_page_max']; $x++): ?>
        <li <?=$pagination['page']==$x?'class="active"':''?>><a href="<?=$pagination['pagination_url'].$x?>"><?=$x?><?=$pagination['page']==$x?'<span class="sr-only">(current)</span>':''?> </a></li>
    <?php endfor; ?>
    <li <?=$pagination['display_page_max']+$pagination['per_page'] > $pagination['total'] ?'class="disabled"':''?>>
      <a href="<?=$pagination['display_page_max'] < $pagination['total'] ?$pagination['pagination_url'].( $pagination['display_page_max']+$pagination['per_page'] ):$pagination['pagination_url'].$pagination['total']?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>