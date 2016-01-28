<?php $html = $class = $content_class = '';

$inc == 0 ? $class         = 'active'         : $class = '' ;
$inc == 0 ? $content_class = 'active visible' : $content_class = '' ;

if( $date_title != null )
{
  $html .= '<li data-accordion>
                    <header class="table auto">
                      <div class="table-cell accordion-close '.$class.'"><div><a href="#" class="'.$class.'" data-accordion-trigger><i class="fa fa-times"></i></a></div></div>
                      <div class="table-cell"><a href="#" class="'.$class.'" data-accordion-trigger>'.$date_title.' </a></div>
                    </header>
                    <section class="'.$content_class.'" data-accordion-content>

                    </section>
                  </li>';
}

echo $html;