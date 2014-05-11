<?php
$sovereign = $modx->getService('sovereign','Sovereign',$modx->getOption('sovereign.core_path',null,$modx->getOption('core_path').'components/sovereign/').'model/sovereign/',$scriptProperties);
if (!($sovereign instanceof Sovereign)) return '';

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'pastGalleriesList');
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$limit = $modx->getOption('limit',$scriptProperties,6);
$offset = $modx->getOption('offset',$scriptProperties,0);
$totalVar = $modx->getOption('totalVar', $scriptProperties, 'total');

$record = $modx->query("SELECT MAX(id) FROM {$modx->getTableName('africanGalleries')} WHERE phase=0");
$highestId = (integer) $record->fetch(PDO::FETCH_COLUMN);
$record->closeCursor();


$c = $modx->newQuery('africanGalleries');
if(!empty($highestId)) {
    $c->where(array(
        'id:!=' => $highestId
    ));
}
$total = $modx->getCount('africanGalleries',$c);
$modx->setPlaceholder($totalVar,$total);


$c->limit($limit,$offset);
$c->sortby($sort,$dir);
$galleries = $modx->getCollection('africanGalleries',$c);


$output = '';
foreach ($galleries as $gallery) {
    $galleryArray = $gallery->toArray();
    $output .= $sovereign->getChunk($tpl,$galleryArray);
    //$modx->log(modX::LOG_LEVEL_DEBUG, $sovereign->getChunk($tpl,$artworkArray));
}
return $output;