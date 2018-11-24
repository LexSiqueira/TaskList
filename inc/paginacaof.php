<?php 
	$pag_anterior = $pagina - 1;
	$pag_posterior = $pagina + 1;
	
    $inicio = ($pagina*$registros_por_pagina)-$registros_por_pagina;
    if($total_registros<=$registros_por_pagina):
        $total_paginas = 1;
    elseif(($total_registros%$registros_por_pagina)==0):
        $total_paginas = ($total_registros/$registros_por_pagina);
    else:
        $total_paginas = ($total_registros/$registros_por_pagina)+1;
    endif;
    $total_paginas = (int)$total_paginas;
    if($pagina>$total_paginas||$pagina<=0):
        echo "Página Inválida";
        exit;
    endif;
	 $sql .= " LIMIT $inicio,$registros_por_pagina";
?>
