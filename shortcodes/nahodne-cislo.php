<?php

$min = $shortcode->getParameter("od", 10); // druhy parameter je vychozi (defoltni) parameter pokud ten parameter nebude zadan adminem
$max = $shortcode->getParameter("do");  // getParameter (set parameter "do" and get content toho parametru)
echo rand($min, $max);