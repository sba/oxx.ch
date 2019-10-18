<?php
$pot = $cls_benutzer->calc_pot();
?>
<h3><?=parse($lg_informationen)?></h3>

<?=parse($lg_einsatz_pro_person)?>:<br/>
<b><?=int2money($cfg_einsatz['betrag'])?> CHF</b><br/><br/>

<?=parse($lg_gesamtbetrag_im_topf)?>:<br/>
<b><?=int2money($pot)?> CHF</b><br/><br/>

<?=parse($lg_gewinnsaetze)?>:<br/>
1. <?=$lg_rang?>: <b>50%</b><br/>
2. <?=$lg_rang?>: <b>25%</b><br/>
3. <?=$lg_rang?>: <b>15%</b><br/>
4. <?=$lg_rang?>: <b>5%</b><br/>
5. <?=$lg_rang?>: <b>5%</b>

<br/><br/>

<h3><?=parse($lg_disclaimer)?></h3>

<?=$lg_disclaimer_text_money?>