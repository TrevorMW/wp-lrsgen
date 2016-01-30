<?php $html = $class = $content_class = '';

$inc == 0 ? $class         = 'active'         : $class = '' ;
$inc == 0 ? $content_class = 'active visible' : $content_class = '' ;
$inc != 0 ? $load_at       = 'deferred'       : $load_at = 'now' ;


if( $date_title != null )
{
  $html .= '<li data-accordion>
                    <header class="table auto">
                      <div class="table-cell accordion-close '.$class.'"><div><a href="#" class="'.$class.'" data-accordion-trigger><i class="fa fa-times"></i></a></div></div>
                      <div class="table-cell"><a href="#" class="'.$class.'" data-accordion-trigger>'.$date_title.' </a></div>
                    </header>
                    <section class="'.$content_class.'" data-accordion-content>
                      <div data-loadable-content="get_date_data" data-load-when="'.$load_at.'" data-load-extra="'.strtotime( $date_title ).'">

                      </div>
                    </section>
                  </li>';
}

echo $html;